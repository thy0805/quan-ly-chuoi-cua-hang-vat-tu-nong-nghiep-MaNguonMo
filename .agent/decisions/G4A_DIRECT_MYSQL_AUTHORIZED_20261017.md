# Thy duyệt chạy trực tiếp MySQL hiện hữu — 17/10/2026

Thy cho phép Codex tự mở Laragon, bật riêng MySQL và tiếp tục G4A. Công cụ Computer Use đã mở Laragon nhưng không lấy được cửa sổ điều khiển; máy không có Windows Service MySQL. Thy sau đó trả lời “Cho phép chạy trực tiếp cấu hình hiện hữu” và xác nhận bằng prompt riêng.

ALLOWED: Chạy đúng mysqld.exe MySQL8.4.3 thuộc Laragon bằng my.ini và datadir hiện hữu sau kiểm process/phiên bản/đường dẫn. Một instance duy nhất; kiểm log/process/TCP và phiên quản trị hợp lệ, rồi chỉ provision/ghi mnm_vat_tu_nong_nghiep đúng G4A.

FORBIDDEN: --initialize, tạo datadir mới, cài lại/reset password, sửa my.ini/registry/firewall hoặc PG/Mongo, xóa/ghi đè dữ liệu sẵn có. Không đọc/giải mã/dò mật khẩu; khi thiếu phiên quản trị phải để Thy đăng nhập cục bộ. Không commit/push hoặc mở G4B/G5.

SOURCE OF TRUTH: Prompt Thy ngày17/10; executable/config/datadir và process/service đọc trực tiếp; ERD/G2 giữ nguyên.

INVARIANTS: Không instance cạnh tranh; config giữ hash; MySQL8.4.x thực tế; chỉ database riêng của môn học được ghi; một Admin và validation/constraint theo G2.

ACCEPTANCE: Startup/readback thực tế có evidence. Lỗi cấu hình/quyền/khóa datadir/khác phiên bản dữ liệu phải HARD STOP, không sửa để ép chạy. Không khởi động được hoặc chưa có phiên quản trị hợp lệ thì ghi blocker. Kết thúc G4A dừng nghiệm thu.
