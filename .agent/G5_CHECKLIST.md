# G5 — checkpoint G4B rồi Blade/Bootstrap

ALLOWED: G4B publication whitelist; sau remote clean sửa Blade/public assets CSS/JS/Bootstrap/license; thêm DashboardController chỉ đọc và GET/dashboard bên trong auth+active; tests HTML/HTTP và docs/ZIP.
FORBIDDEN: Source Controllers/Auth/Requests/Middleware/Models/domain G4A/G4B,32route hiện hữu/JSON contract,migrations/schema/config/dependencies; migrate/seed/reset demo/SQL ngoài database riêng; protected KLCN/NoSQL/Word/Rose; G5commit/push/G6.
SOURCE OF TRUTH: Thy attachment fb9ae323;G4B accepted ZIP/source23/26test425;G2/ERD;code/data thực tế; decision G4B_ACCEPTED_G5_AUTHORIZED.
INVARIANTS:4tables/3FK/7CHECK/2trigger/1Admin;demo3/5/3/4 rowhash/schema/ledger;role/active/CSRF/session/decimal/cycle/RESTRICT;32existingroutes unchanged;assets có nguồn/version/license,offline.
ACCEPTANCE: G4B SHA/tree/clean;G5 HTML/function tests+G4A/B regression/lint/assets;browser thực1366/768/320 screenshot/visual nếu khả dụng,không thì PENDING;fixture/protected giữ;source diff/logs/verification/ZIP CRC+SHA;dừng nghiệm thu.

| ID | Hạng mục | Nguồn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| P01 | G4B acceptance/source/protected/regression/lint | ZIP/Git/MySQL | VERIFIED | g5/g4b-acceptance-preflight;g5-prepublication-regression26/425;lint52 | source chưa drift |
| P02 | Explicit stage/commit/push/readback/clean | đúng main | IN_PROGRESS | G4A base61332e5 | Chưa sửa source G5 |
| C01 | Bootstrap offline/version/license | Official Bootstrap | TODO | Chưa tải | Không npm/composer change |
| C02 | Login/layout/sidebar/header | Blade G4B | TODO | Chưa sửa | Giữ Auth/CSRF |
| C03 | Dashboard thống kê thật | Eloquent existing data | TODO | Chưa làm | GET chỉ đọc |
| C04 | Lists/forms/details/filter/status/delete confirm |32route G4B | TODO | Chưa sửa | Không đổi nghiệp vụ |
| C05 | HTML/regression/lint/assets/fixture QA | MySQL thực | TODO | Chưa chạy G5 | Không reset demo |
| C06 | Browser1366/768/320/ảnh/visual | UI thực | TODO | Tool disabled dù setting bật | Không tự claim VERIFIED |
| C07 | Protected/security/docs/diff/ZIP | Baseline/evidence | TODO | Chưa làm | Không push G5 |
