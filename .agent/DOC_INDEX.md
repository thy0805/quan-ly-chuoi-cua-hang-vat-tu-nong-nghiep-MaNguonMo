# C2 FUNCTIONAL ACCEPTED / LOCKED — 21/10/2026

Thy đã duyệt C2/C2-R:61tests/1095assertions,4race/8workers,15HTTP. Browser1366/768/320 và Print Preview/PDF PENDING. Xem .agent/decisions/C2_FUNCTIONAL_LOCKED_C3_AUTHORIZED_20261021.md. Các trạng thái DRAFT/chưa nghiệm thu bên dưới là lịch sử. Database nghiệp vụ chưa migrate; G7/audit chưa nghiệm thu được giữ ngoài commit.

# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

# Bản đồ nguồn hiện hành — Cụm 1

## C1 hiện hành — 21/10/2026

Ưu tiên: yêu cầu C1 Thy21/10 → decision C1 → code/schema/test receipt hiện hành → context/PLAN → các snapshot G1–G7 theo thời điểm. Hồ sơ G6/G7 vẫn giữ byte nhưng chưa mô tả phần mở rộng kho; không dùng làm ERD hiện hành của test schema mới.

| Tài liệu | Vai trò/trạng thái | Đọc khi |
|---|---|---|
| [context](../context.md) | Điểm tiếp tục hiện hành | Đầu phiên |
| [PLAN](PLAN.md) | Kế hoạch C1 hiện hành | Chọn việc tiếp |
| [Checklist C1](C1_INVENTORY_CHECKLIST.md) | Scope và evidence | Trước sửa/kiểm C1 |
| [Visual C1](C1_VISUAL_CHECKLIST_20261021.md) | Hiện hành:15ảnh browser và ZIP ảnh riêng; thay trạng thái browser disabled tại lần bàn giao trước | Rà giao diện và nghiệm thu C1 |
| [Decision C1](decisions/C1_INVENTORY_20261021.md) | Quyền mở rộng và invariants | Có mâu thuẫn phạm vi cũ |
| [Kết quả C1](../docs/C1_RESULT.md) | Bàn giao, giới hạn, file | Nghiệm thu |
| [Hướng dẫn C1](../docs/c1/README.md) | Chức năng/URL | Xem ứng dụng |
| [Thiết kế C1](../docs/c1/THIET_KE.md) | Schema/transaction/replay | Sửa kho; lập C2/3 |
| [Demo Windows](../docs/c1/CHAY_VA_DEMO.md) | Test-only runtime/demo | Thuyết trình |
| [C1 verification](../docs/evidence/C1_VERIFICATION.json) | Bằng chứng đã lọc | Đối chiếu kết quả |

G1–G6 LOCKED; G7 DRAFT chờ nghiệm thu, không sửa Word/slide hoặc sơ đồ cũ trong C1. Rubric kỹ thuật Thy đã cung cấp20/10; phân công thực tế và duyệt đề tài vẫn chưa xác nhận. Các nhãn hiện hành ở bảng cũ bên dưới chỉ mô tả thời điểm trước C1.

---

## Lịch sử từ checkpoint G6 đã công bố

# Nguồn hiện hành18/10 — G6 ACCEPTED/LOCKED; publication rồi G7 DRAFT

Ưu tiên: Thy mới nhất → decision18/10 → code/accepted artifact → context/PLAN/checklist mới → checkpoint cũ. G6/G6R_RESULT,8Markdown G6,diagram/evidence/checklist cũ giữ byte; chữ DRAFT/chưa push phản ánh thời điểm trước quyết định nghiệm thu. Không tự rewrite các artifact đã khóa.

| Tài liệu | Vai trò | Trạng thái | Khi đọc |
|---|---|---|---|
| .agent/decisions/G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018.md | Quyền chốt Git G6 và DRAFT G7 | Hiện hành ưu tiên | Trước Git/G7 |
| .agent/G6_PUBLICATION_G7_CHECKLIST.md | Scope/checklist hiện hành | Hiện hành | Đầu phiên |
| docs/evidence/G6_PUBLICATION_PREFLIGHT.json | Accepted manifest/source/protected trước stage | Hiện hành kiểm trước commit, không là push receipt | Trước publication |
| context.md, .agent/PLAN.md | Recovery và tiến độ | Hiện hành | Đầu phiên |

Bảng dưới là inventory checkpoint G6-R đã nghiệm thu, không phải quyền thực thi mới; các nhãn Hiện hành/DRAFT G6 của bảng cũ đã chuyển thành accepted checkpoint theo decision18/10.

---

# Bản đồ tài liệu — G5 LOCKED/published, G6 DRAFT

Ưu tiên: Thy mới nhất → code/runtime/evidence trực tiếp → quyết định được duyệt/thiết kế G2 → context/PLAN/checklist → lịch sử. G5 main68770c0d2dc8b0e743a69564112be41eed54dd69 đã push; G6 chỉ docs/diagram/evidence/governance local, chưa nghiệm thu/commit/push. Không dùng câu TODO/chờ/HTTP503/chưaboot/G5chưaởGit trong checkpoint cũ làm trạng thái hiện hành; không sửa evidence/history để đổi quá khứ.

| Tài liệu | Vai trò/trạng thái | Khi đọc/phụ thuộc/ưu tiên |
|---|---|---|
| .agent/AUTH02_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/CLONE_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/DOC_INDEX.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/ENV03_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/ENV04_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G23_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G4A_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G4A_IMPLEMENTATION_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G4A_RESUME_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G4B_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G5_CHECKLIST.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G5_PUBLICATION_G6_CHECKLIST.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/G6R_CHECKLIST.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/PLAN.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/AUTH02_LOCAL_LOGIN_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/CLONE_FIRST_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/ENV03_DIAGNOSTIC_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/ENV04_ACCEPTED_G4A_RESUMED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/ENV04_CONDITIONAL_REPAIR_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/ENV04_OWNER_SHUTDOWN_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G23_ACCEPTED_SCOPE_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G4A_ACCEPTED_G4B_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G4A_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G4A_DIRECT_MYSQL_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G4A_TRIGGER_ADMIN_DDL_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G4B_ACCEPTED_G5_AUTHORIZED_20261017.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/decisions/G5_ACCEPTED_G6_DRAFT_AUTHORIZED_20261017.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/mistake.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| .agent/rule.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| AGENTS.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| README.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| context.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/ARCHITECTURE.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/AUDIT_KLCN.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/BIEN_BAN_GIAI_DOAN_1.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/CONVERSION_MAPPING.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/ENV03_DIAGNOSTIC.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/ENV04_REPAIR_RESULT.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/ERD_MYSQL.md | Thiết kế LOCKED | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/FUNCTIONS_USE_CASES.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G2_DECISIONS.md | Thiết kế LOCKED | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G3_ISOLATION_RESULT.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G4A_RESULT.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G4B_RESULT.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G5_RESULT.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G6R_RESULT.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/G6_RESULT.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/IMPLEMENTATION_PLAN.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/PUBLICATION.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/README_GUI_GPT_WEB.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/REUSED_FILES.md | Lịch sử/tham khảo checkpoint | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/DEMO_KHOI_DONG.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/DE_TAI_CHUC_NANG.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/ERD_MYSQL_THUC_TE.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/MINH_HOA_LARAVEL.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/NGHIEM_THU_GIOI_HAN.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/README.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/RUBRIC_DOI_CHIEU.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |
| docs/g6/UML_USE_CASE.md | Hiện hành/DRAFT G6 | Đọc khi liên quan; code/runtime/decision mới ưu tiên trạng thái |

## Evidence và runtime

| Đường dẫn | Vai trò | Trạng thái/khi đọc |
|---|---|---|
| docs/evidence/G6_G5_PUBLICATION.json, G6_G5_STAGE_REVIEW.json | Remote SHA/tree/fetch/clean và78staged blob review | Hiện hành Git; G6 chưa push |
| docs/evidence/G5_PUBLICATION_PREFLIGHT.json |26source/ZIP/hồi quy mới31/674/fixture/lint/protected trước G5 commit | Checkpoint kiểm trước publication |
| docs/evidence/G6_MYSQL_SCHEMA.json, G6_MYSQL_READBACK.json, G6_FIXTURE_INTEGRITY.json | SELECT schema4tables26columns/3FK/7CHECK/2trigger,fixture3/5/3/4 giữ | Hiện hành metadata G6 |
| docs/evidence/G6_RENDER_QA.json, G6_VERIFICATION.json | SVG browser/structural/semantic/link/snippet/rubric/protected/secret | Hiện hành kiểm kỹ thuật; G6 vẫn DRAFT |
| docs/evidence/G6_STARTUP_CHECK.json | Public cwd/framework router/private .env,login+4assets200 | Hiện hành scope startup GET; không portable installer |
| docs/G6R_RESULT.md, .agent/G6R_CHECKLIST.md | Sửa tối thiểu block PowerShell theo review Thy/GPT Web | Hiện hành G6-R DRAFT; không commit/push |
| docs/evidence/G6R_STARTUP_CHECK.json, G6R_VERIFICATION.json | Lệnh trích trực tiếp Markdown, parser/path/GET và control/fence/link/bảo toàn | Hiện hành sửa blocker tài liệu; ZIP G6 gốc giữ nguyên |
| docs/evidence/G6_ENV04_EVIDENCE_READBACK.json |44hash hiện tại đối chiếu manifest ENV04 | Kiểm đọc riêng; không replay repair/suy từ SQLservice |
| docs/evidence/G5_*, G4B_*, G4A_*, ENV04_*, ENV03_*, G23_*, G1_* | Evidence checkpoint trước đó | Lịch sử đã nghiệm thu theo phạm vi, giữ byte |
| docs/g6/diagrams/ | SVG hiển thị/MMD/PUML/JSON nguồn và2ảnh browser | Hiện hành DRAFT; không claim engine MMD/PUML compiled |
| docs/diagrams/use-cases.puml, docs/ERD_MYSQL.md | Thiết kế G2 cũ | Lịch sử/thiết kế khóa, không ghi đè bằng G6 |
| .agent/private/g4b/baseline.json, .env, .agent/private/g4a/ | Baseline/credential | Private ignored; chỉ verify, không reset/in/copy |
| .agent/private/g5-preview.json, g6-preview.json | PID/URL hiện tại | Kiểm sống trước dùng; không bảo đảm sau reboot |
| .agent/scripts/, .agent/logs/, .agent/archive/ | Helper/log gốc/nguồn cũ | Local ignored; không public hoặc replay helper cũ |
| .agent/logs/g6/package-final.json | ZIP CRC/exact-set/member SHA/whole SHA receipt | Bàn giao chỉ khi PASS |
