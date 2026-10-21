> Cập nhật visual21/10 sau lần bàn giao bên dưới: browser đã kết nối lại;15ảnh desktop/tablet/mobile1366/768/320 đã được chụp và xem trực tiếp. ZIP ảnh riêng MNM_NHOM6_C1_ANH_GIAO_DIEN_20261021.zip; [checklist mới](../../.agent/C1_VISUAL_CHECKLIST_20261021.md). Các dòng visual PENDING/disabled bên dưới là trạng thái lúc bàn giao cũ; evidence test và ZIP cũ giữ nguyên. C1 vẫn chờ Thy nghiệm thu/LOCKED; không chạy lại test hay ghi giao dịch trong lượt chụp.

# Cụm 1 — Quản lý tồn kho

Ngày kiểm: 21/10/2026. Đây là phần mở rộng mới trên baseline G1–G6, không sửa hồ sơ nghiệm thu cũ. Cụm 2 bán hàng và Cụm 3 nhà cung cấp chưa triển khai.

Chức năng đã có: xem tồn theo vật tư; Admin nhập/xuất thủ công; lịch sử bất biến; tìm mã/tên, lọc danh mục/vật tư/trạng thái tồn hoặc loại và ngày giao dịch; phân trang, validation và phân quyền. Vật tư chưa giao dịch hiển thị `0.000` mà không tạo dòng tồn giả. Giữ các chức năng Auth, danh mục, đơn vị tính, vật tư và tài khoản.

| URL | Admin | User |
|---|---|---|
| `/inventory` | Xem, tìm, lọc, phân trang tồn | Xem, tìm, lọc, phân trang tồn |
| `/inventory/history` | Xem lịch sử | Xem lịch sử |
| `/inventory/in` GET/POST | Lập và xác nhận nhập thủ công | 403 |
| `/inventory/out` GET/POST | Lập và xác nhận xuất thủ công | 403 |

Demo đang dùng `http://127.0.0.1:8091/login`, database **mnm_inventory_test_20261021**. Database nghiệp vụ **mnm_vat_tu_nong_nghiep chưa migrate kho**; truy cập chức năng kho ở môi trường đó trả 503 có thông báo, không tự tạo bảng.

Đọc [thiết kế và giới hạn](THIET_KE.md), [cách chạy/demo Windows](CHAY_VA_DEMO.md), [kết quả kiểm](../C1_RESULT.md), [bằng chứng đã lọc](../evidence/C1_VERIFICATION.json). Danh sách file và diff nằm trong ZIP bàn giao. Chưa commit/push C1 hoặc G7.

Kiểm thử MySQL và demo HTTP đã thực hiện. Kiểm ảnh trực tiếp tại 1366/768/320px còn PENDING vì công cụ trình duyệt trả lỗi disabled; không dùng ảnh G5 làm bằng chứng giao diện kho mới. Chưa được Thy nghiệm thu/LOCKED.
