# Cụm 1 — Quản lý tồn kho — 21/10/2026

Nguồn quyền: prompt tổng thể21/10 và attachment5b460635-f4dc-4aee-b351-1516dbe59ef0. Cụm1 được giao triển khai trọn gói; Cụm2/3 chỉ kế hoạch.

ALLOWED: Migration mới, model/relationship, service kho, FormRequest/controller/routes/Blade/navigation; guard và công cụ test cô lập; tests và tài liệu bàn giao. Khởi động instance MySQL hiện hữu theo quyền đã có; chỉ provision/migrate/seed/ghi trên database test riêng mnm_inventory_test_20261021. Database nghiệp vụ chỉ đọc.

FORBIDDEN: migrate/seed/reset hoặc ghi database nghiệp vụ; KLCN/NoSQL/Word/slide; ghi đè G1–G6/G7 ZIP và evidence; thay dependencies; commit/push; triển khai bán hàng/nhà cung cấp.

SOURCE OF TRUTH: HEAD ad30d92dbc0550839c788e3d17a293dcc9218c45 và source hiện hành; yêu cầu Thy mới nhất ưu tiên hơn giới hạn không-kho của checkpoint cũ. Working tree có tài liệu G7/audit từ trước, phải giữ.

INVARIANTS: signed BIGINT ID; một stock/product; không ghi stock khi xem; decimal15,3 tính bằng milli-unit integer; stock không âm; movement bất biến; FK RESTRICT giữ lịch sử; Admin ghi/User đọc; token unique và so payload chống gửi lặp; transaction/khóa product thống nhất cho cơ chế kho dùng lại sau này.

ACCEPTANCE: test MySQL thật trên schema test riêng gồm atomicity, duplicate/conflicting replay, concurrency first-stock/overdraw, permissions HTTP và service, validation, immutable history/delete restrict; hồi quy cũ; đọc/schema/hash database nghiệp vụ không đổi; Blade/runtime/demo20-5=15; ZIP và diff/readback. Thiếu evidence phải ghi pending.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| C1-01 | Baseline source/Git/DB và MySQL/test isolation | Git/runtime | VERIFIED | business-before/after.json; MySQL startup; profile tests | DB nghiệp vụ hash không đổi |
| C1-02 | Migration, quantity, model/service | Prompt/source | VERIFIED | final-ledger-schema.log; InventoryTest | 6bảng/42cột/6FK/11CHECK/4trigger test; sổ khớp |
| C1-03 | HTTP, quyền, Blade responsive | Layout/G5/prompt | VERIFIED chức năng và visual các màn hình; chưa LOCKED | 44tests/828assertions trước lượt chụp; http-demo.json; C1_VISUAL_CHECKLIST_20261021.md |15ảnh mới1366/768/320; chưa kiểm mọi thao tác cảm ứng |
| C1-04 | Test nghiệp vụ/concurrency/hồi quy | Test MySQL riêng | VERIFIED | windows-test-tool-final.log; windows-concurrency-tool.log | 44/44;828;4cases/8workers |
| C1-05 | Demo/QA/bảo toàn/bàn giao ZIP | Artifact cuối | VERIFIED kỹ thuật theo final-checks và package-final; chưa LOCKED | final-checks.json; package-final.json khi PASS | Demo20-5=15/2movement; chưa nghiệm thu |

Quyết định: user chỉ xem tồn/lịch sử; Admin nhập/xuất vật tư active. Sổ lưu tham chiếu nguồn cho Cụm2/3 nhưng chưa triển khai hai module đó. Hai cột quantity riêng khỏi products. Mọi token replay phải khớp actor/product/type/quantity/note/reference. Lịch sử giữ product/user bằng RESTRICT, không sửa/xóa qua UI hoặc Eloquent/SQL runtime.
