<?php

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Services\InventoryService;
use App\Support\InventoryTestProfile;
use App\Support\StockQuantity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (! InventoryTestProfile::active() || DB::selectOne('SELECT DATABASE() AS db')->db !== InventoryTestProfile::DATABASE || config('database.connections.mysql.username') !== 'mnm_c1_test_runtime') throw new LogicException('Concurrency fixture must use isolated runtime');
chdir(base_path());

function ensure(bool $condition, string $message): void
{
    if (! $condition) throw new RuntimeException($message);
}

if (($argv[1] ?? '') === 'worker') {
    try {
        $input = json_decode(base64_decode($argv[2]), true, 512, JSON_THROW_ON_ERROR);
        file_put_contents($input['ready'], 'ready');
        $movement = app(InventoryService::class)->move(User::where('role', 'admin')->firstOrFail(), $input['data']);
        echo json_encode(['status' => 'ok', 'id' => $movement->id, 'after' => $movement->quantity_after]);
    } catch (Illuminate\Validation\ValidationException $error) {
        echo json_encode(['status' => 'validation', 'fields' => array_keys($error->errors())]);
    } catch (Throwable $error) {
        echo json_encode(['status' => 'error', 'type' => get_class($error), 'code' => (string) $error->getCode()]);
        exit(1);
    }
    exit;
}

$run = bin2hex(random_bytes(4));
$directory = base_path('.agent/logs/c1/races/'.$run);
mkdir($directory, 0700, true);
$admin = User::where('role', 'admin')->firstOrFail();
$sample = Product::where('code', 'DEMO-01')->firstOrFail();
$service = app(InventoryService::class);
$results = [];
$productIds = [];
$newProduct = function (string $suffix) use ($run, $sample, &$productIds) {
    $product = Product::create(['code' => 'QA-C1-'.$run.'-'.$suffix, 'name' => 'Vật tư kiểm thử đồng thời '.$suffix, 'category_id' => $sample->category_id, 'unit_id' => $sample->unit_id, 'sale_price' => '1.00']);
    $productIds[] = $product->id;

    return $product;
};
$data = fn (Product $product, string $quantity, string $direction = 'in', ?string $token = null) => ['product_id' => $product->id, 'quantity' => $quantity, 'direction' => $direction, 'note' => 'QA C1 concurrency '.$run, 'idempotency_key' => $token ?? (string) Str::uuid()];
$race = function (array $inputs) use ($directory) {
    $children = [];
    $lockedIds = array_unique(array_column($inputs, 'product_id'));
    sort($lockedIds, SORT_STRING);
    DB::beginTransaction();
    Product::whereIn('id', $lockedIds)->orderBy('id')->lockForUpdate()->get();
    try {
        foreach ($inputs as $index => $input) {
            $ready = $directory.'/'.bin2hex(random_bytes(4)).'-'.$index.'.ready';
            $process = proc_open([PHP_BINARY, __FILE__, 'worker', base64_encode(json_encode(['ready' => $ready, 'data' => $input]))], [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, base_path());
            ensure(is_resource($process), 'Unable to launch concurrent worker');
            fclose($pipes[0]);
            $children[] = compact('process', 'pipes', 'ready');
        }
        $deadline = microtime(true) + 40;
        do {
            $ready = count(array_filter($children, fn ($child) => is_file($child['ready']))) === count($children);
            if ($ready) break;
            if (microtime(true) > $deadline) throw new RuntimeException('Workers did not reach lock barrier');
            usleep(50000);
        } while (true);
        usleep(300000);
        foreach ($children as $child) ensure(proc_get_status($child['process'])['running'], 'Worker finished before parent released product lock');
    } finally {
        DB::rollBack();
    }
    $responses = [];
    foreach ($children as $child) {
        $output = stream_get_contents($child['pipes'][1]);
        $stderr = stream_get_contents($child['pipes'][2]);
        fclose($child['pipes'][1]);
        fclose($child['pipes'][2]);
        $exit = proc_close($child['process']);
        $response = json_decode($output, true);
        ensure($response !== null && ! $stderr && $exit === 0 && $response['status'] !== 'error', 'Concurrent worker failed');
        $responses[] = $response;
    }

    return $responses;
};

$p = $newProduct('first');
$responses = $race([$data($p, '2.125'), $data($p, '3.875')]);
ensure(array_column($responses, 'status') === ['ok', 'ok'] && $p->fresh()->stock->quantity === '6.000' && $p->inventoryMovements()->count() === 2, 'First-stock insert race failed');
$results[] = ['case' => 'concurrent_first_stock', 'status' => 'PASS', 'quantity' => '6.000', 'movements' => 2, 'lock_barrier' => true];

$p = $newProduct('overdraw');
$service->move($admin, $data($p, '20'));
$responses = $race([$data($p, '15', 'out'), $data($p, '15', 'out')]);
$statuses = array_column($responses, 'status');
sort($statuses);
ensure($statuses === ['ok', 'validation'] && $p->fresh()->stock->quantity === '5.000' && $p->inventoryMovements()->count() === 2, 'Concurrent overdraw failed');
$results[] = ['case' => 'concurrent_overdraw', 'status' => 'PASS', 'quantity' => '5.000', 'one_rejected' => true, 'lock_barrier' => true];

$p = $newProduct('duplicate');
$input = $data($p, '7.001');
$responses = $race([$input, $input]);
ensure(array_column($responses, 'status') === ['ok', 'ok'] && $responses[0]['id'] === $responses[1]['id'] && $p->fresh()->stock->quantity === '7.001' && $p->inventoryMovements()->count() === 1, 'Concurrent duplicate failed');
$results[] = ['case' => 'concurrent_identical_replay', 'status' => 'PASS', 'quantity' => '7.001', 'movements' => 1, 'lock_barrier' => true];

$a = $newProduct('conflict-a');
$b = $newProduct('conflict-b');
$key = (string) Str::uuid();
$responses = $race([$data($a, '1', 'in', $key), $data($b, '1', 'in', $key)]);
$statuses = array_column($responses, 'status');
sort($statuses);
ensure($statuses === ['ok', 'validation'] && InventoryMovement::where('idempotency_key', $key)->count() === 1 && ProductStock::whereIn('product_id', [$a->id, $b->id])->count() === 1, 'Cross-product token rollback failed');
$results[] = ['case' => 'concurrent_conflicting_payload', 'status' => 'PASS', 'one_rolled_back' => true, 'lock_barrier' => true];

foreach (Product::whereIn('id', $productIds)->get() as $product) {
    $balance = 0;
    foreach ($product->inventoryMovements()->orderBy('id')->get() as $movement) {
        ensure(StockQuantity::milli($movement->quantity_before) === $balance, 'History chain before mismatch');
        $change = StockQuantity::milli($movement->quantity);
        $balance += $movement->direction === 'in' ? $change : -$change;
        ensure(StockQuantity::milli($movement->quantity_after) === $balance, 'History chain after mismatch');
    }
    ensure(StockQuantity::milli($product->stock?->quantity ?? '0') === $balance, 'Stock/history mismatch');
}
$receipt = ['status' => 'PASS', 'database' => InventoryTestProfile::DATABASE, 'real_php_process_workers' => 8, 'cases' => $results, 'fixture_product_ids' => $productIds, 'all_stock_history_chains_match' => true];
file_put_contents($directory.'/result.json', json_encode($receipt, JSON_PRETTY_PRINT));
echo json_encode($receipt, JSON_PRETTY_PRINT);
