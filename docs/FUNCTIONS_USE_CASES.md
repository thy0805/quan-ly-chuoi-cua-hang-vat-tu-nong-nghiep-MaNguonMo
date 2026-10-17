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
| ERD/Use Case/chức năng | Hồ sơ G2; Mermaid/PlantUML chưa render | APPLIED_UNVERIFIED cho render |

Guest chỉ login; User xem danh mục/đơn vị và tìm vật tư; Admin thêm/sửa/xóa theo rule và quản lý tài khoản. Không Shop, POS, checkout hoặc lịch sử đơn. Responsive G5 đã kiểm trong ma trận G5_BROWSER_QA.json và22ảnh; không có upload/AJAX ghi dữ liệu hoặc đặt hàng. Dashboard chỉ thống kê dữ liệu hiện có, không tạo nghiệp vụ mới.

| Thành viên nhóm 6 | Phân công đề xuất |
|---|---|
| Nguyễn Đức Thành Phát | Migration/MySQL/Eloquent, FK/range/transaction và nghiệm thu dữ liệu |
| Lý Vũ Nhân Hậu | Auth/session/middleware/policy, tài khoản, test quyền |
| Nguyễn Văn Minh | Category/unit và validation/cycle/DELETE/search/pagination |
| Nguyễn Hồ Minh Trọng | Product/Blade/Bootstrap, tích hợp UI, báo cáo/demo và kiểm rubric |

Mỗi module cần người khác review; Thy xác nhận phân công khi triển khai nhóm.
