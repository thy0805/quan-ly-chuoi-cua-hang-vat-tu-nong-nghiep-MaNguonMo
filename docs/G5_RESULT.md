# G5 — Blade/Bootstrap và kiểm giao diện

Ngày17/10/2026. **VERIFIED kỹ thuật trong ma trận bên dưới; chờ Thy/GPT Web nghiệm thu, chưa LOCKED/commit/push G5.**

## Nền và phạm vi

G4B đã nghiệm thu và push main **411aa5d716c1162a836dd35eda994dcefab1cd4c** lên repository riêng [MaNguonMo](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo). Remote SHA/tree/clean kiểm trước G5;48file checkpoint thay đổi,200tracked trên remote. Source G5 gồm26file source/test/assets mới hoặc sửa trên nền đó; manifest và unified patch ở evidence/G5_SOURCE_DIFF.json và G5_SOURCE_PATCH.diff. GitHub chưa chứa G5.

G5 hoàn thiện Blade login/layout/menu/sidebar/header, dashboard chỉ đọc dữ liệu thật, danh sách/chi tiết/biểu mẫu, thông báo/validation tiếng Việt, trạng thái, bộ lọc, phân trang, xác nhận xóa. Bootstrap5.3.8 CSS/bundle chạy offline có MIT license và SRI nguồn chính thức khớp; xem G5_BOOTSTRAP_ASSETS.json. Bản dịch giữ rule/error key/status. Dashboard chỉ thêm GET có auth+active;32route cũ/JSON/Auth/role/Requests/Middleware/domain/schema/models/config/dependency lock giữ.

## Kiểm thực hiện

| Lớp kiểm | Cách kiểm | Kết quả / evidence |
|---|---|---|
| Structural | Lint54PHP, Node syntax app.js; diff33route | PASS;32route cũ nguyên middleware,1GET dashboard mới;G5_PROTECTED_VERIFICATION/G5_ROUTES |
| Semantic | Đối chiếu G2/ERD, backend khóa, asset nguồn/SRI, schema/grants/fixture | PASS trong phạm vi receipt;4bảng nghiệp vụ/3FK RESTRICT/7CHECK/2trigger/1Admin;G5_MYSQL_READBACK |
| Runtime test | MySQL thật, PHPUnit G4A+G4B+G5 |31test674assertions PASS;G5_HTTP_TEST_LOG.txt là JSON đã lọc, không log thô |
| HTTP thật | PHP loopback/cookie/login/logout/CSRF419/User403/assets |29requests PASS;G5_TCP_HTTP;server smoke dừng sau kiểm |
| Browser thật | In-app browser tại localhost,1366/768/320 |22JPEG;G5_BROWSER_QA/G5_SCREENSHOTS |
| Bảo toàn | Baseline private readback, protected Git/hash/archives | PASS;G5_FIXTURE_INTEGRITY/G5_PROTECTED_VERIFICATION |
| Tài liệu | Inventory Markdown sở hữu, semantic hiện hành/lịch sử, link readback | G5_DOC_AUDIT; lịch sử/evidence khóa không viết lại quá khứ |
| ZIP | Whitelist/CRC/exact-set/SHA từng payload/readback | Receipt .agent/logs/g5/package-final.json sau đóng gói; SHA ZIP giao ngoài archive |

Ma trận browser: login ba kích thước; dashboard Admin ba kích thước; list1366/mobile320; menu offcanvas768; create/invalid validation và old-input768; detail/edit/search/modal hủy320; category filter/units/users/pagination1366; User320 không có nút ghi, trực tiếp users/create bị server403; logout hai vai trò. Bảng cuộn trong vùng mobile, không tràn toàn trang ở mẫu đã kiểm. CRUD thành công/validation/ràng buộc và session/CSRF/quyền được kiểm bằng tests rollback; browser chỉ login/logout, gửi biểu mẫu sai bị từ chối và hủy xóa, không sửa demo. Không tuyên bố mọi view × mọi viewport, điện thoại vật lý, đa trình duyệt hoặc stress nhiều client đã kiểm.

Sau restart Codex, browser hoạt động; lần login đầu500 do MySQL đã dừng. Đã resume một instance8.4.3 bằng binary/config/datadir hiện hữu không sửa, kiểm lại đạt; không sửa ENV04/DLL/ICU/reset/initialize. Các test bị ngắt hoặc lỗi trước resume không tính là PASS. Preview loopback để Thy xem khi process còn chạy; session có thể hết hạn, không là dịch vụ production.

## Bảo toàn và giới hạn

Database riêng mnm_vat_tu_nong_nghiep:3users/5categories/3units/4products, nội dung/hash/schema/ledger giữ; AUTO_INCREMENT có thể tiến do transaction rollback. Không migrate/seed/reset demo/DROP/TRUNCATE, không truy cập ghi PostgreSQL/MongoDB. Runtime account chỉ CRUD schema riêng; quyền SELECT metadata trigger chỉ dùng readback bằng migrator, không nâng quyền.

Protected KLCN HEAD fc514c8a040d7d2804791ceea5a255427f5bf086 với11dirty cũ; NoSQL HEAD21288936d172b8485b425c1adc7ca21972da5e48 clean; repo cha HEAD4ee9b0d1ed52f18cf984b4511cc89df841a4721e với5343dòng dirty cũ. HEAD/status/Gitmetadata và hash/accepted archive nêu trong receipt không đổi; đây không là hash toàn bộ ignored/source hoặc audit dữ liệu database khác. MNM HEAD vẫn G4B, index rỗng, G5 dirty local có chủ đích.

Secret scan kiểm file source/hồ sơ public hiện hành bằng giá trị secret hiện tại đã biết, mẫu key/token, whitelist/private/dependency/dump/nestedGit/reparse/kích thước; kết quả PASS trong phạm vi này, không là bảo đảm phát hiện mọi loại bí mật. ZIP không .env/credential/private/helpers/rawlogs/vendor/node_modules/dump/accepted ZIP cũ. Chưa render UML/ERD, chưa có rubric gốc, upload/AJAX ghi dữ liệu/đặt hàng/Shop/POS/kho/công nợ/G6 chưa mở.

## Bàn giao và điểm dừng

Một ZIP `MNM_NHOM6_G5_VERIFIED_20261017.zip` gồm26file delta, tài liệu hiện hành/evidence/22ảnh, BAT_DAU_DOC.md và MANIFEST_SHA256.json. Đối chiếu trên nền commit G4B; không giải nén đè source KLCN/NoSQL hoặc database. Các evidence G4A/G4B/ENV04 giữ nguyên byte. GPT Web đọc G5_RESULT → HTTP_TEST_LOG/TCP → SOURCE_DIFF/PATCH → BROWSER_QA/SCREENSHOTS → MYSQL/FIXTURE/PROTECTED/DOC_AUDIT. Dừng chờ nghiệm thu G5; chưa commit/push hoặc tự mở G6.

Scaffold Vite/Tailwind cũ trong package.json và welcome.blade.php còn giữ nguyên; không route hiện hành render welcome, Blade G5 không gọi @vite và không cần cài/chạy npm. Đây là tệp kế thừa chưa dùng, không phải frontend đang phục vụ.
