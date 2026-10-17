# AUTH02 — đăng nhập cục bộ một lần

Nguồn: Thy nghiệm thu ZIP AUTH01 21/21 hash/CRC và yêu cầu chuẩn bị cách nhập mật khẩu trực tiếp trên máy, giữ ENV04 LOCKED; sau xác thực hợp lệ kiểm SQL chỉ đọc rồi tiếp G4A đúng phạm vi.

ALLOWED: Xác minh mysql.exe/PHP pdo_mysql và công cụ Laragon hiện có; tạo helper trong .agent/scripts; mở cửa sổ nhập tài khoản/mật khẩu cục bộ. Truyền credential qua anonymous pipe giữa hai process cục bộ, không qua argv/environment/file/log/chat. Giữ PDO connection trong process để agent sử dụng sau xác thực. Truy vấn preflight chỉ đọc USER/CURRENT_USER/VERSION/datadir/plugin_dir/SHOW GRANTS/database đích. G4A chỉ tiếp khi identity/quyền/phạm vi đạt.

FORBIDDEN: Đọc/giải mã credential lưu, tự đoán password hoặc tự gửi password rỗng; retry tự động; reset/bypass; sửa/restart/repair ENV04; tự tạo/nâng quyền account trước phiên quản trị được ủy quyền; ghi DB khác; G4B/G5 hoặc commit/push.

SOURCE OF TRUTH: Thy mới nhất; G2_DECISIONS/ERD_MYSQL; G4A_AUTHORIZED; ENV04 đã khóa; source checkpoint21c8842027c510ec4db0d09b859ccd522f8785de.

INVARIANTS: Không credential quản trị trên disk; một attempt chỉ khi Thy bấm đăng nhập với giá trị tự nhập; kết nối cố định127.0.0.1:3306; thông tin từ SQL không đồng nghĩa quyền/DB phù hợp nếu chưa đọc kết quả. Không account/DDL tự động khi login.

ACCEPTANCE: Helper syntax/contract được kiểm; cửa sổ thật đang chờ nhập hoặc receipt xác thực thật. Không coi chuẩn bị helper là SQL đã thành công. Khi auth đạt kiểm identity/grants/database; đề xuất account ứng dụng chỉ quyền schema mnm_vat_tu_nong_nghiep, password riêng ở config ignored, không lưu password quản trị. Nếu auth thất bại, dừng một lần và báo mã lỗi đã lọc.
