# D03 — nghiệm thu bố cục in A4

Ngày: 22/10/2026. Baseline: `2399ec52f26af7a3212b7fb1659abbf6dccd5f02`.

Thy/GPT Web đã nghiệm thu **D03 PRINT QA ACCEPTED; P01–P05 PASS** và cho phép công bố riêng bản vá. Lỗi cột Đơn vị và tiêu đề Số lượng ngắt vụn được xử lý bằng CSS in với độ rộng năm cột 38% / 11% / 13% / 18% / 20%, quy tắc xuống dòng theo cột và nhóm tổng tiền/ghi chú. Hai Blade chỉ thêm khối `invoice-summary`; nội dung chứng từ và biểu thức nghiệp vụ giữ nguyên.

## Phạm vi commit

- [CSS in](../public/assets/sales-print.css).
- [Blade hóa đơn bán](../resources/views/sales/print.blade.php).
- [Blade phiếu nhập](../resources/views/purchases/print.blade.php).
- Báo cáo này, [quyết định nghiệm thu](../.agent/decisions/D03_PRINT_LOCKED_20261022.md), [evidence công khai](evidence/D03_PUBLICATION_VERIFICATION_20261022.json).

Không gom G7/audit và các thay đổi tài liệu có từ trước. ZIP, PDF, ảnh lớn, logs, credentials và dữ liệu database không thuộc whitelist public.

## Kiểm in đã nghiệm thu

| Case | Chứng từ | Số dòng | Tổng tiền VND | Trang A4 dọc | Kết quả |
|---|---|---:|---:|---:|---|
| P01 | BH95 | 2 | 67.500 | 1 | PASS |
| P02 | PN48 | 2 | 420.000 | 1 | PASS |
| P03 | BH99 | 25 | 25.000 | 2 | PASS |
| P04 | PN51 | 25 | 25.000 | 2 | PASS |
| P05 | Mở/render và rà mọi trang của bốn PDF | — | — | 6 tổng cộng | PASS |

Đã kiểm trực quan đủ sáu trang: tiêu đề và Kilôgam đọc được, không mất dòng/cột, số tiền không đè nhau, tiêu đề bảng lặp ở trang tiếp theo, tổng tiền và ghi chú ở trang cuối; PN51 không còn trang gần trống riêng cho ghi chú. Không có sidebar, nút thao tác hoặc header/footer/URL trình duyệt trong PDF. Hai chứng từ ngắn vẫn đúng sau vá. MediaBox từng trang tương ứng A4 dọc.

Gói Thy đã nghiệm thu: `MNM_NHOM6_D03_FINAL_PRINT_RECEIVED_20261022.zip`, SHA-256 `4158570a1b200a03ed04bb08b5dcb68ba5eca5d6d0b62c0f9cc096ca31bd679a`; CRC PASS, 41/41 payload SHA-256 khớp. Hash riêng bốn PDF và ba file source nằm trong evidence công khai để GPT Web đối chiếu. Giữ gói cũ nguyên trạng, không tải ZIP lớn vào Git.

Trước nghiệm thu in, đã có tám lượt render Blade và đối chiếu toàn bộ chữ của bốn chứng từ trước/sau vá. Hồ sơ nhận PDF xác minh 177 file source sau bản vá và snapshot dữ liệu/DDL năm bảng nghiệp vụ cùng 12 bảng test khớp trước/sau lượt in; bộ đếm AUTO_INCREMENT được loại khỏi so sánh theo quy tắc đã ghi. Đây là bằng chứng của lượt kiểm trước, không phải kết quả chạy SQL mới khi công bố.

## Giới hạn và điểm tiếp tục

Nghiệm thu áp dụng cho bốn chứng từ đã kiểm. Không khẳng định mọi máy in hoặc dữ liệu khác đều giống kết quả này. Scale/lề hộp thoại hệ điều hành chưa được đo độc lập; nội dung và MediaBox PDF thật đã được kiểm.

**RUNTIME-8092 PENDING:** Web đôi lúc timeout dù đã có phiên đăng nhập và xuất PDF thành công. Khóa D03 không chứng nhận server ổn định. Lượt công bố không thay source đã nghiệm thu, không kết nối database, chạy migration/seed, kiểm lại nghiệp vụ hoặc thay dịch vụ/config. G7 vẫn PAUSED; dừng sau commit/push/readback riêng D03. SHA công bố được tra trong lịch sử Git của tài liệu này và biên bản local sau push.
