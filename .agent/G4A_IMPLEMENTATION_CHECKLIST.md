# G4A — triển khai sau AUTH02

ALLOWED: Source/config/guard Laravel trong dự án Mã nguồn mở; dependency theo lock; database mới mnm_vat_tu_nong_nghiep sau readback absent; hai account riêng schema cho migration và runtime; bốn bảng/ba FK/một Admin; model/domain integrity tối thiểu cho cycle/active/giá; seeder synthetic; tests MySQL thật; hồ sơ và ZIP.

FORBIDDEN: ENV04 đã khóa; KLCN/NoSQL/Flutter/Word/Rose; DB khác; reset credential; G4B HTTP Auth/CRUD/G5 Blade; commit/push. Không migrate:fresh hoặc DROP database. Rollback chỉ những bảng do G4A vừa tạo, khi readback exact-set chứng minh fixture mới của dự án.

SOURCE OF TRUTH: Thy/G4A_AUTHORIZED và AUTH02_LOCAL_LOGIN; G2_DECISIONS/ERD_MYSQL; checkpoint21c8842; SQLreadback AUTH02 thực tế. Root authenticated qua input local một lần, không lưu password. DB đích absent; root có CREATE/CREATE USER/GRANT.

INVARIANTS: MySQL8.4.3/127.0.0.1:3306/datadir đã xác thực; only MySQL/database whitelist/no fallback; storage/session/cache riêng; signedBIGINT,decimal18,2,utf8mb4,RESTRICT,generated admin_slot unique; self-parent trigger và cycle/active integrity theo source. Credentials chỉ config ignored, không log/Git/ZIP. HTTP nghiệp vụ vẫn đóng.

ACCEPTANCE: Composer lock giữhash, Laravel boot thật, identity/grants của hai account đúng schema, migration/rollback/migrate lại trên DB mới đã chứng minh phạm vi, seeder/model/constraints/domain/Unicode/Decimal được kiểm MySQL thật; protected Git và ENV04 artifact giữ; ZIP CRC/exact-set/memberSHA; dừng chờ nghiệm thu.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| I01 | AUTH02 identity/grants/target absent | SQL thật qua input Thy | VERIFIED | .agent/logs/auth02/sql-readback.private.json;4queries | root@localhost;8.4.3;target0 |
| I02 | DB và account độc lập | I01/G4A | VERIFIED | G4A_SQL_PREFLIGHT/provision/grants;0tables trước migration | MigratorDDL+CRUD;appCRUD schema-only |
| I03 | Composer lock và Laravel boot/guard | Lock/G3 | VERIFIED | composer-install-zip exit0/108packages/lockhash nguyên;boot-about-07 exit0 | Framework config merge tắt |
| I04 | Migration/models/domain integrity/seeder | G2/ERD/sourceG1 | VERIFIED phạm vi G4A | migrate-first-02/seed-first/schema-readback-first;4bảng/3FK/7CHECK/2trigger;11tests/131assertions | Không HTTP CRUD/Auth/UI |
| I05 | MySQL QA/rollback/schema/grants | I02–I04 | VERIFIED | rollback-owned/empty-after-rollback/migrate-final/seed-final/mysql-tests-final/fixture-final-verify;11tests131assertions | Rootbroker đóng;schema cuối3/5/3/4;không migrate:fresh |
| I06 | Protected/diff/hồ sơ/ZIP | Baseline/evidence | VERIFIED | G4A_PROTECTED_VERIFICATION/lint37;ZIP49members/48manifest/CRC/memberSHA/scancredential;handoff-verified.json | Chờ nghiệm thu,không commit/push |

| I06a | ZIP checkpoint PENDING | G4A_RESULT/evidence/source diff | VERIFIED phạm vi đóng gói | ZIP49members/48manifest;SHA a4dc0e45ab2ae0277922b9bc4c100f135b317b0cd416ac83e1d72ef9d0c6df2d;receipt logs/g4a-implementation/handoff-pending_rollback.json | Không chứng nhận rollback/G4A hoàn tất |
