# C2-R — Kiểm giới hạn tiền, QA và bàn giao

ALLOWED: SaleMoney.php, SaleService.php, SaleTest.php; hồ sơ/governance/evidence liên quan; ghi fixture test mnm_inventory_test_20261021.

FORBIDDEN: Semantics kho C1, schema/migration/seed mới, database nghiệp vụ, C3/G7/Word/Rose/KLCN/NoSQL; commit/push, reset/stash/clean; ghi đè ZIP/evidence C2 đã giao.

SOURCE OF TRUTH: Quyền Thy attachment20c4ba69-940d-4258-9b47-67597dd5235d; C1 main08ffd61; C2 ZIP036cdd72…b538391 và source đối chiếu hiện hành.

INVARIANTS: DECIMAL(18,2), milli-unit DECIMAL(15,3), half-up từng dòng; source_id theo sale_line; transaction toàn phiếu; quyền owner/Admin/User giữ nguyên; draft không trừ tồn; không float.

ACCEPTANCE: Ca biên trước/sau có kết quả thật, ValidationException/HTTP422, không ghi dở; hồi quy và concurrency C2 sau source cuối; MySQL/business/protected readback; browser thật hoặc PENDING có nguyên nhân; ZIP CRC/SHA/secret/diff.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| R01 | Snapshot và ca biên trước sửa | Source C2/ZIP | VERIFIED | before-boundaries.log:3/3,42assertions;source30 khớp ZIP | Chưa tái hiện500;source cũ đã422 |
| R02 | Checked add cho draft/confirm | SaleMoney/SaleService | VERIFIED | after-boundaries.log:4/4,52assertions;integer-proof.log;lint3/3 | Chỉ3file source/test thay đổi |
| R03 | Hồi quy, HTTP và concurrency | MySQL test | VERIFIED | regression.log:61/61,1095assertions;sales-races.log:4/8worker;HTTP15 | Snapshot4bảng HTTP không đổi |
| R04 | Browser1366/768/320, print | Browser thật | APPLIED_UNVERIFIED | Không có tool browser callable | PENDING, không bypass |
| R05 | Readback/bảo toàn/hồ sơ/ZIP | Artifact cuối | VERIFIED | package-final.json:16member/15payload CRC/SHA/readback/diff PASS;87MD0lỗi |24.438bytes;secret0;C2-R DRAFT,không push |
