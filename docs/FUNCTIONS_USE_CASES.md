# Chức năng, rubric và phân công — G2

Các dòng dưới là phạm vi sẽ triển khai G4, không phải chức năng đã chạy.

| Rubric | Thiết kế | Trạng thái |
|---|---|---|
| Login/logout | username/password_hash, Laravel Auth/session, active user | TODO |
| Admin/User | Quản lý/nhân viên, middleware/policy trước thao tác và render | TODO |
| CRUD ít nhất hai bảng quan hệ | Category cây + Product category/unit; DELETE restrict | TODO |
| Đơn vị tính | Admin quản lý; xóa bị chặn nếu product dùng | TODO |
| Tài khoản | Admin quản lý tài khoản nhân viên; không public registration | TODO |
| Search/pagination | code/name/active ingredient, category, active_only, per_page1..50, order name/id | TODO |
| Form validation | Rule G1 theo G2_DECISIONS và ERD_MYSQL, CSRF và ID | TODO |
| Eloquent/migration/seeder | Bốn bảng nghiệp vụ, ba FK, synthetic demo | TODO |
| ERD/Use Case/chức năng | Hồ sơ G2; Mermaid/PlantUML chưa render | APPLIED_UNVERIFIED cho render |

Guest chỉ login; User xem danh mục/đơn vị và tìm vật tư; Admin thêm/sửa/xóa theo rule và quản lý tài khoản. Không Shop, POS, checkout hoặc lịch sử đơn. Điểm cộng upload/AJAX/responsive chỉ xét sau chức năng bắt buộc.

| Thành viên nhóm 6 | Phân công đề xuất |
|---|---|
| Nguyễn Đức Thành Phát | Migration/MySQL/Eloquent, FK/range/transaction và nghiệm thu dữ liệu |
| Lý Vũ Nhân Hậu | Auth/session/middleware/policy, tài khoản, test quyền |
| Nguyễn Văn Minh | Category/unit và validation/cycle/DELETE/search/pagination |
| Nguyễn Hồ Minh Trọng | Product/Blade/Bootstrap, tích hợp UI, báo cáo/demo và kiểm rubric |

Mỗi module cần người khác review; Thy xác nhận phân công khi triển khai nhóm.
