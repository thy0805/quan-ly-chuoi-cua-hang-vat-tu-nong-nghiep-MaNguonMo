# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

# Mã nguồn mở — Cụm 1 tồn kho — 21/10/2026

CURRENT TASK: Đã bổ sung15ảnh C1 trực tiếp bằng IAB ở1366/768/320px và ZIP ảnh riêng; chờ Thy nghiệm thu, chưa Cụm2/3.
SOURCE CHECKPOINT: HEAD main ad30d92dbc0550839c788e3d17a293dcc9218c45 không đổi; source C1 còn working tree, không commit/push. Có tài liệu G7/audit dirty từ trước, không reset/clean/stash.
ALLOWED SCOPE: Chỉ repository Mã nguồn mở; code/tests/Blade/guard cho C1, test database mnm_inventory_test_20261021, hồ sơ C1 và governance liên quan.
LOCKED: Evidence/ZIP/sơ đồ G1–G6, nội dung G7 đã có; KLCN/NoSQL/Word/slide; dữ liệu/schema database nghiệp vụ mnm_vat_tu_nong_nghiep.
APPLIED BUT UNVERIFIED: Chưa nghiệm thu/LOCKED C1; chưa kiểm đầy đủ thao tác cảm ứng/mobile. Visual các màn hình kho đã được chụp và kiểm ngày21/10 sau khi kết nối browser trở lại.
VERIFIED: 44/44 tests,828 assertions qua tools/Test-Inventory.ps1;4 race cases/8 real workers qua -ConcurrencyOnly;HTTP thật Admin/User/CSRF/assets, nhập20/xuất5=>15/đúng2movement;test schema6bảngnghiệpvụ/42cột/6FK/11CHECK/4trigger;ledger readback khớp. DB nghiệp vụ trước/sau5bảng gồm migrations giữ hash dữ liệu/DDL/trigger/ledger; kho ở DB đó trả503, chưa migrate.
PENDING: Thy/GPT Web nghiệm thu C1 sau khi rà ZIP ảnh mới; triển khai migration nghiệp vụ phải có quyền riêng; G7 Word/PPTX và xác nhận nhóm vẫn pending; Cụm2/3 chỉ kế hoạch, chưa có code.
LAST EVIDENCE: Ảnh mới tại .agent/logs/c1/visual-20261021/images, receipt verification.json và MNM_NHOM6_C1_ANH_GIAO_DIEN_20261021.zip (15JPEG/17member; CRC và SHA15/15 đạt;119file app không đổi). ZIP bàn giao cũ giữ nguyên. .agent/C1_VISUAL_CHECKLIST_20261021.md. docs/C1_RESULT.md, docs/evidence/C1_VERIFICATION.json, docs/c1; raw logs private/ignored .agent/logs/c1; ZIP MNM_NHOM6_C1_TON_KHO_20261021.zip cùng receipt package-final.json. Kiểm cuối theo receipt, không suy PASS từ tên file.
NEXT EXACT ACTION: Thy/GPT Web rà ZIP ảnh riêng và nghiệm thu C1. Trang tồn kho test đang mở cổng8091; MySQL8.4.3/PID11456, PHP/PID22112 tại thời điểm chụp, phải kiểm lại trước khi dùng. Không replay provision/seed/migrate/push hoặc nhập lại20vào vật tư đang còn15; chỉ C2 khi được giao.

## C1 hiện hành — 21/10/2026

Quyền mới của Thy tại .agent/decisions/C1_INVENTORY_20261021.md thay giới hạn không sửa source/không kho trong task G7 cũ. Baseline nghiệm thu không bị viết lại. Kế hoạch canonical mới .agent/C1_INVENTORY_CHECKLIST.md; G7 checklist giữ snapshot riêng. PHP8.3.30/MySQL8.4.3, Laravel/Eloquent/Blade/Bootstrap giữ nguyên. Hai bảng kho tách khỏi products, quantity decimal-string/milli-unit; shared service/transaction/product-lock/idempotency/FK RESTRICT/immutable triggers. Source ở D: mạng, MySQL/PHP chạy trên máy Laragon.

---

## Lịch sử từ checkpoint G6 đã công bố

# Mã nguồn mở — G6/G6-R ACCEPTED/LOCKED; publication rồi G7 DRAFT

CURRENT TASK: Chốt Git G6 đã nghiệm thu, sau remote readback/clean mới chuẩn bị G7 DRAFT hồ sơ nộp.
SOURCE CHECKPOINT: main/origin68770c0d2dc8b0e743a69564112be41eed54dd69 trước publication G6; SHA mới chỉ đọc từ Git/receipt sau push. G6-R ZIP a432d9ad33c9f60cafe9f40a9daae5099457529a45dfc7fcae04870acb2cb01f,44payload/45member.
ALLOWED SCOPE: .agent/G6_PUBLICATION_G7_CHECKLIST và decision G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018. Explicit whitelist G6 commit/push; sau đó chỉ G7 docs/ảnh tham chiếu/rubric/demo/evidence/ZIP và governance.
LOCKED: G1–G6 theo phạm vi nghiệm thu; source G5/schema/models/Auth/CRUD/config/dependencies;8Markdown G6/sơ đồ/nguồn/ảnh/lệnh demo/evidence accepted; KLCN/NoSQL/repo cha/Word/Rose read only. Không database mutation/migrate/seed/cleanup/ENV04 hoặc sửa MySQL.
APPLIED BUT UNVERIFIED: Publication G6 đang chuẩn bị stage, chưa remote readback; G7 chưa tạo. Không suy G6 accepted nghĩa đã push.
VERIFIED: Manifest44/44/CRC/SHA ZIP,26source hash và app0diff; Git root/main/origin G5 đúng. Fixture SELECT mới3users/5categories/3units/4products, nội dung/schema/ledger giữ baseline. Protected Git đầu lượt18/10 khớp HEAD/index/config lịch sử; parent có thêm2Word lockfiles trước task, được ghi riêng, không sửa/đọc nội dung chúng. G6-R startup/browser/test là evidence17/10 đã nghiệm thu, không chạy lại lượt này.
PENDING: Whitelist/staged/commit/push/readback; G7 Markdown/slide outline/rubric/demo/missing/ZIP. Rubric gốc, mẫu báo cáo/trường/lớp/GV và phân công thật chưa có; không tự bịa. Không Word/PDF/PPTX hoặc G7 commit/push khi chưa duyệt.
LAST EVIDENCE: docs/evidence/G6_PUBLICATION_PREFLIGHT.json; .agent/logs/g7/g6-accepted-before.json; g7-publication-fixture-20261018; decision18/10.
NEXT EXACT ACTION: Rà staged blob/diff/secret, commit riêng G6 rồi push main noforce; readback SHA/tree/filelist/clean, sau đó D01–D02 G7. Dừng G7 DRAFT.

Ngày client18/10/2026. Ảnh danh sách nhóm Thy gửi xác nhận Phát2001230640/Hậu2001230227/Minh2001230517/Trọng2001230992,Nhóm6/Tuần11; chỉ dùng cho hồ sơ G7 local. Phân công G6 là đề xuất. Không public credential/.env/private/log/dump/dependencies. Không dùng câu DRAFT/chưa push trong accepted artifact G6 làm trạng thái mới; giữ byte lịch sử theo DOC_INDEX. Source/test/runtime khác nghiệm thu và khác thầy chấm điểm.
