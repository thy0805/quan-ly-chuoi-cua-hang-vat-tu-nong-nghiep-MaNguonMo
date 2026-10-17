# Minh họa Laravel từ code thật — G6 DRAFT

Các đoạn dưới trích trực tiếp source đã khóa G5, có đường dẫn/dòng và liên kết commit bất biến; chỉ để giải thích, không là script cần chạy. Không dùng DB::table làm thay thế ORM cho CRUD: controller/model sử dụng Eloquent; DB transaction/metadata/DDL trong migration là mục đích riêng.

## Migration và quan hệ

Nguồn [database/migrations/2026_10_17_000004_create_products_table.php, dòng13](../../database/migrations/2026_10_17_000004_create_products_table.php#L13) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/database/migrations/2026_10_17_000004_create_products_table.php#L13).

```php
            $table->bigInteger('id', true);
            $table->bigInteger('category_id')->index();
            $table->bigInteger('unit_id')->index();
            $table->string('code', 60)->collation('utf8mb4_0900_bin')->unique();
            $table->string('name', 200);
            $table->string('active_ingredient', 200)->nullable();
            $table->decimal('sale_price', 18, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->integer('expiry_warning_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreign('category_id')->references('id')->on('product_categories')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete()->restrictOnUpdate();
        });
```

Khai báo signed BIGINT, unique code, DECIMAL18,2 và haiFK RESTRICT. Đây là trích source migration đã áp G4A; G6 không chạy up/down.

## Seeder dữ liệu tổng hợp

Nguồn [database/seeders/DatabaseSeeder.php, dòng24](../../database/seeders/DatabaseSeeder.php#L24) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/database/seeders/DatabaseSeeder.php#L24).

```php
        DB::transaction(function () use ($adminPassword, $userPassword) {
            foreach ([User::class, ProductCategory::class, Unit::class, Product::class] as $model) {
                if ($model::query()->exists()) {
                    throw new RuntimeException('MNM: seeder chỉ nhận schema demo rỗng; không ghi đè dữ liệu hiện hữu.');
                }
            }
            (new User)->forceFill(['username' => 'admin_demo', 'password_hash' => Hash::make($adminPassword), 'role' => 'admin', 'is_active' => true])->save();
            foreach (['nhanvien_demo_1', 'nhanvien_demo_2'] as $username) {
                User::create(['username' => $username, 'password_hash' => Hash::make($userPassword), 'is_active' => true]);
            }
```

Seeder từ chối schema không rỗng và đòi mật khẩu demo riêng; không chạy lại trên demo đang có. Ảnh/log G5 không thay bằng chứng đã chạy Seeder G4A.

## Quan hệ Eloquent

Nguồn [app/Models/Product.php, dòng20](../../app/Models/Product.php#L20) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/app/Models/Product.php#L20).

```php
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
```

category/unit là belongsTo; ProductCategory/Unit có hasMany. Domain save còn kiểm active references, không chỉ ánh xạ khóa ngoại.

## Laravel Authentication

Nguồn [app/Http/Controllers/SessionController.php, dòng24](../../app/Http/Controllers/SessionController.php#L24) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/app/Http/Controllers/SessionController.php#L24).

```php
        if (! Auth::attempt([...$request->safe()->only(['username', 'password']), 'is_active' => true])) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['username' => 'Thông tin đăng nhập không hợp lệ hoặc tài khoản đã ngừng hoạt động.']);
        }
        RateLimiter::clear($key);
        $request->session()->regenerate(true);

        return $request->expectsJson() ? response()->json(['user' => $request->user()]) : redirect()->intended(route('products.index'));
    }
```

Auth dùng guard/session Laravel với user provider riêng; regenerate khi login. Logout invalidate/token đọc ở cùng file.

## Middleware phân quyền

Nguồn [app/Http/Middleware/RequireAdmin.php, dòng10](../../app/Http/Middleware/RequireAdmin.php#L10) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/app/Http/Middleware/RequireAdmin.php#L10).

```php
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->role === 'admin', 403, 'Chỉ Admin được thực hiện thao tác này.');

        return $next($request);
    }
}
```

EnsureActiveUser đọc fresh user mỗi request; alias active/admin đăng ký trong bootstrap/app.php và gắn ở routes/web.php.

## Validation biểu mẫu

Nguồn [app/Http/Requests/CatalogWriteRequest.php, dòng26](../../app/Http/Requests/CatalogWriteRequest.php#L26) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/app/Http/Requests/CatalogWriteRequest.php#L26).

```php
            $rules += ['category_id' => [$presence, 'required', 'string', DecimalId::rule()], 'unit_id' => [$presence, 'required', 'string', DecimalId::rule()], 'active_ingredient' => ['sometimes', 'nullable', 'string', 'max:200'], 'sale_price' => [$presence, 'required', 'numeric', 'min:0', 'decimal:0,2', DecimalLimit::rule(16, 2)], 'tax_rate' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100', 'decimal:0,2'], 'expiry_warning_days' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:3650']];
        }

        return $rules;
```

FormRequest kiểm field/DecimalId/DecimalLimit; domain bổ sung uniqueness/cycle/active. Bản dịch vi không đổi rule hoặc HTTP status.

## Tìm kiếm và phân trang

Nguồn [app/Http/Controllers/CatalogController.php, dòng28](../../app/Http/Controllers/CatalogController.php#L28) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/app/Http/Controllers/CatalogController.php#L28).

```php
        if (($filters['q'] ?? '') !== '') {
            $query->where(function ($query) use ($definition, $filters) {
                foreach ($definition['search'] as $column) {
                    $query->orWhereRaw('LOWER('.$column.') LIKE ?', ['%'.mb_strtolower($filters['q']).'%']);
                }
            });
        }
        foreach (['is_active', ...($catalog === 'products' ? ['category_id', 'unit_id'] : [])] as $key) {
            if (isset($filters[$key])) {
                $query->where($key, $filters[$key]);
            }
        }
        $records = $query->orderBy('name')->orderBy('id')->paginate($filters['per_page'] ?? 20)->withQueryString();
```

LOWER/LIKE dùng binding, giữ semantics wildcard%; sort name/id, paginate(per_page mặc định20) và withQueryString. Request giới hạn1..50.

## Dashboard đọc Eloquent

Nguồn [app/Http/Controllers/DashboardController.php, dòng15](../../app/Http/Controllers/DashboardController.php#L15) — [GitHub checkpoint](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo/blob/68770c0d2dc8b0e743a69564112be41eed54dd69/app/Http/Controllers/DashboardController.php#L15).

```php
        $statistics = [
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'categories' => ProductCategory::count(),
            'units' => Unit::count(),
        ];
        if ($request->user()->role === 'admin') {
            $statistics['users'] = User::count();
        }
        $categories = ProductCategory::withCount('products')->orderByDesc('products_count')->orderBy('name')->get();
        $products = Product::with(['category', 'unit'])->orderBy('name')->orderBy('id')->limit(5)->get();
```

Đếm dữ liệu thật và eager-load category/unit. Nhánh users chỉ Admin; test quan sát không có ghi business data.

## Blade, Bootstrap và bảo vệ form

[Layout](../../resources/views/layouts/app.blade.php) nạp CSS/JS nội bộ public/assets, render menu theo role; [form catalog](../../resources/views/catalog/form.blade.php) có @csrf/@method, old-input và error components. [app.js](../../public/assets/app.js) xử lý modal xác nhận bằng textContent và toggle mật khẩu. Hiding UI đi kèm middleware server; không dùng ẩn nút để thay authorization. [License/nguồn](../evidence/G5_BOOTSTRAP_ASSETS.json) ghi Bootstrap5.3.8/SRI/MIT; không cần Node build/CDN cho G5.

Kiểm chứng: G4AMysqlTest11test, G4BHttpTest15test, G5PresentationTest5test; tổng31test/674assertions chạy lại trước push. [Preflight](../evidence/G5_PUBLICATION_PREFLIGHT.json), [HTTP G5](../evidence/G5_TCP_HTTP.json) và [browser G5](../evidence/G5_BROWSER_QA.json) có phạm vi/giới hạn riêng. Không suy snippet được trích nghĩa là đã chạy lệnh migration/seeder trong G6.
