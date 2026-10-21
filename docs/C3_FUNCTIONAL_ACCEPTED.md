# C3 đã nghiệm thu chức năng — Nhóm 6

Trạng thái hiện hành ngày 21/10/2026: **C3 FUNCTIONAL ACCEPTED / LOCKED** theo [quyết định khóa](../.agent/decisions/C3_FUNCTIONAL_LOCKED_20261021.md). C1 đã khóa; C2 đã khóa chức năng. Dừng phát triển ba cụm sau công bố C3.

## Nguồn và giới hạn nghiệm thu

Source C3 gồm 36 file: 29 file mới và bảy điểm tích hợp với source hiện hành. Không tạo project mới. ZIP C3 được Thy/GPT Web rà và được kiểm lại manifest 50/50 payload, CRC, SHA-256; 42 file source/tài liệu hiện tại khớp nguyên byte với ZIP. [Whitelist và evidence gốc](evidence/C3_VERIFICATION.json).

ZIP gốc: `MNM_NHOM6_C3_DRAFT_20261021.zip`; SHA-256 `549bd1622801fdbe1c053de0f67cca47f97b074602703f4034a884041236588e`. Baseline C2 là `2d5d323e5fa629508a83aa5f542081343a0f7902`. Hồ sơ [C3_RESULT](C3_RESULT.md), thư mục `docs/c3`, evidence gốc và checklist triển khai C3 giữ nguyên như bản DRAFT đã nộp; các câu “chưa nghiệm thu/chưa push” ở đó là lịch sử tại thời điểm đóng ZIP. Quyết định khóa mới có quyền ưu tiên về trạng thái hiện hành.

| Cụm | Phạm vi đã nghiệm thu | Trạng thái |
|---|---|---|
| C1 | Tồn kho, nhập/xuất thủ công, lịch sử | ACCEPTED / LOCKED |
| C2 | Bán tại quầy, hóa đơn nội bộ, cập nhật tồn | FUNCTIONAL ACCEPTED / LOCKED |
| C3 | Nhà cung cấp, phiếu nhập, cập nhật tồn dùng chung C1 | FUNCTIONAL ACCEPTED / LOCKED |
| C2 + C3 | Responsive 1366/768/320, Print Preview và PDF | PENDING |
| Database nghiệp vụ | Triển khai migration/seed các cụm mới | Chưa thực hiện; cần quyền riêng |

## Kết quả chức năng đã có

75/75 tests và 1.277 assertions; sáu ca đồng thời với 12 PHP worker; 48 HTTP request, cùng 11 request kiểm launcher. Chuỗi PN48 nhập 420000.00 → BH95 bán 67500.00 → tồn A18.000/B7.000. Schema MySQL test có 11 bảng nghiệp vụ, 13 FK, 23 CHECK, 14 trigger. Chi tiết và giới hạn ở evidence gốc. Lượt công bố không chạy lại kiểm thử ghi dữ liệu vì source khớp bản đã nghiệm thu.

## Mở và trình diễn trên Windows

Theo [hướng dẫn chạy đã kiểm](c3/CHAY_VA_DEMO.md), dùng server test hiện hữu tại `http://127.0.0.1:8092/login` nếu còn chạy; không khởi động thêm instance trên cổng bận. Profile test và credentials giữ riêng trên máy, không có trong Git/ZIP. Kiểm trạng thái server trước khi dùng, không suy đang chạy từ tài liệu lịch sử.

Admin quản lý tại `/suppliers` và `/purchases`; User chỉ đọc hai phần này. Phiếu mẫu `/purchases/48`, bán hàng `/sales/95`, trang in tương ứng thêm `/print`. Không cần tạo thêm dữ liệu để xem chuỗi mẫu. Không dùng database nghiệp vụ để trình diễn các cụm chưa triển khai.

## Phần còn lại

[Danh sách Visual/Print QA](C2_C3_VISUAL_PRINT_PENDING.md) ghi các màn hình cần kiểm khi có công cụ trình duyệt. Không có ảnh/PDF mới trong lượt khóa này. Không mở C4, G7, báo cáo Word hoặc slide.

Thông tin commit/remote và biên bản sau push được bàn giao riêng bằng ZIP evidence nhỏ, tránh ghi SHA tự tham chiếu trong chính commit.
