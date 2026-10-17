G5 ACCEPTED/LOCKED theo decision G5_ACCEPTED_G6_DRAFT_AUTHORIZED_20261017. Đây là checklist checkpoint trước nghiệm thu; giới hạn G5 chưa push/G6 chưa mở bên dưới đã được quyền mới thay thế. Tiếp tục bằng G5_PUBLICATION_G6_CHECKLIST; source/evidence cũ giữ nguyên.

# G5 — checkpoint G4B rồi Blade/Bootstrap

ALLOWED: G4B publication whitelist; sau remote clean sửa Blade/public assets CSS/JS/Bootstrap/license và bản dịch giao diện lang/vi/validation.php theo locale vi hiện hữu; thêm DashboardController chỉ đọc và GET/dashboard bên trong auth+active; tests HTML/HTTP và docs/ZIP. Bản dịch giữ nguyên rule, error key và HTTP status.
FORBIDDEN: Source Controllers/Auth/Requests/Middleware/Models/domain G4A/G4B,32route hiện hữu/JSON contract,migrations/schema/config/dependencies; migrate/seed/reset demo/SQL ngoài database riêng; protected KLCN/NoSQL/Word/Rose; G5commit/push/G6.
SOURCE OF TRUTH: Thy attachment fb9ae323;G4B accepted ZIP/source23/26test425;G2/ERD;code/data thực tế; decision G4B_ACCEPTED_G5_AUTHORIZED.
INVARIANTS:4tables/3FK/7CHECK/2trigger/1Admin;demo3/5/3/4 rowhash/schema/ledger;role/active/CSRF/session/decimal/cycle/RESTRICT;32existingroutes unchanged;assets có nguồn/version/license,offline.
ACCEPTANCE: G4B SHA/tree/clean;G5 HTML/function tests+G4A/B regression/lint/assets;browser thực1366/768/320 screenshot/visual nếu khả dụng,không thì PENDING;fixture/protected giữ;source diff/logs/verification/ZIP CRC+SHA;dừng nghiệm thu.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| P01 | G4B acceptance/source/protected/regression/lint | ZIP/Git/MySQL | VERIFIED | g4b-acceptance-preflight;26/425;lint52 |23source khớp trước publication |
| P02 | Explicit stage/commit/push/readback/clean | đúng main | VERIFIED | 411aa5d716c1162a836dd35eda994dcefab1cd4c;G5_G4B_PUBLICATION.json | Clean trước G5 |
| C01 | Bootstrap offline/version/license | Official Bootstrap | VERIFIED | G5_BOOTSTRAP_ASSETS.json |5.3.8 SRI/MIT; không đổi dependency lock |
| C02 | Login/layout/sidebar/header | Blade G4B | VERIFIED | G5PresentationTest + Browser QA/ảnh login | Auth/CSRF giữ |
| C03 | Dashboard dữ liệu thật | Eloquent bốn bảng | VERIFIED | tests/dashboard/ảnh;32route giữ | GET chỉ đọc, User không thấy thống kê tài khoản |
| C04 | List/form/detail/filter/status/delete confirm | route G4B | VERIFIED |31/674;29TCP;browser tương tác | Browser hủy modal xóa; CRUD kiểm bằng rollback |
| C05 | HTML/regression/lint/assets/fixture QA | MySQL thật | VERIFIED | G5_HTTP_TEST_LOG/TCP/ROUTES/MYSQL/FIXTURE/PROTECTED | Không reset demo |
| C06 | Browser1366/768/320/ảnh/visual | UI thực | VERIFIED trong ma trận | G5_BROWSER_QA/G5_SCREENSHOTS;22JPEG | Không tuyên bố mọi trang × mọi kích thước |
| C07 | Protected/security/docs/diff | Baseline/source | VERIFIED trong receipt | G5_PROTECTED_VERIFICATION/G5_DOC_AUDIT/SOURCE_DIFF/PATCH | Không audit toàn DB protected |
| C08 | ZIP CRC/exact-set/hash | Payload whitelist | VERIFIED kiểm preview; final phải đọc lại PASS | .agent/logs/g5/package-final.json | Chỉ VERIFIED sau đóng gói/readback PASS |
| A01 | Thy nghiệm thu G5/publication | Quyền mới | LOCKED | decision G5_ACCEPTED_G6_DRAFT_AUTHORIZED | Publication/G6 DRAFT theo checklist mới |
