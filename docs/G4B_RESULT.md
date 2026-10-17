# G4B — Authentication, Authorization và CRUD qua HTTP

Ngày 17/10/2026. Trạng thái **VERIFIED kỹ thuật, chờ Thy/GPT Web nghiệm thu**. Source mới chưa commit/push. Không mở G5/G6.

## 1. Điểm vào và checkpoint

- Source: `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`.
- GitHub: [repository Mã nguồn mở](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo), branch `main`.
- G4A đã nghiệm thu và push: **61332e5e7236a7b28dd71197b3f3848b14aaff74**. Remote SHA/tree bằng local; working tree sạch trước G4B.166 file tracked:80 source/config/infrastructure,4 file tests,82 tài liệu/governance/evidence. Commit G4A gồm77 file đã stage theo whitelist,23 source theo manifest; không force push.
- G4B:23 file source/test mới hoặc sửa, gồm ba entry/bootstrap/routes, ba controllers, hai middleware, bốn FormRequests, một service, một resource registry, tám Blade views và một HTTP test file. Danh sách/SHA ở [G4B_SOURCE_DIFF](evidence/G4B_SOURCE_DIFF.json); patch ở [G4B_SOURCE_PATCH](evidence/G4B_SOURCE_PATCH.diff).
- ZIP `MNM_NHOM6_G4B_VERIFIED_20261017.zip` là phần bổ sung trên nền commit G4A, không phải toàn bộ dependency/source Laravel. Đọc `BAT_DAU_DOC.md` trong ZIP. SHA-256 của archive giao ngoài archive; manifest kiểm từng payload. Không gửi .env/private/vendor/node_modules/rawlogs/dump.

## 2. Chức năng và contract

| Hạng mục | Đã triển khai / kiểm trực tiếp |
|---|---|
| Login/logout | Username/password_hash, is_active; Laravel session/CSRF; regenerate login, invalidate/token mới logout; cookie phiên cũ không đăng nhập lại được |
| Throttling | Năm lần sai trong 60 giây theo username/IP; lần tiếp theo 429, không xác thực |
| Active session | Mỗi request đọc lại user; tài khoản bị ngừng bị403, phiên bị hủy; request tiếp theo401 |
| Admin/User | Admin quản lý users/categories/units/products. User đọc/tìm/phân trang; create/edit/POST/PUT/PATCH/DELETE và users bị403 trên server |
| Singleton Admin | Không thêm roleAdmin qua form, không tự đổi role; không ngừng/hạ quyền/xóa Admin hiện hữu; không lộ password_hash |
| Category/unit/product CRUD | Eloquent + FormRequest; validation theo model/rule G4A; transaction và DELETE vật lý chặn tham chiếu kể cả inactive |
| Cây/active | Chặn tự trỏ/vòng nhiều cấp và cha không hợp lệ; ngừng cha không tự ngừng/xóa con; giữ parity inactive-reference khi đổi tên hoặc chọn lại |
| Giá/ID/Unicode | Signed BIGINT dạng chuỗi, DECIMAL18,2 giữ chuỗi; giới hạn thuế/hạn cảnh báo/scale; Unicode và escaping Blade |
| Search/pagination | LOWER/LIKE có binding, giữ wildcard%/_; q≤160, active_only/is_active/category_id/unit_id; per_page1..50; sort name/id hoặc username/id ổn định |
| Web/JSON | HTML guest302→login, validation302+errors, mutation302+status; JSON guest401, thiếu quyền403, ID không hợp lệ/không có404, validation422, create201/delete204; CSRF sai/thiếu419 |
| Blade tối thiểu | Login, list/detail/create/edit và logout; navigation/nút theo role, server kiểm độc lập. Chưa Bootstrap/UI hoàn chỉnh |

Mật khẩu nhân viên mới/đổi giữ min 10/max 255 ký tự theo controller nguồn; trước bcrypt kiểm thêm giới hạn 72 byte, kể cả Unicode, để trả422 thay lỗi hash500. Không đổi mật khẩu demo hoặc seed lại. Password_confirmation chỉ dùng validation; role/password_hash không mass-assign từ request.

Category resources/search columns được cố định trong CatalogResources, không lấy table/column từ client. Các truy vấn HTTP dùng Eloquent/query binding, không gọi module PostgreSQL/MongoDB/worker. Runtime guard vẫn kiểm database/account/datadir trước kết nối; cookie/session/cache/log riêng storage/mnm.

## 3. Kết quả kiểm thử

| Lớp kiểm | Cách kiểm / kết quả |
|---|---|
| Structural | PHP lint52 file đạt;32 route có Web/CSRF;30 route ngoài login có auth+active, mọi thao tác quản lý có admin;23 source/SHA và8 Blade views |
| Semantic | Đối chiếu G2/ERD và archive G1; giữ per_page1..50/active_only/min 10, wildcard và parity domain. Models/migrations/config/dependency không đổi |
| PHPUnit/MySQL | **26 test / 425 assertions đạt**, gồm11 test G4A và15 test G4B. Lần focused đầu14 test / 268 assertions; các ca bổ sung được kiểm trong suite cuối |
| TCP HTTP thật |24 request qua PHP public/index.php và cookie jar: Admin/User login/logout, guest/role/CSRF, đọc/form HTML và cookie replay. HttpOnly/SameSite=Lax đạt; server tạm đã dừng |
| Dữ liệu | Sau suite cuối và TCP: demo3 users/5 categories/3 units/4 products; nội dung/rowhash, schema và migration ledger khớp baseline G4A |
| MySQL readback |8.4.3, đúng database;3 FK RESTRICT,7 CHECK,2 trigger giữ nguyên định nghĩa,1 Admin active; runtime chỉ SELECT/INSERT/UPDATE/DELETE schema riêng |
| Isolation/security | Protected Git HEAD/status/metadata khớp baseline; accepted archives/11 artifact ENV04/binary/my.ini giữ hash; scan source/tài liệu theo whitelist và secret hiện hành/mẫu keytoken |

Lệnh suite cuối trên máy đã provision: `python -X utf8 .agent/scripts/g4b_run.py regression-parity-final vendor/phpunit/phpunit/phpunit --configuration phpunit.xml`. Runner local ignored nạp credential private và lọc output trước ghi; không đưa credential hoặc runner private vào ZIP. Bằng chứng: [HTTP QA](evidence/G4B_HTTP_QA.json), [test log đã lọc](evidence/G4B_HTTP_TEST_LOG.txt), [routes](evidence/G4B_ROUTES.json), [TCP](evidence/G4B_TCP_HTTP.json), [MySQL](evidence/G4B_MYSQL_READBACK.json), [fixture](evidence/G4B_FIXTURE_INTEGRITY.json), [protected/security](evidence/G4B_PROTECTED_VERIFICATION.json), [publication](evidence/G4B_G4A_PUBLICATION.json).

Tests ghi dữ liệu synthetic QA-G4B trong transaction và rollback; G4A regression cũng rollback. Không migrate, seed, DROP/TRUNCATE hoặc reset demo. InnoDB AUTO_INCREMENT có thể tiến sau insert đã rollback; không tuyên bố counter giữ nguyên. Metadata trigger đọc bằng account migrator hiện hữu, chỉ SELECT; không dùng root hoặc thêm quyền/account.

Ca FK race chèn tham chiếu xác định sau precheck, trước DELETE trong cùng connection, kiểm MySQL thật trảFK1451 và rollback. Đây là kiểm cửa sổ interleaving; chưa stress nhiều client hoặc chứng minh mọi lịch đồng thời. MySQL SQL trực tiếp có thể làm tròn decimal quá scale; Eloquent/HTTP từ chối trước ghi theo G4A, không suy database luôn từ chối.

Hai lần công cụ TCP đầu dừng ở bước sao chép CookieJar và so tên thuộc tính cookie phân biệt hoa/thường; đã sửa helper, lần cuối24 request đạt. Probe route đầu chưa resolve HTTP Kernel nên thiếu middleware trong phép đọc; đã resolve lại và kiểm32 route. Các lỗi helper không được báo thành lỗi MySQL/app; raw logs chỉ local. Không suy thành công chỉ từ exit0.

## 4. Bảo toàn và Git sau G4B

| Repository | HEAD | Trạng thái so baseline |
|---|---|---|
| KLCN SourceCode | fc514c8a040d7d2804791ceea5a255427f5bf086 |11 dòng dirty Mobile có sẵn, không đổi |
| NoSQL SourceCode-Mongo |21288936d172b8485b425c1adc7ca21972da5e48 | Sạch, không đổi |
| Repo cha |4ee9b0d1ed52f18cf984b4511cc89df841a4721e |5343 dòng dirty có sẵn, đã loại subtree Mã nguồn mở; không đổi |
| Mã nguồn mở |61332e5e7236a7b28dd71197b3f3848b14aaff74 | G4B modified/untracked, không stage/commit/push; remote main vẫn G4A |

Giới hạn bảo toàn lượt này: HEAD/status và hash Git HEAD/index/config/packed-refs; không hash toàn bộ protected source/database. Không thực hiện kết nối PostgreSQL/MongoDB hoặc tác vụ ghi source protected. Secret scan kiểm giá trị hiện hành đã biết và mẫu key/token, kèm rà whitelist; không phải chứng minh không thể có secret dưới mọi dạng.

## 5. Phần chưa làm và điểm dừng

Chưa kiểm giao diện bằng browser visual QA, responsive/Bootstrap hoàn chỉnh, render UML/ERD, báo cáo cuối/Word/demo nhóm; chưa production deployment/load/performance. Không Shop/POS/checkout/lịch sử đơn/kho/lô/công nợ. Không thay schema hoặc nghiệp vụ G4A.

**Dừng chờ Thy/GPT Web nghiệm thu G4B.** Sau nghiệm thu mới nhận quyền checkpoint Git/G5 tương ứng; không tự stage/commit/push hoặc mở giai đoạn mới. G1/G2/G3/ENV04/G4A đã nghiệm thu theo phạm vi, G4B mới VERIFIED kỹ thuật.
