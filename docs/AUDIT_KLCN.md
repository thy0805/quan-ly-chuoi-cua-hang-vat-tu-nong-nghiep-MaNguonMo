> Hồ sơ lịch sử G1 đã được Thy nghiệm thu về clone/audit ngày 17/10/2026. Số đếm/điểm dừng phản ánh G1; trạng thái hiện hành ở G2_DECISIONS.md, G3_ISOLATION_RESULT.md và README.md.

# Audit KLCN và kết quả clone

## Xác minh nguồn

Nguồn duy nhất: D:\Hoctap\KhoaLuanCuNhan\SourceCode\api-laravel, HEAD fc514c8a040d7d2804791ceea5a255427f5bf086 của repo SourceCode. NoSQL không dùng làm nguồn code. D: ánh xạ \\CUTE\USBSSD; đường dẫn resolved của Git/file cùng share này.

Đã đọc quy tắc root/API/Web, context, kế hoạch gốc các mục 1/3/7/12, DOC_INDEX/mistake và rule. Những checkpoint cũ được phân biệt với mã hiện tại. KLCN có 11 dòng dirty Mobile sẵn trước lượt này; không làm sạch, commit hoặc thay đổi chúng. NoSQL source sạch trước clone.

## Việc đã làm trước khi Thy chỉnh hướng

Có Git mới chưa commit/remote, 11 file trung lập copied, 5 file tham khảo .txt và phần scaffold/tài liệu tự tạo. Chưa chạy Composer, Artisan, tests, app, migration/seed hoặc DB. Lượt kiểm trước xác nhận HEAD/status/hash/index/config nguồn không đổi.

Sau yêu cầu clone thật: kiểm kê 60 file của khung trước, lưu nguyên byte vào .agent/archive/initial-scaffold-20261017/ rồi xử lý từng chỗ trùng. Khung trước không bị xóa mù. Template trang phase1 và hai helper scaffold cũ được gỡ khỏi vị trí hoạt động sau khi kiểm hash bản lưu. Tài liệu sáu bảng/chỉnh role/trạng thái cũ là bản nháp chưa duyệt.

## Source đã clone thật

102 file COPIED. Các nhóm: app 43; bootstrap 2; config 13; database 18; public 4; resources 3; routes 3; tests 8; root 8. Có User model, 17 lớp support nghiệp vụ, các controllers/middleware, SQL schema/patches, một migration token, hai seeders, tests và composer.json/lock thực tế. Không coi số file là số chức năng đã nghiệm thu.

## Phát hiện và ảnh hưởng chuyển đổi

| Phần | Nguồn cụ thể | Nhận định |
|---|---|---|
| Framework | composer.json/lock | PHP ^8.3, Laravel ^13.17; lock v13.33.0; Sanctum và MongoDB dependency còn giữ |
| ORM | app/Models/User.php, CatalogController/CatalogWriteController | Chỉ một model User; danh mục dùng DB::table; chưa đạt Eloquent cho hai bảng quan hệ |
| Auth | AuthController, User, bootstrap/app.php | Auth::attempt active, regenerate session/logout invalidate; password_hash và HasApiTokens |
| Permission | CatalogPermission, BranchAccess, UserManagementController | Quyền theo chuỗi/chi nhánh; ghi danh mục yêu cầu is_catalog_admin và chain_owner active |
| Catalog | CatalogWriteController | parent_id/cycle, active relation, unique/length, price/tax/expiry validation cần giữ khi port |
| CRUD gap | routes/api.php | Catalog có GET/POST/PATCH; chưa thấy route DELETE categories/products; không gọi bản clone đủ CRUD môn học |
| SQL | database/schema và patches | BIGINT IDENTITY, TIMESTAMPTZ, partial index, pg_constraint/DO; chưa có bộ migration MySQL nghiệp vụ |
| PG lock | DebtService, InventoryAlertScanner | pg_advisory_xact_lock không thể đổi driver rồi coi tương thích |
| Fallback SQL | InventoryAlertController | Nhánh non-pgsql dùng date và phép nối || của SQLite; không chứng minh MySQL |
| Raw query | UserManagementController | LOWER("user"."username") phải xử lý dialect có kiểm khi chuyển |
| Bán hàng | SaleService | draft→confirmed, lô/kho, thuế/discount/cost/debt/invoice/outbox và transaction; không tương đương pending checkout đơn giản |
| UI | Web Next.js riêng; /shop và cart import mock-data | Chỉ đối chiếu UI/ý tưởng, không copy Next.js vào ứng dụng mới; Shop checkout thật chưa có để chuyển nguyên trạng |
| Worker/Mongo | ProductContentStore, NotificationStore, OutboxService và config | Lớp tích hợp còn trong API copied; worker folder không copy; chưa kích hoạt |
| Setup | composer scripts, routes/console.php | Có migrate và command ghi klcn186_dev; tuyệt đối không chạy trước cô lập runtime |
| Test | phpunit.xml và tests copied | Có config SQLite memory/fixture tổng hợp; chưa chạy tests, không là bằng chứng MySQL |

## REUSE / REWRITE / OMIT

| Thành phần | Hướng xử lý sau duyệt | Trạng thái hiện tại |
|---|---|---|
| Laravel app/controllers/support/models/routes/tests/composer | REUSE làm nền, port từng module | COPIED nguyên byte |
| Query Builder→Eloquent | REWRITE kỹ thuật giữ hành vi | Chưa sửa |
| SQL PG→migrations MySQL | REWRITE dialect/DDL giữ relation/rule được chọn | Chưa chuyển, SQL gốc đã copy |
| JSON API→Blade/Bootstrap | REWRITE lớp UI/response, giữ validation/quyền | Chưa xây |
| Auth→Admin/User | ADAPT cần mapping phê duyệt riêng | Chưa đổi role/quyền |
| Shop checkout/lịch sử khách | Tính năng mới hoặc ghép module cần duyệt | Không coi POS cũ là Shop đã hoàn thiện |
| Mongo/worker, chuyển kho/công nợ/báo cáo nâng cao | Đề xuất OMIT khỏi bản chạy môn học nếu được duyệt | Code API vẫn copied làm đối chiếu; chưa cắt |
| Next.js/Flutter/worker directory | OMIT khỏi source ứng dụng mới | Không copy |
| .env/.git nguồn/vendor/node_modules/runtime/data/uploads | OMIT vì độc lập/an toàn | Manifest ghi rõ file/thư mục bỏ |

Không xác nhận database live hoặc runtime nào. Bằng chứng nguyên byte và snapshot chỉ xác nhận clone/source trong phạm vi đã kiểm.
