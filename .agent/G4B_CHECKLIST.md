# G4A publication → G4B HTTP

ALLOWED: Checkpoint G4A đã nghiệm thu tới main repo Mã nguồn mở; sau đó source HTTP Auth/authorization/CRUD/search/pagination và Blade tối thiểu trong dự án này; MySQL fixture tests có transaction/rollback, private session/cache/logs riêng; tài liệu/ZIP.

FORBIDDEN: Thay schema G4A, migrate/seed/DROP/TRUNCATE/reset demo; sửa hoặc ghi database/source KLCN/NoSQL/Flutter/Word/Rose; G5/Shop/POS/kho/công nợ; commit/push G4B trước nghiệm thu; force push hoặc remote khác.

SOURCE OF TRUTH: Thy attachment b97a87a4 ngày 17/10/2026; G4A accepted ZIP/source manifest; G2_DECISIONS/ERD_MYSQL; G4A_ACCEPTED_G4B_AUTHORIZED decision; code/MySQL thực tế.

INVARIANTS: Bốn bảng/ba FK/bảy CHECK/hai trigger/một Admin; demo3/5/3/4 giữ nội dung, signedBIGINT/decimal string; runtime chỉ schemaCRUD, không root trong app; server-side role/active/CSRF; no bearer/Mongo/PostgreSQL.

ACCEPTANCE: G4A push SHA/readback/tree sạch; HTTP focused tests guest/user/admin/inactive/login/logout/session/CSRF/CRUD/validation/search/pagination/restrict/race phù hợp; regression11tests131assertions trên MySQL thật; fixture cleanup/readback; routes/lint/security/protected verification; ZIP CRC/memberSHA; dừng nghiệm thu G4B.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| A01 | G4A acceptance/manifest/base/remote | ZIP/Thy/Git | IN_PROGRESS | Base và origin/main đều21c8842 | Không divergence |
| A02 | Stage review/secret/protected | Explicit whitelist | TODO | Chưa stage | Không add -A |
| A03 | Commit/push/readback/clean | Main đúng remote | TODO | Chưa commit | Trước G4B |
| B01 | HTTP contract/Auth/role/CSRF | G2/Thy/G4A | TODO | Chưa sửa source | Blade tối thiểu |
| B02 | CRUD/users/search/pagination | G4A models/rules | TODO | Chưa sửa source | Không schema |
| B03 | MySQL HTTP/regression/fixture QA | B01/B02 | TODO | Chưa chạy | Không reset demo |
| B04 | Security/protected/readback/diff/ZIP | Baseline/test evidence | TODO | Chưa làm | Chưa push G4B |
