> Hồ sơ lịch sử G1 đã được Thy nghiệm thu về clone/audit ngày 17/10/2026. Số đếm/điểm dừng phản ánh G1; trạng thái hiện hành ở G2_DECISIONS.md, G3_ISOLATION_RESULT.md và README.md.

# Đọc trước khi nghiệm thu ZIP — Nhóm 6

Gói: `MNM_NHOM6_CLONE_AUDIT_20261017.zip`.

Thy yêu cầu từ checkpoint này mỗi lần bàn giao gom hồ sơ vào một ZIP, giúp gửi GPT Web trong giới hạn tối đa 10 file. Chỉ cần tải lên ZIP này cho lượt nghiệm thu hồ sơ.

## Thứ tự đọc

1. `docs/BIEN_BAN_GIAI_DOAN_1.md`: phạm vi đã làm và giới hạn kiểm chứng.
2. `docs/AUDIT_KLCN.md`, `docs/REUSED_FILES.md`: kết quả audit và nguồn gốc file.
3. `docs/CONVERSION_MAPPING.md`: mapping và DEC01–06 cần duyệt.
4. `docs/ARCHITECTURE.md`, `docs/ERD_MYSQL.md`, `docs/FUNCTIONS_USE_CASES.md`, `docs/IMPLEMENTATION_PLAN.md`: thiết kế dự kiến và kế hoạch.
5. `context.md`, `.agent/PLAN.md`, `.agent/CLONE_CHECKLIST.md`: điểm tiếp tục và trạng thái.
6. `.agent/logs/clone/`: evidence clone được chọn; `DANH_MUC_GOI_SHA256.json`: danh mục và SHA-256 từng file trong gói.

## Phạm vi gói

Gồm tài liệu Markdown hiện hành, nguồn sơ đồ UML, quy tắc dự án, quyết định clone trước và các evidence clone: manifest, verification, đối chiếu nguồn, PHP syntax, provenance, đọc lại tài liệu, Git status của bản mới.

Đây là ZIP hồ sơ, không chứa toàn bộ source Laravel, `.git`, `.env`, dependency, dữ liệu database, log runtime hoặc scaffold lịch sử. Các đường dẫn source trong manifest và link đến code trên máy là thông tin tham chiếu; không phải file được đính kèm trong ZIP. Snapshot đầy đủ trước/sau và archive lịch sử vẫn ở workspace.

Source thật: `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`.

Các số COPIED102 / ADAPTED2 / NEW46 và gate17/17 trong evidence clone phản ánh checkpoint clone trước khi bổ sung hướng dẫn, helper và quy tắc ZIP. Việc đóng gói không chạy lại ứng dụng hoặc database, không xác nhận tương thích MySQL. Manifest SHA trong ZIP phản ánh đúng phiên bản tài liệu được đóng gói; hash tài liệu trong evidence clone cũ thuộc lượt kiểm trước đó.

## Điểm dừng

Chưa triển khai chuyển đổi MySQL/Eloquent/Blade/Bootstrap, chưa chạy migration/seed hoặc ghi database. Chờ Thy/GPT Web nghiệm thu clone và chốt DEC01–06; đóng gói không mở quyền triển khai tiếp.
