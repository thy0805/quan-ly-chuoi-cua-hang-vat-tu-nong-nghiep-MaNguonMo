> Hồ sơ lịch sử G1 đã được Thy nghiệm thu về clone/audit ngày 17/10/2026. Số đếm/điểm dừng phản ánh G1; trạng thái hiện hành ở G2_DECISIONS.md, G3_ISOLATION_RESULT.md và README.md.

# Danh sách file và nguồn gốc

COPIED: 102 file nguyên byte từ SourceCode/api-laravel. Bảng dưới liệt kê từng file; SHA-256 đầy đủ/source path nằm trong `.agent/logs/clone/clone-manifest.json` ở root dự án.

| File COPIED | SHA-256 (12 ký tự đầu) | Bytes |
|---|---|---|
| .editorconfig | fe39e0ad06ed | 275 |
| .gitattributes | a40eb27ee0b5 | 186 |
| artisan | f91a517c0c5d | 425 |
| composer.json | b05c3db66a62 | 2745 |
| composer.lock | a420d963c57e | 317788 |
| package.json | 023d511d768e | 461 |
| phpunit.xml | 493369cfb7fe | 1322 |
| vite.config.js | f413e14379e2 | 632 |
| app/Http/Controllers/AuthController.php | 589d4892890b | 1109 |
| app/Http/Controllers/CatalogController.php | 587029567e97 | 3164 |
| app/Http/Controllers/CatalogWriteController.php | 526e055e5bef | 9436 |
| app/Http/Controllers/Controller.php | e8ae1d379d50 | 70 |
| app/Http/Controllers/CustomerController.php | 412a86c6f134 | 4082 |
| app/Http/Controllers/DebtController.php | 23e0674d6ab5 | 4938 |
| app/Http/Controllers/InventoryAlertController.php | 81bb6b5c95b3 | 10776 |
| app/Http/Controllers/InventoryController.php | 82aa6b68964b | 7916 |
| app/Http/Controllers/MobileAuthController.php | 41eda8117d11 | 2446 |
| app/Http/Controllers/NotificationController.php | 4313eae4d9be | 1324 |
| app/Http/Controllers/OrganizationController.php | cb0922ebb843 | 8431 |
| app/Http/Controllers/OutboxController.php | 068d988b7d3d | 3341 |
| app/Http/Controllers/ProductContentController.php | 1d4179eaa507 | 1984 |
| app/Http/Controllers/ProfileController.php | 34d4a74c57b4 | 1913 |
| app/Http/Controllers/PurchaseReceiptController.php | 02d7e6e49a09 | 14118 |
| app/Http/Controllers/SalesOrderController.php | 21a08c9d5fff | 11175 |
| app/Http/Controllers/SalesReportController.php | fdc3b75992d6 | 9200 |
| app/Http/Controllers/StockTransferController.php | c45887b69582 | 16745 |
| app/Http/Controllers/SupplierController.php | b9445f10ba39 | 3701 |
| app/Http/Controllers/UserManagementController.php | 42cbcd933ce0 | 12780 |
| app/Http/Middleware/EnsureActiveUser.php | 55cf774c1c97 | 327 |
| app/Http/Middleware/SerializeBigIntIds.php | 422decf61d25 | 1193 |
| app/Http/Middleware/ValidateBearerToken.php | 417968af3b82 | 676 |
| app/Http/Middleware/ValidateBigIntRouteIds.php | 1abdfec1d72e | 657 |
| app/Models/User.php | 7430e608b88c | 552 |
| app/Providers/AppServiceProvider.php | caf306ef6a25 | 361 |
| app/Support/BranchAccess.php | ab0a8726b339 | 1438 |
| app/Support/CatalogPermission.php | 193d9738ca0f | 685 |
| app/Support/DebtAccess.php | 6baac51fad40 | 1578 |
| app/Support/DebtService.php | fcfd098aab39 | 7292 |
| app/Support/DecimalId.php | 1e1375aba336 | 917 |
| app/Support/DecimalLimit.php | 6db501a3d5e4 | 1235 |
| app/Support/InventoryAlertScanner.php | 063052905a54 | 7596 |
| app/Support/NotificationStore.php | 8940a1406990 | 2203 |
| app/Support/OutboxService.php | 97eb83d2d5b4 | 674 |
| app/Support/PartnerAccess.php | 3a1d88a3530f | 754 |
| app/Support/ProductContentStore.php | 0d3f5ffd3113 | 1478 |
| app/Support/PurchaseReceiptService.php | 78a0d0973a9b | 11361 |
| app/Support/ReceiptAccess.php | 278ab537248c | 1517 |
| app/Support/SaleService.php | 29545ee21747 | 12058 |
| app/Support/SalesAccess.php | 35ed7a8360aa | 1133 |
| app/Support/StockTransferService.php | 0f209ad637e5 | 14866 |
| app/Support/TransferAccess.php | 331093858825 | 2145 |
| bootstrap/app.php | 093afe03ee4d | 1134 |
| bootstrap/providers.php | f720e185207a | 89 |
| config/app.php | b4b25e15efa7 | 4281 |
| config/auth.php | c7e204e9785c | 4040 |
| config/cache.php | ee4ad2bba1ed | 4369 |
| config/cors.php | 08c1332e24cd | 391 |
| config/database.php | 64add819e232 | 6909 |
| config/filesystems.php | ba7060d7a23e | 2532 |
| config/logging.php | beb1ffd0db91 | 4606 |
| config/mail.php | c43ff49a31c5 | 3631 |
| config/mobile_auth.php | a735b2b05563 | 99 |
| config/mongodb.php | f474f0b8c657 | 139 |
| config/queue.php | 6101774da7c8 | 4199 |
| config/services.php | b7ee786ea1ad | 1039 |
| config/session.php | 64272cfbef6f | 8423 |
| database/.gitignore | e758642d95e8 | 10 |
| database/migrations/2026_10_12_000001_create_personal_access_tokens_table.php | fa99aa7b8088 | 779 |
| database/patches/20260927_catalog_active.sql | 89629d0e5fa2 | 475 |
| database/patches/20260927_debt_sources.sql | 343a5378b304 | 2119 |
| database/patches/20260927_inventory_alert_incidents.sql | 5b6fdf5b6a5c | 1236 |
| database/patches/20260927_outbox_delivery.sql | 1c56f1c2e8fd | 1053 |
| database/patches/20260927_product_expiry_warning.sql | 31145d4ac671 | 245 |
| database/patches/20260927_product_tax.sql | 118fac2f7cc1 | 554 |
| database/patches/20260927_receipts_cost.sql | 262403344496 | 2014 |
| database/patches/20260927_sale_idempotency.sql | 6d8cf5b1ddd7 | 154 |
| database/patches/20260927_sales_season.sql | 4325946131d3 | 81 |
| database/patches/20260927_stock_transfer_flow.sql | 588204dc1a39 | 2148 |
| database/patches/20260927_transfer_reconciliation.sql | 294182d1636e | 1860 |
| database/patches/20260927_warehouse_product_settings.sql | e97299fd113b | 581 |
| database/patches/20261003_purchase_supplier_invoice.sql | 782306f68a2e | 810 |
| database/schema/klcn186.sql | 3172800a0cc4 | 15842 |
| database/seeders/DatabaseSeeder.php | 985f63ef68aa | 492 |
| database/seeders/DemoDataSeeder.php | 3db2a1c34a4f | 3055 |
| public/.htaccess | b7e379c77639 | 740 |
| public/favicon.ico | e3b0c44298fc | 0 |
| public/index.php | eba77cba3969 | 543 |
| public/robots.txt | e5c4b84484ee | 24 |
| resources/css/app.css | 02db84e827e0 | 339 |
| resources/js/app.js | 101ead936a22 | 3 |
| resources/views/welcome.blade.php | cad37219edcf | 72230 |
| routes/api.php | b1c5db182e81 | 7489 |
| routes/console.php | 1e062d547b10 | 2799 |
| routes/web.php | 55dd3fd92b95 | 325 |
| tests/TestCase.php | c7c209f5579c | 142 |
| tests/Feature/CatalogAccessTest.php | 59a29daa185d | 10507 |
| tests/Feature/ExampleTest.php | aaa4a192fb4a | 359 |
| tests/Feature/InventoryAccessTest.php | 8439e202c005 | 93946 |
| tests/Feature/MobileAuthTest.php | a4ec670ee4c1 | 14334 |
| tests/Feature/MobileSalesFixture.php | c337b3483338 | 9250 |
| tests/Feature/MobileSalesIntegrationTest.php | 814d6ce73a9e | 15470 |
| tests/Unit/ExampleTest.php | ffacae5c1a2a | 243 |

## ADAPTED và NEW

ADAPTED gồm đúng `.env.example` và `.gitignore`, viết riêng từ phần scaffold đã kiểm; không mang template chứa kết nối của KLCN sang. Không có PHP/SQL/service/route/model/test/composer nào được sửa nội dung.

NEW gồm tài liệu, helper quản lý/check và file giữ chỗ, liệt kê đầy đủ và đếm chính xác trong `.agent/logs/clone/file-provenance.json`. Không tính archive/log evidence và .git vào payload count. Các file này không phải triển khai nghiệp vụ mới.

## OMITTED

15 mục file/thư mục bị loại có lý do trong clone-manifest.json. Thư mục vendor/storage/.agent được loại tại cấp thư mục nên số mục không phải số file con. Không copy .env, .env.example nguồn, SQLite database, bootstrap cache, vendor, runtime, runner hoặc các hướng dẫn source. Git nguồn nằm ở SourceCode/.git và không được copy; Git đích được init mới.

Bản lưu 60 file trước điều chỉnh ở `.agent/archive/initial-scaffold-20261017/`, inventory/hashes tại `.agent/logs/clone/preexisting-target-inventory.json`. Năm file .txt tham khảo trước đây vẫn giữ ở archive/klcn-reference; chúng không được tính trong 102 COPIED hiện hành.
