# G4A — DDL trigger bằng phiên quản trị đã xác thực

SQL AUTH02 xác nhận MySQL8.4.3, root@localhost có quyền CREATE/CREATE USER/GRANT; target DB absent. Đã provision database mới và hai account schema-only. Readback server: log_bin=1, log_bin_trust_function_creators=0. Theo [MySQL8.4 CREATE TRIGGER](https://dev.mysql.com/doc/refman/8.4/en/create-trigger.html), tạo trigger trong cấu hình này có thể cần quyền quản trị ngoài TRIGGER.

Không thay config/global variable hoặc cấp SUPER/quyền server cho account mới. Giữ phiên root PDO trong RAM từ lần Thy tự nhập, chỉ dùng nó provision đúng hai trigger INSERT/UPDATE trong database môn học và rollback chúng khi fixture đã kiểm. DEFINER là account migration riêng, không phải root. Đây là DDL thuộc database đã được Thy cho phép trong G4A, không phải quyền sửa cấu hình server hoặc thay nghiệp vụ.

Migration Laravel vẫn chạy bằng account migration schema-only. MigrationTriggers có chế độ bridge được kích hoạt rõ bằng token ephemeral; gửi action create/drop, không gửi SQL tùy ý. Local runner trong phiên Node xử lý đúng hai câu DDL hardcoded, schema-qualified bằng PDO root đã xác thực, rồi migration đọc lại nội dung/count trigger. Không có fallback root trong connection Laravel; runtime account vẫn CRUD-only.

Bridge chỉ được phục vụ trong lúc agent trực tiếp chạy migration/rollback. Không mở cổng mạng, không credential root trên disk, không daemon/scheduler. Request/response/token thuộc .agent/private/g4a và không public. Mất phiên root hoặc không có bridge response thì migration fail closed; không được bỏ trigger để báo thành công.

Với môi trường khác, caller có quyền phù hợp có thể chạy migration thông thường; tại môi trường hiện tại, chuẩn bị phiên quản trị như AUTH02 và dùng runner cho hai trigger. Chưa chứng nhận cách triển khai trên máy khác. Rollback chỉ sau exact-set/schema/row hashes chứng minh fixture mới; không DROP database.
