# C3 — Nhà cung cấp và phiếu nhập hàng

ALLOWED: source C3, migration000008, tests/tools/views; tích hợp InventoryService/Product/User/UserController/routes/layout; metadata InventoryTest; hồ sơ C3/governance hiện hành.
FORBIDDEN: KLCN/NoSQL/Word/Rose/G7, accepted evidence/ZIP, dependencies/config, database nghiệp vụ; commit/push C3.
SOURCE OF TRUTH: quyền Thy72b741aa; C2 main2d5d323e5fa629508a83aa5f542081343a0f7902; code/schema test thực tế; decision C2_FUNCTIONAL_LOCKED_C3_AUTHORIZED_20261021.
INVARIANTS:2roles; Admin ghi nhập/User chỉ đọc; C1manual không mở quyền; kho chỉ InventoryService; purchase source_id từng dòng; integer cent/milli; confirmed bất biến; transaction toàn phiếu; token/version/replay; product locks tăng dần; test schema duy nhất mnm_inventory_test_20261021.
ACCEPTANCE: structural/schema/route; semantics/permission/line-source; PHPUnit+real concurrency+HTTP+DB readback/protected/hash/ZIP. Browser/in thật thiếu tool thì PENDING, không suy VERIFIED.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| A | Publish C2 whitelist | ZIP C2/C2-R | VERIFIED | logs/c3/c2-publication.json |51files;main2d5d323;visualPENDING |
| B | Baseline/isolation | guard/private profile | VERIFIED | logs/c3/before.json;business-before/after.json | Business chỉ đọc |
| C | Schema/model/service | decision C3 | VERIFIED | logs/c3/mysql-readback.log;regression.log | Supplier/Purchase/PurchaseLine |
| D | Form/route/Blade/print | layouts C1/C2 | VERIFIED chức năng;visual PENDING | logs/c3/http-demo.json;routes.log | Admin ghi/User xem |
| E | Test/transaction/concurrency/chain | MySQL test | VERIFIED | logs/c3/regression.log;purchases-races.log |75tests/1277;6cases/12workers |
| F | HTTP/DB/protected | source cuối | VERIFIED chức năng;visual PENDING | logs/c3/http-demo.json;mysql-readback.log;protected-after.json | Browser/print PENDING |
| G | Hồ sơ/diff/ZIP | source cuối và receipts | VERIFIED | logs/c3/package-final.json;docs-qa.json;patch-check.json |51members/50payload CRC/SHA/readback/secret PASS;không commit/push C3 |
