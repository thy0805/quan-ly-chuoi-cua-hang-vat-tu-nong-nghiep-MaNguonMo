<?php

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\SaleService;
use App\Support\InventoryTestProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

require dirname(__DIR__,2).'/vendor/autoload.php';
$app = require dirname(__DIR__,2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (! InventoryTestProfile::active() || DB::selectOne('SELECT DATABASE() AS db')->db !== InventoryTestProfile::DATABASE || config('database.connections.mysql.username') !== 'mnm_c1_test_runtime') throw new LogicException('Isolated runtime only');
chdir(base_path());
function verifySale(bool $ok, string $message): void { if (! $ok) throw new RuntimeException($message); }
if (($argv[1] ?? '') === 'worker') {
    try {
        $input=json_decode(base64_decode($argv[2]),true,512,JSON_THROW_ON_ERROR);
        file_put_contents($input['ready'],'ready');
        $user=User::where('role','admin')->firstOrFail();
        $sale = $input['mode']==='sale' ? app(SaleService::class)->confirm(User::where('username','nhanvien_demo_1')->firstOrFail(),$input['sale_id'],1) : ($input['mode']==='draft' ? app(PurchaseService::class)->draft($user,$input['data']) : app(PurchaseService::class)->confirm($user,$input['purchase_id'],1));
        echo json_encode(['status'=>'ok','id'=>$sale->id,'state'=>$sale->status]);
    } catch (Illuminate\Validation\ValidationException $e) {
        echo json_encode(['status'=>'validation','fields'=>array_keys($e->errors())]);
    } catch (Throwable $e) {
        echo json_encode(['status'=>'error','type'=>get_class($e),'code'=>(string)$e->getCode()]);exit(1);
    }
    exit;
}
$run=bin2hex(random_bytes(4));
$dir=base_path('.agent/logs/c3/races/'.$run);mkdir($dir,0700,true);
$admin=User::where('role','admin')->firstOrFail();$user=User::where('username','nhanvien_demo_1')->firstOrFail();$sample=Product::where('code','DEMO-01')->firstOrFail();
$newProduct=function(string $label,string $qty='10') use($run,$sample,$admin) {
    $p=Product::create(['code'=>'QA-C3-'.$run.'-'.$label,'name'=>'Vật tư race nhập hàng '.$label,'category_id'=>$sample->category_id,'unit_id'=>$sample->unit_id,'sale_price'=>'1.00']);
    app(InventoryService::class)->move($admin,['product_id'=>$p->id,'direction'=>'in','quantity'=>$qty,'idempotency_key'=>(string)Str::uuid()]);return $p;
};
$supplier=Supplier::create(['code'=>'QA-C3-'.$run,'name'=>'Nhà cung cấp race C3']);
$draft=function(array $rows,?string $token=null) use($admin,$supplier) { return app(PurchaseService::class)->draft($admin,['supplier_id'=>$supplier->id,'client_key'=>$token ?? (string)Str::uuid(),'lines'=>$rows]); };
$row=fn($p,$q='1')=>['product_id'=>$p->id,'quantity'=>$q,'unit_price'=>'1.00'];
$confirm=fn($purchase)=>['mode'=>'confirm','purchase_id'=>$purchase->id];
$race=function(array $payloads,array $products) use($dir) {
    DB::beginTransaction();Product::whereIn('id',$products)->orderBy('id')->lockForUpdate()->get();$children=[];
    try {
        foreach($payloads as $i=>$data) {
            $ready=$dir.'/'.bin2hex(random_bytes(5)).'.ready';$data['ready']=$ready;
            $process=proc_open([PHP_BINARY,__FILE__,'worker',base64_encode(json_encode($data))],[0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']],$pipes,base_path());
            verifySale(is_resource($process),'Worker not launched');fclose($pipes[0]);$children[]=compact('process','pipes','ready');
        }
        $deadline=microtime(true)+40;
        do {
            $ready=count(array_filter($children,fn($c)=>is_file($c['ready'])))===count($children);
            if($ready)break;
            if(microtime(true)>$deadline)throw new RuntimeException('Barrier timeout');
            usleep(50000);
        } while(true);
        usleep(250000);
        foreach($children as $c)verifySale(proc_get_status($c['process'])['running'],'Worker bypassed locked product');
    } finally { DB::rollBack(); }
    $outputs=[];
    foreach($children as $c) {
        $out=stream_get_contents($c['pipes'][1]);$err=stream_get_contents($c['pipes'][2]);fclose($c['pipes'][1]);fclose($c['pipes'][2]);$exit=proc_close($c['process']);$data=json_decode($out,true);
        verifySale($exit===0 && $data && !$err && $data['status']!=='error','Worker error: '.$out);$outputs[]=$data;
    }
    return $outputs;
};
$results=[];
$p=$newProduct('same');$a=$draft([$row($p,'2')]);$out=$race([$confirm($a),$confirm($a)],[$p->id]);
verifySale(array_column($out,'status')===['ok','ok'] && $p->fresh()->stock->quantity==='12.000' && InventoryMovement::where('source_type','purchase')->whereIn('source_id',$a->lines->pluck('id'))->count()===1,'Duplicate confirmation');
$results[]=['case'=>'same_purchase_two_confirmations','workers'=>2,'outcomes'=>$out,'final_stock'=>'12.000'];
$p=$newProduct('two-purchases');$a=$draft([$row($p,'4')]);$b=$draft([$row($p,'4')]);$out=$race([$confirm($a),$confirm($b)],[$p->id]);
verifySale(array_column($out,'status')===['ok','ok'] && $p->fresh()->stock->quantity==='18.000','Lost stock update');
$results[]=['case'=>'two_purchases_same_product','workers'=>2,'outcomes'=>$out,'final_stock'=>'18.000'];
$p=$newProduct('reverse-a');$q=$newProduct('reverse-b');$a=$draft([$row($p),$row($q)]);$b=$draft([$row($q),$row($p)]);$out=$race([$confirm($a),$confirm($b)],[$p->id,$q->id]);
verifySale(array_column($out,'status')===['ok','ok'] && $p->fresh()->stock->quantity==='12.000' && $q->fresh()->stock->quantity==='12.000','Reverse product order');
$results[]=['case'=>'reverse_multiline_product_order','workers'=>2,'outcomes'=>$out,'final_stocks'=>['12.000','12.000']];
$p=$newProduct('replay');$data=['supplier_id'=>$supplier->id,'client_key'=>(string)Str::uuid(),'lines'=>[$row($p)]];$out=$race([['mode'=>'draft','data'=>$data],['mode'=>'draft','data'=>$data]],[$p->id]);
verifySale(array_column($out,'status')===['ok','ok'] && $out[0]['id']===$out[1]['id'] && $p->fresh()->stock->quantity==='10.000' && Purchase::findOrFail($out[0]['id'])->lines()->count()===1,'Duplicate draft');
$results[]=['case'=>'same_client_key_draft','workers'=>2,'outcomes'=>$out,'final_stock'=>'10.000'];
$p=$newProduct('sale-purchase','5');$a=$draft([$row($p,'2')]);$sale=app(SaleService::class)->draft($user,['client_key'=>(string)Str::uuid(),'lines'=>[$row($p,'4')]]);$out=$race([$confirm($a),['mode'=>'sale','sale_id'=>$sale->id]],[$p->id]);
verifySale(array_column($out,'status')===['ok','ok'] && $p->fresh()->stock->quantity==='3.000','Concurrent sale/purchase lost update');
$results[]=['case'=>'purchase_and_sale_same_product','workers'=>2,'outcomes'=>$out,'final_stock'=>'3.000'];
$p=$newProduct('overflow','999999999998.999');$a=$draft([$row($p)]);$b=$draft([$row($p)]);$out=$race([$confirm($a),$confirm($b)],[$p->id]);$statuses=array_column($out,'status');sort($statuses);
verifySale($statuses===['ok','validation'] && $p->fresh()->stock->quantity==='999999999999.999','Concurrent overflow');
$results[]=['case'=>'two_purchases_stock_overflow','workers'=>2,'outcomes'=>$out,'final_stock'=>'999999999999.999'];
echo json_encode(['status'=>'PASS','run'=>$run,'cases'=>$results,'workers'=>12,'barrier'=>'Parent product lock, both workers ready and running before release','database'=>InventoryTestProfile::DATABASE],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
