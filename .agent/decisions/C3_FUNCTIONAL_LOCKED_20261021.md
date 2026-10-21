# C3 FUNCTIONAL ACCEPTED / LOCKED — 21/10/2026

Nguồn quyền: Thy gửi attachment cce85618-0805-4db4-850e-68b87f378b1d, nghiệm thu chức năng C3 và cho phép commit/push riêng lên main. Quyết định này thay trạng thái DRAFT/chưa được push trong hồ sơ C3 cũ; không sửa lại bằng chứng đã khóa.

Source được duyệt: 36 file trong ZIP `MNM_NHOM6_C3_DRAFT_20261021.zip`, SHA-256 `549bd1622801fdbe1c053de0f67cca47f97b074602703f4034a884041236588e`; baseline C2 `2d5d323e5fa629508a83aa5f542081343a0f7902`. Đủ 50 payload/CRC và 42 bản source/tài liệu trong ZIP được đối chiếu với file hiện hành trước công bố.

C1 ACCEPTED/LOCKED; C2 và C3 FUNCTIONAL ACCEPTED/LOCKED. Chức năng C3 gồm CRUD nhà cung cấp, phiếu nhập nhiều dòng, lưu/sửa nháp, xác nhận tăng kho đúng một lần qua InventoryService, tìm/lọc/phân trang, quyền Admin ghi/User xem. Giữ transaction, khóa sản phẩm, version/token, tiền nguyên cent, bất biến phiếu đã xác nhận và FK RESTRICT.

Evidence chức năng được chấp nhận: 75 tests/1.277 assertions; sáu ca đồng thời/12 worker; 48 HTTP request; PN48 → BH95 → tồn A18/B7. Đây là kết quả kiểm của checkpoint C3 trước khi khóa, không phải một lượt chạy test mới khi công bố Git.

Browser responsive 1366/768/320 và Print Preview/PDF C2+C3 vẫn PENDING. Không coi HTTP hoặc DOM giả lập là bằng chứng bố cục. C1 đã được nghiệm thu giao diện theo hồ sơ riêng.

Chỉ được stage whitelist C3 và hồ sơ liên quan; tài liệu điều phối trộn G7 chỉ stage header mới trên nội dung HEAD. Giữ nguyên phần G7/audit trong working tree. Đúng origin duy nhất của môn Mã nguồn mở; không force push, không gộp lịch sử KLCN/NoSQL.

Database nghiệp vụ chưa migration/seed các cụm mới; môi trường đã kiểm là MySQL test cô lập. Lượt khóa chỉ đọc snapshot database nghiệp vụ, không chạy test ghi dữ liệu. KLCN/NoSQL/Word/Rose và accepted artifact được bảo toàn.

Sau khi push/readback và ZIP evidence đạt: dừng phát triển ba cụm. Không mở C4/G7/Word/slide hoặc triển khai database. Bước tiếp theo chỉ là Visual/Print QA chung khi Thy giao và có công cụ phù hợp.
