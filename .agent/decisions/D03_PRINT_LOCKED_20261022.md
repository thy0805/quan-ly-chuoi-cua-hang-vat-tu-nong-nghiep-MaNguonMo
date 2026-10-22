# D03 — quyết định nghiệm thu và khóa bản vá in

Ngày: 22/10/2026. Baseline main: `2399ec52f26af7a3212b7fb1659abbf6dccd5f02`.

Thy/GPT Web đã mở gói Final Print, đối chiếu 41/41 payload SHA-256, CRC và render lại sáu trang từ bốn PDF. Quyết định của Thy: **D03 PRINT QA ACCEPTED**, cho phép khóa và công bố riêng D03 bằng commit/push lên main. P01–P05 PASS.

Phạm vi khóa gồm đúng ba file source: `public/assets/sales-print.css`, `resources/views/sales/print.blade.php`, `resources/views/purchases/print.blade.php`. Ba file giữ nguyên byte đã nghiệm thu; không chỉnh tiếp trong lượt công bố. Chỉ thêm quyết định này, [kết quả nghiệm thu](../../docs/D03_LOCK_RESULT.md) và [evidence đã lọc](../../docs/evidence/D03_PUBLICATION_VERIFICATION_20261022.json).

Việc công bố chỉ hoàn tất khi commit chứa đúng whitelist, kiểm an toàn đạt, push không force và remote SHA/nội dung được đọc lại khớp local. Commit công bố được xác định bằng lịch sử Git của tài liệu này; biên bản local lưu SHA đầy đủ và kết quả readback sau push.

Quyết định này ưu tiên hơn các ghi chú DRAFT/chờ nghiệm thu/chưa cấp quyền push trước ngày nghiệm thu, chỉ trong phạm vi D03. Giữ nguyên lịch sử và evidence cũ. C1/C2/C3 Functional và D01/D02 tiếp tục LOCKED; Visual Responsive C2/C3 được chấp nhận trong phạm vi đã kiểm.

**RUNTIME-8092 PENDING** là việc vận hành riêng cần kiểm trước demo. Không sửa/khởi động lại server, không kill tiến trình, không đổi cấu hình hoặc kết nối database trong lượt công bố. G7 vẫn PAUSED. Không sửa KLCN, NoSQL, Word, Rational Rose hoặc mở chức năng mới.
