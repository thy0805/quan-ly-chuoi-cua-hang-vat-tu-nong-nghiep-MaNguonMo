# Visual/Print QA C2 + C3 — PENDING

Ngày lập: 21/10/2026. Đây là kế hoạch kiểm, không phải kết quả PASS. Lượt khóa Git không có công cụ trình duyệt điều khiển/chụp được; không lặp các lần thử công cụ không callable và không dùng ảnh C1 thay thế.

## Điều kiện thực hiện

Chờ Thy giao QA chung. Dùng đúng source đã khóa và MySQL test cô lập theo `docs/c3/CHAY_VA_DEMO.md`. Đọc lại checkpoint và xác minh server trước khi mở trang. Ưu tiên xem fixture có sẵn, không xác nhận lại hoặc sửa fixture đã nghiệm thu; nếu cần thao tác ghi thì dùng fixture test riêng trong phạm vi được giao.

Mỗi màn hình kiểm ở ba chiều rộng **1366, 768 và 320 px**, ghi cả chiều cao, vai trò, URL, commit, thời điểm, kết quả và đường dẫn ảnh thật. Kiểm tràn trang, cuộn bảng, thanh điều hướng, nút thao tác, nhãn/lỗi form, bàn phím và việc đọc tổng tiền. Chưa tuyên bố đã kiểm cảm ứng thiết bị thật.

| ID | Màn hình / URL | Vai trò / điểm cần nhìn | Trạng thái |
|---|---|---|---|
| V01 | `/sales` | Admin/User; bộ lọc, trạng thái rỗng, phân trang và giới hạn thấy phiếu theo quyền | PENDING |
| V02 | `/sales/create` | Form nhiều dòng; thêm/bỏ; dòng còn lại sau validation có chỉ số thưa; lỗi tiếng Việt | PENDING |
| V03 | `/sales/{id}/edit` | Nháp của người lập, lưu lại và thông báo version cũ; không ghi vào fixture khóa | PENDING |
| V04 | `/sales/95` | Hai dòng, tổng 67500.00, trạng thái đã xác nhận, liên kết lịch sử/in; không còn nút sửa/xác nhận | PENDING |
| V05 | `/sales/95/print` | Trang hóa đơn nội bộ trên ba kích thước trước khi vào Print Preview | PENDING |
| V06 | `/suppliers` | Admin/User; tìm, lọc hoạt động, phân trang, thao tác ghi chỉ Admin | PENDING |
| V07 | `/suppliers/create`, `/suppliers/{id}/edit` | Admin; trường bắt buộc, lỗi email/mã trùng, trạng thái và giữ dữ liệu sau validation | PENDING |
| V08 | `/suppliers/{id}` | Admin/User; tên/địa chỉ dài, liên kết phiếu; thông báo không xóa được nhà cung cấp đã sử dụng | PENDING |
| V09 | `/purchases` | Admin/User; bộ lọc nhà cung cấp/ngày/trạng thái, phân trang, trạng thái rỗng | PENDING |
| V10 | `/purchases/create`, `/purchases/{id}/edit` | Admin; chọn đối tác, nhiều dòng, thêm/bỏ, số thập phân, lỗi và old input thưa | PENDING |
| V11 | `/purchases/{id}` nháp | Chỉ Admin là người lập có nút sửa/xác nhận; thông báo nháp chưa cộng kho | PENDING |
| V12 | `/purchases/48` | Admin/User; tổng 420000.00, snapshot đối tác, hai dòng, trạng thái khóa và liên kết lịch sử | PENDING |
| V13 | `/purchases/48/print` | Trang phiếu nhập nội bộ trên ba kích thước trước khi vào Print Preview | PENDING |
| V14 | `/inventory`, `/inventory/history` | Tra hai mã C3-HTTP-f738f634-A/B; tồn 18.000/7.000, nguồn nhập/bán theo từng dòng | PENDING |

## Print Preview và PDF A4

| ID | Ca kiểm | Điều kiện đạt cần quan sát | Trạng thái |
|---|---|---|---|
| P01 | Hóa đơn `/sales/95/print`, A4 dọc | Không cắt cột/tổng; ẩn sidebar và nút; đúng nội dung 67500.00 VND | PENDING |
| P02 | Phiếu nhập `/purchases/48/print`, A4 dọc | Đọc đủ đối tác, số lượng, đơn giá, tổng 420000.00; không cắt nội dung | PENDING |
| P03 | Hóa đơn nhiều dòng / tên dài | Kiểm chuyển trang, tiêu đề bảng, dòng/tổng và ghi chú; ghi lỗi nếu có | PENDING |
| P04 | Phiếu nhập nhiều dòng / địa chỉ dài | Kiểm chuyển trang, bảng/tổng và snapshot đối tác; không làm biến mất dòng | PENDING |
| P05 | Mở lại PDF đã lưu P01–P04 | Render và kiểm mọi trang; đối chiếu số dòng, tổng, chữ tiếng Việt với chứng từ | PENDING |

Khi thực hiện, lưu ảnh thật và PDF trong hồ sơ QA riêng có manifest/CRC/SHA-256, kết luận theo từng ca. Chỉ HTTP 200 hoặc gọi `window.print()` chưa đáp ứng ca in. Nếu phát hiện lỗi cần sửa source chức năng LOCKED, ghi cụ thể và xin phạm vi sửa tương ứng; không mở nghiệp vụ mới.
