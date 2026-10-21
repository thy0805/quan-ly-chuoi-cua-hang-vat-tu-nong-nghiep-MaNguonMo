# C1/C2/C3 chức năng LOCKED — 21/10/2026

Thy đã nghiệm thu C3 và cho phép công bố riêng source lên main. C1 ACCEPTED/LOCKED; C2/C3 FUNCTIONAL ACCEPTED/LOCKED. Browser responsive 1366/768/320 và Print Preview/PDF C2+C3 vẫn PENDING. Không mở C4/G7/Word/slide; không migration/seed database nghiệp vụ.

Nguồn hiện hành: `.agent/decisions/C3_FUNCTIONAL_LOCKED_20261021.md`, `.agent/C3_PUBLICATION_CHECKLIST_20261021.md`, `docs/C3_FUNCTIONAL_ACCEPTED.md`, `docs/C2_C3_VISUAL_PRINT_PENDING.md`. Source C3 36 file khớp ZIP đã duyệt SHA-256 549bd1622801fdbe1c053de0f67cca47f97b074602703f4034a884041236588e; baseline C2 2d5d323e5fa629508a83aa5f542081343a0f7902.

Hồ sơ C3 DRAFT gốc, `docs/c3`, evidence và các snapshot bên dưới là lịch sử theo thời điểm, giữ nguyên để truy vết. Quyết định khóa mới có quyền ưu tiên đối với trạng thái nghiệm thu/push. G7/audit cũ được giữ riêng, không gộp vào commit C3. KLCN/NoSQL/Word/Rose và accepted ZIP được bảo toàn. Evidence chức năng đã duyệt: 75 tests/1.277 assertions, sáu ca đồng thời/12 worker, 48 HTTP; không chạy lại test ghi dữ liệu trong lượt công bố.

---

## Snapshot trước khi khóa Git C3

# C2 FUNCTIONAL ACCEPTED / LOCKED — 21/10/2026

Thy đã duyệt C2/C2-R:61tests/1095assertions,4race/8workers,15HTTP. Browser1366/768/320 và Print Preview/PDF PENDING. Xem .agent/decisions/C2_FUNCTIONAL_LOCKED_C3_AUTHORIZED_20261021.md. Các trạng thái DRAFT/chưa nghiệm thu bên dưới là lịch sử. Database nghiệp vụ chưa migrate; G7/audit chưa nghiệm thu được giữ ngoài commit.

# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

## C1 hiện hành — 21/10/2026

Bổ sung xem tồn, nhập thủ công, xuất thủ công và lịch sử; User chỉ xem, Admin ghi. Xem [C1](c1/README.md). Chưa có bán hàng/nhà cung cấp.

Nguồn hiện tại [C1_RESULT](C1_RESULT.md) và [C1_VERIFICATION](evidence/C1_VERIFICATION.json). Các trạng thái giữ source G5/4bảng/không kho bên dưới là snapshot trước mở rộng; G6/G7 đã khóa không bị viết lại. Database nghiệp vụ vẫn giữ schema cũ, test schema mới đã kiểm độc lập.

---

## Lịch sử từ checkpoint G6 đã công bố

Cập nhật hiện hành18/10/2026: G6/G6-R đã ACCEPTED/LOCKED, được chốt Git theo decision G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018. Publication đang kiểm, SHA sau push đọc tại context/receipt mới. Sau remote readback sạch chỉ chuẩn bị G7 DRAFT, chưa commit/push G7. Các trạng thái G6 DRAFT/chưa push bên dưới giữ checkpoint cũ; source/nghiệp vụ và accepted G6 content không sửa.

# Chức năng, rubric và phân công — G5

Trạng thái hiện hành dựa kiểm G5 trên MySQL8.4.3:31test/674assertions,29lượt TCP và browser thật1366/768/320. G4B đã được Thy nghiệm thu; G5 đã ACCEPTED/LOCKED trong phạm vi bằng chứng; G6 chỉ DRAFT theo quyền mới. Rubric dựa mô tả Thy cung cấp, chưa có phiếu chấm gốc. G5 nghiệm thu theo decision G5_ACCEPTED_G6_DRAFT_AUTHORIZED; rubric gốc chưa được cung cấp.

| Rubric | Thiết kế | Trạng thái |
|---|---|---|
| Login/logout | username/password_hash, Laravel Auth/session, active user | VERIFIED G4B |
| Admin/User | Quản lý/nhân viên, middleware/policy trước thao tác và render | VERIFIED G4B |
| CRUD ít nhất hai bảng quan hệ | Category cây + Product category/unit; DELETE restrict | VERIFIED G4B |
| Đơn vị tính | Admin quản lý; xóa bị chặn nếu product dùng | VERIFIED G4B |
| Tài khoản | Admin quản lý tài khoản nhân viên; không public registration | VERIFIED G4B |
| Search/pagination | code/name/active ingredient; active_only/is_active/category/unit; per_page1..50, name/id hoặc username/id | VERIFIED G4B |
| Form validation | Rule G1 theo G2_DECISIONS và ERD_MYSQL, CSRF và ID | VERIFIED G4B |
| Eloquent/migration/seeder | Bốn bảng nghiệp vụ, ba FK, synthetic demo | LOCKED G4A; regression VERIFIED G5 |
| ERD/Use Case/chức năng | G6 schema-derived SVG + source Mermaid/PlantUML/JSON; browserQA | VERIFIED render kỹ thuật, G6 DRAFT chờ nghiệm thu |

Guest chỉ login; User xem danh mục/đơn vị và tìm vật tư; Admin thêm/sửa/xóa theo rule và quản lý tài khoản. Không Shop, POS, checkout hoặc lịch sử đơn. Responsive G5 đã kiểm trong ma trận G5_BROWSER_QA.json và22ảnh; không có upload/AJAX ghi dữ liệu hoặc đặt hàng. Dashboard chỉ thống kê dữ liệu hiện có, không tạo nghiệp vụ mới.

| Thành viên nhóm 6 | Phân công đề xuất |
|---|---|
| Nguyễn Đức Thành Phát | Migration/MySQL/Eloquent, FK/range/transaction và nghiệm thu dữ liệu |
| Lý Vũ Nhân Hậu | Auth/session/middleware/policy, tài khoản, test quyền |
| Nguyễn Văn Minh | Category/unit và validation/cycle/DELETE/search/pagination |
| Nguyễn Hồ Minh Trọng | Product/Blade/Bootstrap, tích hợp UI, báo cáo/demo và kiểm rubric |

Mỗi module cần người khác review; Thy xác nhận phân công khi triển khai nhóm.

Hồ sơ G6 hiện hành: [đề tài/phân công](g6/DE_TAI_CHUC_NANG.md), [Use Case](g6/UML_USE_CASE.md), [rubric](g6/RUBRIC_DOI_CHIEU.md), [demo](g6/DEMO_KHOI_DONG.md). Phân công vẫn đề xuất, chưa xác nhận đóng góp. Sơ đồ G2 cũ giữ lịch sử; SVG G6 render từ cùng metadata/model, không tuyên bố đã compile engine PlantUML/Mermaid.
