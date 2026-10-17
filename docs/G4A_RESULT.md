# G4A — Laravel/MySQL và nền dữ liệu

Ngày 17/10/2026. Trạng thái: **VERIFIED trong phạm vi G4A; chờ Thy nghiệm thu**. G1/G2/G3/ENV03/ENV04 giữ LOCKED theo phạm vi; chưa commit/push G4A, chưa mở G4B/G5.

## Nguồn và phạm vi

Source duy nhất: `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`. Checkpoint nền `21c8842027c510ec4db0d09b859ccd522f8785de`; thiết kế [G2_DECISIONS](G2_DECISIONS.md)/[ERD_MYSQL](ERD_MYSQL.md). Tài liệu G2 mô tả trạng thái thiết kế tại thời điểm G2; evidence G4A dưới đây chứng minh thực thi mới.

Chỉ database `mnm_vat_tu_nong_nghiep`. Không thay MySQL binary/my.ini/globalconfig, không restart/repair, không truy cập ghi database khác, không sửa KLCN/NoSQL/Mobile/Word/Rose.

## Kết quả đã kiểm

| Hạng mục | Evidence | Kết quả |
|---|---|---|
| SQL identity/provision | G4A_SQL_PREFLIGHT | Root nhập local;MySQL 8.4.3;database absent trước create;không rootpassword trên disk |
| Dependency/boot | G4A_MYSQL_QA |108 package theo composer.lock không đổi;Laravel 13.33.0/PHP 8.3.30;about exit0 |
| Migration/schema | G4A_MYSQL_QA |4 bảng nghiệp vụ+ledger;3 FK RESTRICT;7 CHECK;2 trigger;BIGINT signed;DECIMAL18,2;generatedAdmin unique |
| Eloquent/Seeder | G4A_MYSQL_QA |3 users(1 Admin/2 User),5 categories,3 units,4 products synthetic;quan hệ/casts/hash/UTC |
| MySQL QA | tests/Feature/G4AMysqlTest.php/G4A_MYSQL_QA |11 tests,131 assertions,exit0;không SQLite |
| Runtime privileges | SHOW GRANTS/G4A_MYSQL_QA | Chỉ SELECT/INSERT/UPDATE/DELETE trên schema riêng;CREATE bị từ chối1142 và không tạo bảng |
| Migrator privileges | SHOW GRANTS/G4A_MYSQL_QA | CRUD+CREATE/ALTER/DROP/INDEX/REFERENCES/TRIGGER trên schema riêng;không globalprivilege |
| Guard/seed overwrite | G4A_MYSQL_QA | Exactfixture được nhận;fixture bị sửa bị chặn;mutation rollback;seed lại bị từ chối trước ghi |
| HTTP | G4A_MYSQL_QA | HTTP503/no-store thật qua server local tạm;processHTTP đã dừng |
| Protected/source | G4A_PROTECTED_VERIFICATION |37 PHP lint;KLCN/NoSQL/repo cha HEAD/status/index/config khớp;11 artifact ENV04/ZIP/my.ini/mysqld giữhash |

Rollback đúng bốn migration đạt; readback chỉ còn ledger migrations rỗng và0trigger; remigrate/reseed rồi11 tests/131 assertions cuối đạt.

## Thay đổi kỹ thuật và giới hạn

Danh sách từng file/SHA: [G4A_SOURCE_DIFF](evidence/G4A_SOURCE_DIFF.json). Thêm4 migration,models/relations/CatalogIntegrity/Seeder,guard identity/database/fixture và bộ test. Hai helper DecimalId/DecimalLimit kế thừa giữ byte. Không đưa Next.js/Flutter/Mongo/worker vào runtime.

Laravel13 gộp cấu hình framework mặc định nên cần dontMergeFrameworkConfiguration để chỉ có kết nối MySQL; bổ sung exception binding/viewcompiled riêng và tên connection khi dựng MySqlConnection. Không sửa vendor/framework để vượt guard.

MySQL hiện có log_bin bật/trustfunctioncreators tắt: hai trigger phải do phiên quản trị hợp lệ tạo, DEFINER vẫn là migrator schema-only. Không cấp SUPER hoặc đổi cấu hình. Broker riêng chỉ phục vụ đúng hai CREATE/DROP trigger schema này; rootcredential chỉ RAM/anonymouspipe.

Giá giữ decimal string; Eloquent từ chối quá2chữ số lẻ, MySQL rawSQL có thể làm tròn1.239 thành1.24 và test ghi nhận đúng khác biệt. Trigger chỉ chặn tự trỏ trực tiếp; cycle nhiều cấp/active-parent kiểm trong transaction khóa cây. Chưa stress nhiều client đồng thời.

CRUD model đã kiểm; login/logout,phân quyền HTTP,FormRequest/controller,search/pagination HTTP,Blade/Bootstrap và browserQA chưa triển khai. Không tuyên bố hoàn tất rubric từ11 tests nền dữ liệu. HTTP503 giữ nguyên.

## Tài khoản và bảo mật

Runtime `mnm_runtime_20261017@localhost`;migration `mnm_migrate_20261017@localhost`. Password/demo mới chỉ trong `.env` và `.agent/private/g4a/credentials.json` bị Git ignore; không nằm trong ZIP. Demo username: admin_demo,nhanvien_demo_1,nhanvien_demo_2; đối chiếu Seeder là nguồn chuẩn. Không gửi mật khẩu qua chat.

Một probe config đã vô tình hiện mật khẩu runtime mới qua connection framework merge. Giá trị cũ đã bị thu hồi bằng đổi credential riêng account vừa tạo;private/.env đồng bộ;loglocal chứa giá trị cũ đã xóa. Không lộ hoặc đổi credential root/account có sẵn. Runner mới lọc credential;hồ sơ/ZIP được quét giá trị bí mật hiện hành. Không khẳng định xóa được lịch sử đầu ra công cụ, nên thu hồi giá trị cũ là biện pháp xử lý chính.

## Git và bàn giao

MNM main giữ SHA nền21c8842027c510ec4db0d09b859ccd522f8785de;index trống,chưa commit/push/contactremote. KLCN11dòng dirty có sẵn,NoSQL sạch;HEAD và Gitmetadata khớp baseline. Đây là kiểm HEAD/status/metadata,không phải fullhash mọi file protected hoặc nội dung database khác.

ZIP `MNM_NHOM6_G4A_VERIFIED_20261017.zip` gồm báo cáo/evidence/code G4A và helper an toàn để GPT Web kiểm với checkpoint GitHub. Không dependency/.env/private/rawlogs/dump. Đọc START_HERE.md trong ZIP;không giải nén ghi đè project đang dùng. SHA/CRC/exact-set/memberSHA ở receipt bàn giao ngoài ZIP.

Nguồn chi tiết: [QA](evidence/G4A_MYSQL_QA.json),[protected](evidence/G4A_PROTECTED_VERIFICATION.json),[source diff](evidence/G4A_SOURCE_DIFF.json),[checklist](../.agent/G4A_IMPLEMENTATION_CHECKLIST.md). Sau nghiệm thu G4A mới xét G4B/G5;không tự tiếp.
