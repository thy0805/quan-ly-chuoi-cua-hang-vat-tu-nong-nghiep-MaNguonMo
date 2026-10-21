> Cập nhật visual21/10 sau lần bàn giao bên dưới: browser đã kết nối lại;15ảnh desktop/tablet/mobile1366/768/320 đã được chụp và xem trực tiếp. ZIP ảnh riêng MNM_NHOM6_C1_ANH_GIAO_DIEN_20261021.zip; [checklist mới](../.agent/C1_VISUAL_CHECKLIST_20261021.md). Các dòng visual PENDING/disabled bên dưới là trạng thái lúc bàn giao cũ; evidence test và ZIP cũ giữ nguyên. C1 vẫn chờ Thy nghiệm thu/LOCKED; không chạy lại test hay ghi giao dịch trong lượt chụp.

# Cụm 1 — Kết quả triển khai quản lý tồn kho

Ngày 21/10/2026. Source tại `D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN`. Baseline HEAD main `ad30d92dbc0550839c788e3d17a293dcc9218c45`; C1 là working tree local, chưa stage/commit/push, chưa nghiệm thu/LOCKED. Không mở Cụm 2/3 và không sửa Word/slide.

**Đã kiểm chức năng trên MySQL test; visual QA còn PENDING.** Database nghiệp vụ chưa migrate kho. [Bắt đầu/URL/quyền](c1/README.md), [schema và transaction](c1/THIET_KE.md), [chạy/demo Windows](c1/CHAY_VA_DEMO.md), [evidence](evidence/C1_VERIFICATION.json).

| Chức năng | Màn hình | Kết quả |
|---|---|---|
| Xem tồn, chưa giao dịch bằng0 | `/inventory` | Admin/User; search/filter/paging |
| Nhập thủ công | `/inventory/in` | Admin; số dương, token chống lặp |
| Xuất thủ công | `/inventory/out` | Admin; ngăn âm/vượt tồn |
| Lịch sử bất biến | `/inventory/history` | Admin/User; tìm/lọc/ngày/phân trang; không sửa/xóa |
| Quyền và dữ liệu | Middleware/FormRequest/service/MySQL | User gọi HTTP ghi403; rollback/locking/unique/FK/CHECK/trigger |

Hai migration mới ngày21/10 thêm product_stocks và inventory_movements. Model/quan hệ mới, InventoryService, StockQuantity, FormRequest, controller/middleware,6route và5Blade kho; điều hướng tích hợp layout cũ. Đã có39route thực tế kể cả các route hiện hữu. Giữ bốn migration và dependencies cũ.

Các tệp chính: `database/migrations/2026_10_21_000005_create_product_stocks_table.php`, `2026_10_21_000006_create_inventory_movements_table.php`; `app/Models/ProductStock.php`, `InventoryMovement.php`; `app/Services/InventoryService.php`; `app/Support/StockQuantity.php`, `InventoryTestProfile.php`, RuntimeIsolation; `app/Http/Controllers/InventoryController.php`, UserController; hai FormRequest kho và EnsureInventoryInstalled; models Product/User; config cache/session/view; routes/web.php; resources/views/inventory và layouts/app; lang/vi/validation.php; tests/Feature/InventoryTest.php, tests/Support/inventory_concurrency.php, tests/TestCase và3testhồiquy; tools/Start-InventoryDemo.ps1 và Test-Inventory.ps1. Manifest/diff trong ZIP liệt kê chính xác từng đường dẫn, phân biệt source C1 và docs đã có từ G7.

| Phép kiểm thực sự chạy | Kết quả |
|---|---|
| `tools/Test-Inventory.ps1` → PHPUnit | **44/44 tests,828 assertions**;31test cũ +13test kho |
| `tools/Test-Inventory.ps1 -ConcurrencyOnly` | **4/4 cases**,8PHPworkers thật có barrier giữ khóa; first stock/overdraw/replay/conflict |
| Atomicity | Inject lỗi sau ghi stock nhưng trước insert history; rollback tồn mới/tồn cũ và không có nửa lịch sử |
| Quantity/quyền/inactive/schema | Decimal chính xác, invalid/overflow/overdraw, activeAdmin mới đọc; User403; DELETE/UPDATE history bị chặn; FK/đổi đơn vị được bảo vệ |
| TCP HTTP demo có session/CSRF thật | C1-DEMO-20-5: nhập20,xuất5,còn15.000,đúng2movement; replay không ghi trùng, xuất16 trả422;4assets200 |
| MySQL readback cuối |6bảngnghiệpvụ/42cột/6FK/11CHECK/4trigger;9stock và14movement từ2lượt race+demo, toàn bộ chuỗi khớp tồn |
| Database nghiệp vụ | Hash dữ liệu/DDL/trigger/migration ledger trước-sau khớp; vẫn4bảngnghiệpvụ+migrations; kho503 chưa cài |
| Lint/PowerShell/diff/Markdown/ZIP | Xem số lượng và kết quả trực tiếp trong C1_VERIFICATION, final-checks và package-final |

Lần chạy full-regression-1 có43/44 đạt: helper HTTP test dùng CSRF cũ sau404, đã sửa helper và giữ log thất bại. Các lượt sau và tool cuối đạt; không tắt CSRF để làm test qua. Metadata trigger được đọc bằng account test DDL đủ quyền, không suy “không có trigger” từ account runtime thiếu quyền nhìn metadata. Test runtime chỉ CRUD schema test; không global privilege hoặc root runtime.

Demo hiện tại: [đăng nhập](http://127.0.0.1:8091/login) → [tồn vật tư demo](http://127.0.0.1:8091/inventory?product_id=21) → [hai dòng lịch sử](http://127.0.0.1:8091/inventory/history?product_id=21). Tool khởi động đã chạy, không migration/seed. Tài khoản test nằm cấu hình private tại máy, không có mật khẩu trong ZIP. Vật tư demo hiện đã còn15; dùng vật tư test mới nếu muốn diễn lại từ0.

Bảo toàn: 47 artifact G6/G5screenshots/G7/ZIP cũ so SHA trước/sau; KLCN HEAD fc514c8a040d7d2804791ceea5a255427f5bf086 và11dòng dirty có sẵn; NoSQL HEAD341a83a3bd6f579fad840e54d6c10f69b99362ec sạch. Đối chiếu HEAD và porcelain status theo snapshot đầu lượt, không gọi KLCN sạch hoặc khẳng định đã hash mọi file của hai repo. Git status Mã nguồn mở gồm source C1 và tài liệu G7/audit có trước; index không có file stage, HEAD không đổi.

Giới hạn còn lại: chưa có ảnh/kiểm trực quan màn hình kho1366/768/320 vì browser tool disabled; chưa triển khai vào database nghiệp vụ; chưa tự động provision cho máy khác; chưa stress/loadtest dài hạn; source reference tương lai chưa có module bán hàng/nhà cung cấp. Tồn là sổ từ giao dịch ghi nhận, chưa có nghiệp vụ kiểm kê thực địa. Đề xuất nghiệm thu chức năng C1 và hoàn tất visual QA trước khi khóa checkpoint. Không tự migrate/push hoặc làm Cụm2/3 trong lượt này.

ZIP bàn giao `MNM_NHOM6_C1_TON_KHO_20261021.zip`: mở BAT_DAU_DOC trước, có source overlay/diff, tài liệu và evidence đã lọc, MANIFEST_SHA256; không env/private/dump/vendor/node_modules/rawlogs. Đây là phần bổ sung trên baseline Git, không là bản cài độc lập hoặc bằng chứng C1 đã được push. SHA256/CRC/member readback được ghi trong receipt package-final tại máy.
