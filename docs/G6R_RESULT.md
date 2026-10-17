# G6-R DRAFT — sửa lệnh PowerShell khởi động demo

Ngày 17/10/2026. Nguồn: Thy/GPT Web đã đọc ZIP G6, đối chiếu GitHub G5 và đánh giá nội dung/sơ đồ phù hợp; còn blocker lệnh khởi động. G6 vẫn DRAFT, chưa nghiệm thu cuối, chưa stage/commit/push. Source G5 giữ commit **68770c0d2dc8b0e743a69564112be41eed54dd69**.

## Sửa tối thiểu

Chỉ sửa block PowerShell trong [DEMO_KHOI_DONG](g6/DEMO_KHOI_DONG.md): dùng dấu `/` cho đường dẫn public, PHP executable và framework router. Bản gốc mất các dấu phân cách, đồng thời có ba byte control 0x08, 0x0B, 0x0C. Nội dung ngoài block, bảy Markdown G6 còn lại, trang xem, hai sơ đồ/nguồn và ảnh giữ nguyên byte.

Nguyên nhân: đường dẫn Windows trong chuỗi sinh tài liệu bị diễn giải escape. Lượt G6 trước kiểm helper bằng đường dẫn đúng riêng, chưa chạy nguyên lệnh bị hỏng từ Markdown. Kết quả đó không đủ chứng minh lệnh tài liệu. G6-R thay bằng kiểm trích trực tiếp block, không lấy việc helper cũ chạy làm bằng chứng.

ZIP G6 gốc giữ nguyên SHA-256 **55d1f9ef7063f7835d8af7058be4da40ab10a681d97b20c325b07c982c649338**; không ghi đè lịch sử. ZIP G6-R mới là gói hiệu chỉnh để nghiệm thu.

## Kiểm chứng

- [Startup G6-R](evidence/G6R_STARTUP_CHECK.json): PowerShell Parser không lỗi; ba đường dẫn tồn tại; thực thi block lấy từ Markdown bằng Windows PowerShell, chỉ thay literal cổng8086 thành cổng trống64134. GET login và4assets đều200, bốn asset khớp byte source; đúng PHP process; server tạm dừng/cổng đóng. Private .env nạp bình thường, không chèn credential/đổi guard.
- [Verification G6-R](evidence/G6R_VERIFICATION.json): quét toàn bộ8Markdown G6, control/fence/link và bảo toàn so với ZIP gốc; danh sách file sửa/thêm và hash; fixture trước/sau; source/protected theo [G6_VERIFICATION](evidence/G6_VERIFICATION.json).
- G6_STARTUP_CHECK và G6_VERIFICATION hiện hành cập nhật theo kiểm trực tiếp G6-R; bằng chứng G6 trước sửa vẫn trong ZIP gốc. Hồi quy31/674 là checkpoint trước publication G5, không chạy lại trong G6-R vì không sửa source.
- Không migration/seed/ghi nghiệp vụ hoặc restart MySQL. Runtime Laravel có thể ghi session/view/cache riêng khi GET; dữ liệu nghiệp vụ/schema/ledger giữ baseline3users/5categories/3units/4products.

## File và bàn giao

File nội dung sửa: docs/g6/DEMO_KHOI_DONG.md. File đầu mối/evidence/governance được cập nhật hoặc thêm để phản ánh G6-R; danh sách đầy đủ tại G6R_VERIFICATION, so với ZIP G6 gốc. Helper/log dưới .agent/scripts và .agent/logs chỉ dùng tại máy, không đóng vào ZIP.

Gói **MNM_NHOM6_G6R_DRAFT_20261017.zip**: đọc BAT_DAU_DOC.md rồi tài liệu này. Có MANIFEST_SHA256.json; bàn giao chỉ khi receipt .agent/logs/g6r/package-final.json PASS CRC/exact-set/từng SHA/readback. SHA toàn ZIP giao ngoài gói.

Dừng sau G6-R để Thy/GPT Web nghiệm thu lần cuối. Không khóa G6, commit/push hoặc mở module mới. Phân công thực tế, rubric gốc, báo cáo nộp/slide và buổi demo thật vẫn chờ như G6; không coi startup GET là diễn tập nghiệp vụ hoàn chỉnh.
