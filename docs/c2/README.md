## C2-R hiện hành — 21/10/2026

[Hồ sơ C2-R](../C2_R_RESULT.md): checked-add trước phép cộng,61/61tests/1095assertions,HTTP15cổng8092 và4race C2 đạt. Browser1366/768/320/in thật PENDING;chưa commit/push. Phần dưới là snapshot C2 trước sửa.

---

# C2 — Bán hàng tại quầy

Đọc [kết quả và giới hạn](../C2_RESULT.md), [thiết kế](THIET_KE.md), [demo Windows](CHAY_VA_DEMO.md), [evidence](../evidence/C2_VERIFICATION.json).

Source Laravel/Blade/Bootstrap hiện hữu được mở rộng, không tạo website riêng. Admin/User đăng nhập như trước; menu Bán hàng dẫn tới `/sales`. User lập/xác nhận/xem phiếu của mình; Admin đọc toàn cửa hàng nhưng chỉ sửa/xác nhận phiếu mình lập. Kho thủ công vẫn dành Admin.

C2 có mã và đã kiểm chức năng trên MySQL test; browser/responsive/in thực tế PENDING. C1 đã LOCKED/push; C2 chưa được nghiệm thu hoặc push. Hai bảng mới chỉ hiện diện ở database test, database nghiệp vụ chưa thay đổi.
