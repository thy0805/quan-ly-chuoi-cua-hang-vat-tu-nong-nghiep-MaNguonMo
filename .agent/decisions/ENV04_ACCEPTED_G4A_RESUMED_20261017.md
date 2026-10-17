# ENV04 nghiệm thu; mở lại gate G4A — 17/10/2026

Nguồn: Thy gửi attachment `673256cd-07a6-4b63-99d2-a030f578aa80`. ENV04 ACCEPTED / LOCKED trong phạm vi phục hồi 44 file và kiểm startup MySQL 8.4.3. ZIP đã nghiệm thu có SHA-256 `5d7362d162c352faac0d16059b0836f067cf5ada9e861da3efec085dc812be02`; giữ nguyên ZIP, báo cáo, checklist và evidence ENV04. Quyết định này thay trạng thái chờ nghiệm thu ENV04 trong tài liệu điều phối; không viết lại bằng chứng lịch sử.

ALLOWED: Kiểm đọc lại binary/config/datadir khai báo/process/port/log, 68 file runtime và Git ba dự án; tìm phiên hoặc cấu hình xác thực SQL hợp lệ hiện có bằng thông tin không bí mật. Ghi hồ sơ G4A mới, checklist và ZIP nhỏ. Chỉ sau khi có phiên SQL hợp lệ mới kiểm danh tính/quyền/database đích và thực hiện G4A theo G2/G4A_AUTHORIZED: dependencies từ lock, guard độc lập, bốn bảng, migration/model/seeder synthetic và QA MySQL thật.

FORBIDDEN: Đọc/giải mã mật khẩu lưu, đoán root/password, reset/bypass authentication; suy quyền SQL từ Windows owner; sửa/replay ENV04, restart không cần thiết; sửa source/database KLCN hoặc NoSQL, Flutter/Word/Rose; DROP/ghi đè DB chưa chứng minh phạm vi; G4B/G5; commit/push G4A.

SOURCE OF TRUTH: Thy mới nhất; checkpoint `21c8842027c510ec4db0d09b859ccd522f8785de`; G2_DECISIONS, ERD_MYSQL; ENV04 đã khóa; baseline 68 file và protected Git.

INVARIANTS: MySQL một instance 8.4.3, đúng binary/my.ini/datadir hiện hữu; installation 127 file cũ + 44 file phục hồi; không ghi DB trước xác thực và kiểm quyền; database duy nhất được provision là `mnm_vat_tu_nong_nghiep`; bốn bảng/ba FK/một Admin và quy tắc G2 giữ nguyên.

ACCEPTANCE: Readback hiện hành có thời điểm; protected Git và 68 hash khớp; ENV04 artifact không đổi; SQL identity/quyền/DB phải đọc bằng phiên hợp lệ trước DDL. Nếu không có xác thực hợp lệ, dừng tại blocker, không đánh dấu G4A runtime VERIFIED. ZIP mới phải kiểm CRC, exact-set và SHA từng thành viên.
