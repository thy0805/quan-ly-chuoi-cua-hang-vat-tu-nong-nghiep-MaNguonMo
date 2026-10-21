# C1 — Kiểm giao diện ngày 21/10/2026

ALLOWED: Khôi phục dịch vụ hiện hữu; xem UI trên database test mnm_inventory_test_20261021; ảnh desktop/mobile; ZIP ảnh riêng và cập nhật trạng thái liên quan.
FORBIDDEN: Đổi source ứng dụng; migration/seed/ghi nghiệp vụ; sửa ZIP bàn giao cũ; KLCN/NoSQL/Word; commit/push; Cụm 2/3.
SOURCE OF TRUTH: Source C1 hiện hành và giao diện browser thực tế cổng 8091.
INVARIANTS: Vật tư demo C1-DEMO-20-5 còn 15.000; 2 dòng lịch sử; không submit nhập/xuất.
ACCEPTANCE: Ảnh thật 1366/768/320px, kiểm trực quan, đối chiếu URL/kích thước, ZIP đọc lại/CRC/SHA256.

| ID | Hạng mục | Trạng thái | Evidence |
|---|---|---|---|
| V1 | Khôi phục server test, đăng nhập | VERIFIED | Browser tab 3 hiện Quản lý tồn kho; MySQL ready 8.4.3, port3306/PID11456; PHP8091/PID22112 |
| V2 | Chụp/kiểm desktop và mobile | VERIFIED phạm vi màn hình |15ảnhJPEG,1366/768/320; tồn15.000/2lịch sử; menu mở/đóng và cuộn ngang thật; đã xem từng ảnh |
| V3 | ZIP ảnh riêng, readback, bảo toàn source | VERIFIED | logs/c1/visual-20261021/verification.json;17member/15ảnh, CRC/SHA15/15;119hash source không đổi; ZIP bàn giao cũ không đổi |

Không khóa C1 trước khi Thy nghiệm thu.
