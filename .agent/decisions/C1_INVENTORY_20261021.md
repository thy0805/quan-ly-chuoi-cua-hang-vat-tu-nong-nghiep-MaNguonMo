# Quyết định mở rộng tồn kho — 21/10/2026

Thy giao trọn Cụm 1 theo attachment 5b460635-f4dc-4aee-b351-1516dbe59ef0. Quyền này thay giới hạn không sửa source/không kho của task G7 trước, không hồi tố thay bằng chứng G1–G6. Cụm 2/3 chưa được giao triển khai. Không commit/push và không sửa Word/slide.

Chỉ sửa repository Mã nguồn mở. Giữ database nghiệp vụ chỉ đọc; provision schema test mới mnm_inventory_test_20261021 và hai account chỉ có quyền trên schema đó. MySQL hiện hữu đã được xác minh binary/config/datadir và khởi động theo quyền trước, không initialize/reset/sửa cấu hình hoặc phục hồi ENV04 lại.

Chọn 2 bảng riêng, ID signed BIGINT khớp source; DECIMAL(15,3) và arithmetic integer milli-unit; một stock/product, chưa giao dịch hiển thị0 không tạo stock. Product khóa trước stock để bảo vệ lần nhập đầu. Mọi lần ghi dùng chung InventoryService trong transaction. Token UUID và UNIQUE nguồn bảo vệ thao tác lặp; source_id tương lai là định danh dòng chứng từ. Lịch sử bất biến bằng model/trigger, FK RESTRICT giữ vật tư/actor; không đổi đơn vị tính sau giao dịch.

User chỉ xem, Admin nhập/xuất; service đọc lại actor, không tin role trên object cũ. Test profile chỉ chấp nhận ba marker, database/account/host/datadir cố định, tách cookie/session/cache/view. TestCase từ chối database nghiệp vụ. Không thay dependencies hoặc bốn migration cũ.

MySQL binlog yêu cầu quyền DDL cao hơn khi CREATE TRIGGER: chỉ bốn câu CREATE TRIGGER tên/bảng đã giới hạn trên schema test được thực thi qua phiên quản trị hợp lệ. Không cấp global/SUPER, không đổi log_bin_trust_function_creators hoặc cấu hình. Giữ log lần thử chưa đạt, không sửa thành PASS.

Kết quả chức năng: 44/44 tests,828 assertions;4 race cases/8 workers;TCP demo20-5=15/2movement; profile/schema/ledger được đọc lại. Ảnh và responsive visual QA PENDING do công cụ browser disabled. VERIFIED kỹ thuật theo evidence không đồng nghĩa LOCKED/nghiệm thu; chưa migrate kho vào database nghiệp vụ. Xem docs/C1_RESULT.md và docs/evidence/C1_VERIFICATION.json.
