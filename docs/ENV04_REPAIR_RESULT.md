# ENV04_REPAIR_RESULT — phục hồi MySQL 8.4.3 A+B

Ngày17/10/2026. **ENV04: VERIFIED trong phạm vi phục hồi và kiểm môi trường; chờ Thy/GPT Web nghiệm thu.** Đã phục hồi đúng44 file/3.837.376byte và khởi động lại một instance. Chưa tự tiếp tục G4A, tạo DB, migration/seed hoặc commit/push.

## Kết quả

| Hạng mục | Kiểm trực tiếp |
|---|---|
| Nguồn | Package Oracle8.4.3 Windowsx64 và manifest nguyên byte từ ENV03 đã nghiệm thu |
| File thêm |1 DLL chính component_reference_cache.dll +43file ICU, đúng44đường dẫn/3.837.376byte; không DLL debug |
| Hash sau copy |44/44 SHA-256 và bytes khớp staging/entry archive/manifest; exact-set lib chỉ44file này |
| Bảo toàn installation |127file cũ cùng đường dẫn/bytes/SHA;0file ghi đè; tổng171file;5thư mục mới đúng whitelist |
| Shutdown | Một native Windows shutdown event đúng instance; monitor18852/server33712 đều exit0; log Normal shutdown/Shutdown complete; Windows xác nhận process thoát/3306+33060 không còn listener trướccopy |
| Startup mới | Một lần Start-Process hidden, binary/my.ini/datadir hiện hữu; monitor10032 → server4968; một instance |
| Port/version |3306/33060 thuộc4968; TCP127.0.0.1:3306 greeting8.4.3; không gửi auth/SQL |
| Component | Module component_reference_cache.dll thực sự nạp trong server4968, version8.4.3.0, đúng path/hash |
| ICU |43file corpus chính thức khớp hash; log startup mới không còn cảnh báo Missing data directory for ICU |
| Log | Không lỗi load component/structured ERROR mới; vẫn giữ cảnh báo secure-file-priv và CA self-signed ngoài phạm vi |
| Account/DB | Không cần tạo account để shutdown bằng owner;0auth attempt/0SQL, không tạo user/DB hoặc thay quyền |

Xem [44file trước/sau](evidence/ENV04_FILE_READBACK.json), [127file cũ](evidence/ENV04_INSTALLATION_INTEGRITY.json), [runtime/module](evidence/ENV04_RUNTIME_READBACK.json), [log đã lọc](evidence/ENV04_LOG_SUMMARY.json), [receipt](evidence/ENV04_ACTION_RECEIPTS.json), [verification cuối](evidence/ENV04_VERIFICATION.json).

## Giải quyết blocker bằng quyền hiện có

Kiểm cấu hình không secret của Laragon/my.ini và client CLI không tìm được phiên SQL được ủy quyền sẵn để dùng; không kết luận server không có tài khoản root/admin. Không đọc/decrypt savedcredential hoặc thử root/password rỗng/mặc định. Thy bổ sung yêu cầu kiểm cơ chế shutdown Windows/process owner và tránh account không cần thiết; đã áp dụng phạm vi mới này.

Source đúng tag8.4.3 cho thấy kill_mysql gọi SetEvent(hEventShutdown); sự kiện có tên theo PID monitor; thread xử lý sự kiện đóng kết nối và dừng server bình thường. Monitor chờ child thoát. Đã xác minh hai process thuộc user Windows hiện tại và OpenEvent được bằng ACL sẵn có; signal đúng mysqld18852_shutdown một lần. Không tạo event, sửa ACL, TerminateProcess/taskkill, reset hoặc bypass SQL. Đây là đường implementation Windows có trong source MySQL, không gọi nó là lệnh CLI công khai. Nguồn: [mysqld.cc tag8.4.3](https://github.com/mysql/mysql-server/blob/mysql-8.4.3/sql/mysqld.cc), [restart_monitor_win.cc](https://github.com/mysql/mysql-server/blob/mysql-8.4.3/sql/restart_monitor_win.cc).

Probe AttachConsole chỉ kiểm membership, không gửi Ctrl+C; console chỉ chứa hai mysqld và helper. Dùng sự kiện riêng để monitor tiếp tục chờ server shutdown. Quyền OS dùng cho shutdown không chứng minh quyền SQL CREATE USER/SHUTDOWN/provision database. Không tạo account SHUTDOWN mới; yêu cầu trước của Thy không còn cần cho ENV04 theo chỉ dẫn mới nhất.

Helper shutdown ban đầu kiểm TCP bằng kết nối và gặp timeout nên đánh dấu gate chưa đủ, mặc dù exit0 và log đã complete. Đã giữ receipt gốc, không signal lần hai hoặc force-kill. Readback Windows độc lập xác nhận không còn server process/listener3306/33060 trước khi copy. Gate được xác minh bằng bằng chứng này; không gọi timeout là connection-refused. Chi tiết trong ACTION_RECEIPTS và raw local logs.

## Nguồn file, backup và rollback

Archive Oracle259751071byte, SHA-256 `e401d0420486d91bd14eda7b6b449498007863ca340991e529adff4787352e7e`;44 staging và từng archive entry rehash lại trước thao tác, không tải phiên bản khác. Manifest ENV03 nguyên byte so với ZIP đã nghiệm thu. Disclosure GPG archived key hết hạn từ ENV03 giữ nguyên; không mô tả key còn hạn.

Đã lưu private backup my.ini, manifest, inventory127file và log trước thao tác tại `.agent/private/env04/before-owner/`. Không sao chép datadir đang chạy hoặc tuyên bố có backup dữ liệu nhất quán. File khôi phục được tạo bằng chế độ exclusive; không giải nén toàn bộ package đè installation. Receipt ghi từng file/thư mục được thêm; file cũ không đổi hash.

Không cần rollback vì kiểm bản vá đạt. Nếu được yêu cầu rollback sau này: phải kiểm quyền/cơ chế shutdown hợp lệ của instance mới, dừng sạch và đối chiếu receipt/hash từng file đã thêm trước khi chỉ gỡ đúng44file. Chỉ gỡ thư mục do phiên này tạo khi trống/đường dẫn hợp lệ, không xóa đệ quy hoặc khôi phục datadir cũ. Không replay event theo PID18852: instance này đã thoát; PID mới10032/4968 cần xác minh lại. Không dùng helper restore/start cũ để chạy lần hai.

## Phạm vi kiểm và giới hạn

Structural: exact-set44file/bytes/hash,5directory,127file cũ/171file sau, source/runtime68file, Git protected. Semantic: đối chiếu manifest ENV03/package8.4.3, nguồn shutdown Windows và receipt hành vi thật; không suy quyền SQL từ quyền OS. Runtime: clean shutdown/exit0, startup mới/PID/port/version, module DLL nạp thật và cảnh báo ICU biến mất. Verification cuối ghi thêm readback Git/source và ZIP.

Chưa có SQLsession, nên @@plugin_dir/@@datadir qua SQL, quyền account, ownership/tồn tại database mnm_vat_tu_nong_nghiep và SQLregexp test vẫn chưa kiểm. ICU được xác minh ở phạm vi corpus/hash và startup check, không tuyên bố mọi chức năng MySQL/regexp đều đã test. InnoDB initialization trong log là khởi động storage engine trên datadir hiện hữu; không chạy mysqld --initialize.

Không agent sửa trực tiếp datadir/config/bin/ACL/registry/firewall hoặc database; MySQL tự ghi nội bộ khi shutdown/startup bình thường. Không chứng nhận mọi byte datadir giữ nguyên. KLCN/NoSQL được đối chiếu HEAD/status/metadata Git theo baseline, không full-hash toàn workspace; KLCN giữ11dòng dirty Mobile có trước, NoSQL clean. Không kết nối PostgreSQL/MongoDB. Source runtime68file giữ hash, chỉ hồ sơ/helper local Mã nguồn mở thay đổi.

## Bàn giao và điểm dừng

ENV03/G1/G2/G3 LOCKED; ENV04 chờ Thy/GPT Web nghiệm thu kết quả sửa môi trường. G4A vẫn chưa tiếp tục; quyền/DB riêng/dependency/boot/migrations/Eloquent/seeder và QA MySQL còn TODO. Không tự mở G4B/G5. Chưa commit/push; local main21c8842027c510ec4db0d09b859ccd522f8785de, remote cùng SHA readback ở checkpoint trước. ENV04 chưa có trên GitHub; GPT Web đọc ZIP hồ sơ để nghiệm thu.

Đọc đầu tiên ENV04_REPAIR_RESULT → ENV04_VERIFICATION → ENV04_FILE_READBACK/INSTALLATION_INTEGRITY/ACTION_RECEIPTS/RUNTIME_READBACK/LOG_SUMMARY. Hồ sơ JSON có đầy đủ nguồn/đích/hash44file; không kèm DLL/archive/rawlog/credential trong ZIP. Sau nghiệm thu môi trường mới xử lý xác thực/quyền hợp lệ cho database riêng theo phạm vi G4A được duyệt; chưa cần Thy tạo account cho ENV04.
