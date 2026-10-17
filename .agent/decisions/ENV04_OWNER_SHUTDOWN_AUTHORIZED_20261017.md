# ENV04 — tiếp tục bằng quyền process owner hợp lệ

Nguồn: Thy bổ sung17/10/2026 yêu cầu tự xử lý blocker tối đa; kiểm tài khoản/session cấu hình, ưu tiên root nếu có cách xác thực được ủy quyền; kiểm cơ chế shutdown console/process owner được MySQL Windows hỗ trợ; không tạo account không cần thiết; không reset/guess/decrypt/skip-grant-tables/initialize/force-kill/security change. Nếu shutdown hợp lệ thì tiếp ENV04 đúng44file, dừng sau kiểm chứng, không DB/migration/seed/commit/push.

ALLOWED: Ngoài preflight/44file đã duyệt, kiểm quyền sở hữu process và quyền mở sự kiện Windows hiện hữu của đúng instance. MySQL source tag8.4.3: kill_mysql gọi SetEvent(hEventShutdown); create_shutdown_and_restart_thread tạo mysqld<monitor-pid>_shutdown; handler ghi normal shutdown và close_connections; monitor chờ child thoát. Đây là đường shutdown bình thường trong implementation Windows, không phải TerminateProcess/taskkill. Dùng OpenEvent với quyền hiện có, không tạo event hoặc sửa ACL. Chỉ sau receipt PID/port/log hoàn chỉnh và exitcode hợp lệ mới copy whitelist.

SOURCE OF TRUTH: Thy mới nhất → process ownership/commandline/binary/config/hash/event access thực tế → sql/mysqld.cc và sql/restart_monitor_win.cc tagmysql-8.4.3 → ENV03 manifest44file đã nghiệm thu. Sự kiện là cơ chế implementation trong source, không mô tả như CLI công khai của MySQL.

INVARIANTS: Không xác thực SQL bằng password đoán hoặc đọc/decrypt savedcredential. Quyền OS/event hiện có khác quyền account SQL; không suy từ shutdown OS rằng có quyền tạo DB/USER. Không tạo account mới nếu shutdown owner đã đủ. my.ini/bin/datadir và127file cũ không sửa trực tiếp; chỉ44file tronglib thêm mới; startup1instance hiện hữu cấu hình cũ.

ACCEPTANCE: Fresh preflight; process owner đúng user hiện tại, parent/child/config/binary đúng; event đúng tên theo monitor PID mở bằng ACL hiện có; SetEvent một lần; hai PID thoát/exit0, log mới có normal shutdown+shutdown complete và hai port đóng. Nếu không đạt thì dừng trướccopy. Saucopy exact-set44hash/171filetotal và127filecũ giữ; startup mới/version/port/moduleDLL/log component+ICU, protected verification vàZIP. Chưa có SQLsession không chặn ENV04 file/runtime khi dùng phương án owner hợp lệ; SQL/quyền/DB vẫn PENDING của G4A sau nghiệm thu.

Lựa chọn console được khảo sát: AttachConsole thành công, console chỉ có hai mysqld và helper probe. Không broadcast Ctrl+C vì monitor không có cùng handler server; ưu tiên đúng named event mà MySQL sử dụng để giữ monitor chờ server kết thúc bình thường. Probe chỉ mở event, chưa signal tại thời điểm decision.
