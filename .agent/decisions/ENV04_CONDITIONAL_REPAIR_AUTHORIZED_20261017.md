# ENV04 — Thy duyệt A+B có điều kiện

Ngày17/10/2026. Nguồn: attachment494169c9-6c9f-4f2a-9d2d-843f18458b97. Thy/GPT Web nghiệm thu ENV03 ZIP55/55 SHA/CRC, SHA9096cccea491fab2a302447ef4b9f068e80ec3485dad6e97e9efe96732b64da8; khóa hồ sơ ENV03 theo thời điểm.

Thy duyệt phục hồi đúng44 file/3837376byte trong docs/evidence/ENV03_REPAIR_MANIFEST.json, từ staging Oracle8.4.3. Cho phép tạo đúng thư mục còn thiếu và startup một instance sau shutdown an toàn bằng tài khoản hợp lệ, với đủ preflight/hash/exact-set/integrity/rollback. Không sửa bin/config/datadir/ACL/registry/firewall, không mở rộng whitelist hoặc ghi đè.

Điều kiện rõ trong prompt: “Nếu không có phiên quản trị hợp lệ hoặc không shutdown an toàn được, dừng trước khi chép file.” Không force-kill, password reset/guess/decrypt/skip-grant-tables/initialize. Không Laravel/migration/seed/DB/G4B/G5/commit/push. Dừng sau ENV04 để nghiệm thu, G4A vẫn BLOCKED.

Thy trả lời yêu cầu xác thực: “chưa có bạn tạo cho tui nhé sau dó ghi chsu vào bàn giao”. Ghi nhận yêu cầu tạo tài khoản và bàn giao. Chưa thể thực thi CREATE USER/GRANT khi không có phiên được xác thực với quyền phù hợp; yêu cầu này không cung cấp tài khoản quản trị hiện hữu và không cho phép bypass/khởi tạo lại/reset. Không tạo credential hoặc account giả trong báo cáo. Việc tạo tài khoản vẫn PENDING; khi có phiên hợp lệ sẽ cần xác định loại tài khoản và quyền tối thiểu trước thực thi, giữ DB khác ngoài phạm vi.

Checklist hiện hành .agent/ENV04_CHECKLIST.md; kết quả docs/ENV04_REPAIR_RESULT.md. Source runtime không đổi. ENV03 cũ giữ lịch sử chẩn đoán; quyết định ENV04 này ưu tiên về phạm vi quyền mới.
