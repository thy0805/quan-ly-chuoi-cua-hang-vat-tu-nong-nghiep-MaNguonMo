# C2 — Bán hàng và hóa đơn nội bộ

ALLOWED: Chốt Git riêng C1 được nghiệm thu; C2 migration/models/service/requests/controller/routes/Blade/tests/tools/docs; ghi test schema hiện hữu; evidence và ZIP diff nhỏ.
FORBIDDEN: Ghi/migrate database nghiệp vụ; C3/G7/Word/Rose/KLCN/NoSQL; ghi đè evidence/ZIP đã duyệt; commit/push C2; reset/stash/clean/checkout thay đổi chưa phân loại.
SOURCE OF TRUTH: Decision C1_LOCKED_C2_AUTHORIZED_20261021, source C1 hiện hành và schema test đọc lại; G1–G6 giữ lịch sử.
INVARIANTS: User chỉ phiếu của mình, Admin đọc toàn bộ; kho tay Admin; phiếu nhiều dòng xác nhận nguyên tử; source_id=sale_line.id; lượng milli-unit; tiền integer cent với làm tròn half-up từng dòng, tổng cộng các dòng; không thêm thuế/chiết khấu nghiệp vụ ngoài yêu cầu; nháp không trừ kho; confirmed bất biến qua API/domain; khóa ID sản phẩm tăng dần; không xuất vượt tồn/trừ hai lần.
ACCEPTANCE: C1 remote readback; tests thật MySQL/service/HTTP/permissions/precision/rollback/replay/concurrency; demo TCP và MySQL readback; browser thật hoặc PENDING; ZIP CRC/SHA; bảo toàn nguồn và DB nghiệp vụ.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| C2-01 | Commit/push C1 whitelist | Quyền Thy/manifest | VERIFIED | logs/c2/c1-publication.json;main08ffd61 | Giữ G7 dirty |
| C2-02 | Schema/tiền/service/role | Decision/source | VERIFIED | sales-first.log;57tests1041assertions; sales-races.log | Test DB; không float |
| C2-03 | HTTP/Blade render/trang in HTML | Layout/C1 | VERIFIED | http-demo.json:34request PASS;57tests | Không suy visual từ HTTP |
| C2-03V | Browser1366/768/320 và print dialog | Browser thật | APPLIED_UNVERIFIED | Tool không callable; chưa có ảnh C2 | PENDING nghiệm thu trực quan |
| C2-04 | Test/test concurrency/demo | MySQL test riêng | VERIFIED | 57/57;1041assertions;4races/8workers;HTTP34 | Readback và C1races PASS;seeder2lần |
| C2-05 | QA/ZIP/bảo toàn | Artifact cuối | VERIFIED | package-final.json:45member/44payload CRC/SHA/readback PASS;secret0;patch30file dry-run PASS |81.347bytes;C2 chưa commit/push |
