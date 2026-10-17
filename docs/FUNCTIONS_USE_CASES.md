# Chức năng, rubric và phân công — G4B

Trạng thái dựa kiểm trực tiếp G4B trên MySQL 8.4.3:26 test / 425 assertions và24 lượt TCP HTTP. Rubric dựa mô tả Thy cung cấp, chưa có phiếu chấm gốc. VERIFIED kỹ thuật chưa thay nghiệm thu của Thy.

| Rubric | Thiết kế | Trạng thái |
|---|---|---|
| Login/logout | username/password_hash, Laravel Auth/session, active user | VERIFIED G4B |
| Admin/User | Quản lý/nhân viên, middleware/policy trước thao tác và render | VERIFIED G4B |
| CRUD ít nhất hai bảng quan hệ | Category cây + Product category/unit; DELETE restrict | VERIFIED G4B |
| Đơn vị tính | Admin quản lý; xóa bị chặn nếu product dùng | VERIFIED G4B |
| Tài khoản | Admin quản lý tài khoản nhân viên; không public registration | VERIFIED G4B |
| Search/pagination | code/name/active ingredient; active_only/is_active/category/unit; per_page1..50, name/id hoặc username/id | VERIFIED G4B |
| Form validation | Rule G1 theo G2_DECISIONS và ERD_MYSQL, CSRF và ID | VERIFIED G4B |
| Eloquent/migration/seeder | Bốn bảng nghiệp vụ, ba FK, synthetic demo | LOCKED G4A; regression VERIFIED G4B |
| ERD/Use Case/chức năng | Hồ sơ G2; Mermaid/PlantUML chưa render | APPLIED_UNVERIFIED cho render |

Guest chỉ login; User xem danh mục/đơn vị và tìm vật tư; Admin thêm/sửa/xóa theo rule và quản lý tài khoản. Không Shop, POS, checkout hoặc lịch sử đơn. Điểm cộng upload/AJAX/responsive chỉ xét sau chức năng bắt buộc.

| Thành viên nhóm 6 | Phân công đề xuất |
|---|---|
| Nguyễn Đức Thành Phát | Migration/MySQL/Eloquent, FK/range/transaction và nghiệm thu dữ liệu |
| Lý Vũ Nhân Hậu | Auth/session/middleware/policy, tài khoản, test quyền |
| Nguyễn Văn Minh | Category/unit và validation/cycle/DELETE/search/pagination |
| Nguyễn Hồ Minh Trọng | Product/Blade/Bootstrap, tích hợp UI, báo cáo/demo và kiểm rubric |

Mỗi module cần người khác review; Thy xác nhận phân công khi triển khai nhóm.
