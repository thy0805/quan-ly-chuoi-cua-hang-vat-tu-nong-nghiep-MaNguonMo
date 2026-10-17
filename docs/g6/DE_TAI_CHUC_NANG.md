# Đề tài, phạm vi và phân công — G6 DRAFT

## 1. Đề tài và mục tiêu

Xây dựng ứng dụng quản lý cửa hàng vật tư nông nghiệp phục vụ môn Lập trình mã nguồn mở, Nhóm6. Ứng dụng hỗ trợ chuẩn hóa danh mục, đơn vị tính, thông tin vật tư và tài khoản sử dụng; giảm tra cứu thủ công thông qua tìm kiếm, lọc và phân trang. Đây là phạm vi catalog/quản lý tài khoản, chưa phải hệ thống bán hàng hoặc quản lý chuỗi hoàn chỉnh.

Source môn học độc lập KLCN/NoSQL; Laravel13.33/PHP8.3.30, MySQL8.4.3/InnoDB/utf8mb4, Eloquent, Blade và Bootstrap5.3.8 offline/MIT. Database mnm_vat_tu_nong_nghiep. Session/cache bằng file riêng, không cần worker, Node, Next.js hoặc Flutter để chạy giao diện. Scaffold Vite/Tailwind cũ còn lưu nhưng không route hiện hành sử dụng; không coi là công nghệ frontend G5.

## 2. Vai trò và chức năng đã triển khai

| Vai trò | Quyền và giới hạn |
|---|---|
| Chưa đăng nhập | Mở trang đăng nhập; route bảo vệ chuyển về login, JSON yêu cầu xác thực |
| Nhân viên (User) đang hoạt động | Login/logout, dashboard theo quyền; xem danh mục/đơn vị/vật tư, chi tiết, tìm kiếm/lọc/phân trang; server từ chối thao tác quản lý bằng403 |
| Quản trị viên (Admin) đang hoạt động | Quyền xem/tra cứu như User; CRUD danh mục/đơn vị/vật tư/tài khoản nhân viên; dashboard thêm số tài khoản |

Danh mục có cây cha-con, cấm tự trỏ/vòng nhiều cấp, yêu cầu cha hợp lệ theo trạng thái; ngừng danh mục không tự cascade ngừng con. Vật tư liên kết đúng một danh mục và một đơn vị, giá DECIMAL(18,2) không âm, thuế rỗng hoặc0..100, hạn cảnh báo rỗng hoặc0..3650. Dữ liệu tham chiếu khi tạo/thay đổi hoặc vật tư active phải đáp ứng rule active trong domain. Xóa bị chặn khi có con/vật tư tham chiếu; FK RESTRICT là lớp bảo vệ cuối.

Tài khoản đăng nhập bằng username/password_hash; mật khẩu băm, không trả hash qua JSON/Blade. Role cố định admin/user, form nhân viên không tự nâng role; tối đa một Admin tại database, ứng dụng chặn xóa/hạ quyền/ngừng Admin hiện hữu. Login regenerate session, logout invalidate/regenerate CSRF; active middleware đọc lại trạng thái user mỗi request. Không đăng ký công khai/quên mật khẩu/remember/token API.

Dashboard đọc số liệu thật:4vật tư,4active,5danh mục,3đơn vị ở fixture hiện tại; chỉ Admin thấy3tài khoản. Không tạo số doanh thu/tồn kho giả. Tìm kiếm theo định nghĩa catalog, LOWER/LIKE với binding và thứ tự name/id; per_page1..50, mặc định20. Validation tiếng Việt, giữ old-input, CSRF, trạng thái thành công/thất bại; mobile dùng offcanvas và bảng cuộn trong vùng.

## 3. Các phần chưa triển khai

Không POS/Shop/checkout/đặt hàng/lịch sử đơn, kho/lô/công nợ/worker; không upload ảnh hoặc AJAX ghi dữ liệu. Responsive đã có ma trận mẫu1366/768/320, chưa là chứng nhận mọi thiết bị/trình duyệt. Không đưa các phần này vào chức năng đã hoàn thành hoặc Use Case bắt buộc.

## 4. Phân công đề xuất cho bốn thành viên

| Thành viên | Phần phụ trách đề xuất | Người kiểm chéo | Sản phẩm giải thích/demo |
|---|---|---|---|
| Nguyễn Đức Thành Phát | MySQL/migration/seeder/Eloquent, FK/CHECK/transaction | Lý Vũ Nhân Hậu | ERD/schema, quan hệ, minh họa migration và baseline |
| Lý Vũ Nhân Hậu | Auth/session/middleware/phân quyền/tài khoản | Nguyễn Văn Minh | Login/logout, User403, bảo vệ Admin |
| Nguyễn Văn Minh | Danh mục/đơn vị, validation/cây/xóa/tìm kiếm/phân trang | Nguyễn Hồ Minh Trọng | Quan hệ cha-con, bộ lọc và RESTRICT qua test |
| Nguyễn Hồ Minh Trọng | Vật tư/Blade/Bootstrap/tích hợp UI, tổng hợp hồ sơ/demo | Nguyễn Đức Thành Phát | Layout responsive, form/error, rubric/demo |

Đây là phân công đề xuất, chưa là xác nhận ai đã thực hiện phần nào hoặc tỷ lệ đóng góp; nhóm cần xác nhận trước báo cáo cuối. Mỗi thành viên đọc module của mình và một module kiểm chéo; không chỉ học thuộc thao tác giao diện.
