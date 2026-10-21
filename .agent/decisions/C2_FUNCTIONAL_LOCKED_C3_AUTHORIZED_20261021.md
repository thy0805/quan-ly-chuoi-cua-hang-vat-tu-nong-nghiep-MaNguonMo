# C2 chức năng LOCKED; C3 được giao — 21/10/2026

Nguồn: Thy attachment72b741aa-109a-4aac-b86a-6e535ee68f63. C2/C2-R FUNCTIONAL ACCEPTED:61tests/1095assertions,4race/8workers,15HTTP. Browser1366/768/320 và Print Preview/PDF vẫn PENDING. Không sửa thêm tiền C2; ZIP cũ giữ nguyên.

Cho phép whitelist commit/push C2+C2-R độc lập trên main đúng origin; không force, không gộp G7/audit chưa nghiệm thu. Source30file phải khớp ZIP C2 và overlay3file C2-R đã duyệt. Shared governance chỉ stage phần C2 trên HEAD; giữ nguyên phần G7 working tree.

Sau publish C2, cho phép triển khai trọn C3: nhà cung cấp và phiếu nhập nhiều dòng, nháp/xác nhận bất biến, nhập đúng một lần qua InventoryService C1, source_type=purchase/source_id từng purchase_line. Tiền dùng SaleMoney hiện có; transaction và product lock thứ tự ổn định; chỉ ghi MySQL test mnm_inventory_test_20261021. Database nghiệp vụ và KLCN/NoSQL/Word/Rose chỉ đọc. G7 PAUSED.

Quyền C3: Admin quản lý nhà cung cấp và lập/sửa/xác nhận phiếu nhập; User xem/tìm phiếu và nhà cung cấp, không ghi nhập kho. Quyền này giữ C1 Admin nhập kho, không mở quyền CRUD quản trị cho User. Admin chỉ sửa/xác nhận phiếu do mình lập; người khác đọc. FK RESTRICT nhà cung cấp có phiếu; phiếu xác nhận lưu snapshot tên/thông tin nhà cung cấp.

Kiểm tích hợp nhập→tồn→bán C2→lịch sử, validation/quyền/rollback/replay/đồng thời và hồi quy. Browser không callable thì ghi PENDING, không tạo ảnh giả. Bàn giao C3 DRAFT ZIP một lần, KHÔNG commit/push C3, không C4/G7.
