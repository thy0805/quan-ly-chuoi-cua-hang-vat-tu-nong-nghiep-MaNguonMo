# C2-R DRAFT — Giới hạn tiền và kiểm tra sau sửa

Ngày kiểm: 21/10/2026. Nguồn yêu cầu: Thy gửi kết quả rà C2 và prompt C2-R. Không mở C3/G7; không commit/push.

## Kết luận về ca biên trước sửa

Ba bài kiểm thử mới được chạy trước khi sửa SaleMoney/SaleService: **3/3 bài, 42 assertions đạt**. Source C2 cũ đã trả ValidationException/HTTP422 khi tổng vượt DECIMAL và rollback khi xác nhận dữ liệu draft bất thường; chưa tái hiện TypeError hoặc HTTP500 từ phép cộng tổng.

Lý do: source cũ kiểm `SaleMoney::decimal($total)` ngay sau mỗi lần cộng. Mỗi tổng đang hợp lệ và mỗi dòng đều không vượt MAX=999999999999999999 cent. Trên PHP64-bit của máy, một lần cộng nhiều nhất2×MAX=1999999999999999998, vẫn nhỏ hơn PHP_INT_MAX=9223372036854775807. Phép cộng tiền liên tục không kiểm tra có thể tràn int, nhưng điều đó không chứng minh luồng ứng dụng hiện hành đã tràn.

C2-R vẫn bổ sung chặn **trước** phép cộng theo yêu cầu, làm điều kiện an toàn rõ ràng và không phụ thuộc kiểm tra sau cộng. Không ghi nhận lỗi500 đã tái hiện khi bằng chứng thực tế là422.

## Thay đổi tối thiểu

Chỉ3file source/test khác baseline C2:

| File | Thay đổi |
|---|---|
| app/Support/SaleMoney.php | add(total,amount) từ chối số âm, total ngoài MAX hoặc amount lớn hơn MAX−total trước khi cộng; trả ValidationException với key total |
| app/Services/SaleService.php | Cả lưu nháp và xác nhận dùng SaleMoney.add; bỏ cộng trực tiếp/kiểm sau cộng |
| tests/Feature/SaleTest.php | Thêm4bài và snapshot toàn nội dung4bảng: giới hạn helper/PHP_INT_MAX,10dòng hợp lệ nhưng tổng vượt mức, xác nhận draft bất thường/rollback, hóa đơn đúng MAX và replay |

MAX−total chỉ được tính sau khi total hợp lệ nhờ short-circuit. Phép cộng cuối chỉ diễn ra khi cả hai không âm và tổng≤MAX. Giữ integer cent, DECIMAL(18,2), lượng DECIMAL(15,3), half-up từng dòng, source theo sale_line, quyền và transaction C2; không đổi InventoryService hoặc nhập/xuất thủ công C1.

Test xác nhận draft bất thường cố ý cập nhật các dòng của fixture draft trong transaction test, mỗi dòng vẫn thỏa CHECK MySQL nhưng tổng lớn hơn header. Không tắt trigger/CHECK, không sửa dữ liệu nghiệp vụ; fixture rollback sau test. Kiểm snapshot bảo đảm các thay đổi kho/movement ở dòng trước bị rollback khi dòng sau thất bại.

## Kết quả sau source cuối

| Kiểm tra | Kết quả thực tế |
|---|---|
| 4ca biên sau sửa | 4/4 bài,52assertions |
| PHPUnit đầy đủ | **61/61 bài,1.095assertions**,21.164ms |
| Đồng thời C2 | 4ca/8worker PHP thật đạt: xác nhận cùng phiếu, tranh tồn, nhiều vật tư đảo thứ tự, tạo nháp trùng client_key |
| TCP HTTP thật cổng8092 | **15request đạt**; tạo/gửi lại/sửa quá tổng đều422 với errors.total; phiếu nháp không thay đổi |
| Bốn bảng sau HTTP | Hash toàn nội dung sales/sale_lines/product_stocks/inventory_movements trước–sau khớp |
| MySQL readback chỉ đọc | 8bảng nghiệp vụ/62cột/9FK/16CHECK/9trigger;7migration giữ nguyên |
| Kho/phiếu test sau race | 50movement/27vật tư có stock;15phiếu/21dòng/16sale movement; chuỗi tồn, tổng và nguồn từng dòng khớp |
| C1 fixture/demo C2 cũ | C1 id21 vẫn15.000/2movement;BH-00000032 vẫn55000.00, tồn A8.000/B6.500 |
| Source/artifact bảo vệ | 162/165file baseline giữ hash; chỉ3file source/test nêu trên thay đổi; các ZIP cũ giữ nguyên |
| Database nghiệp vụ | Hash dữ liệu/DDL/trigger5bảng khớp đầu lượt; không migration/seed/ghi |
| KLCN/NoSQL | HEAD/status khớp đầu lượt; không coi đây là hash toàn source hai repo |

Evidence đầy đủ tại [C2_R_VERIFICATION](evidence/C2_R_VERIFICATION.json). Các count fixture có thể tăng khi chạy lại concurrency; không xóa lịch sử để ép count cũ. Hồi quy C1 nằm trong suite61bài; không chạy lại4race C1 ở lượt này vì không sửa cơ chế kho C1.

## HTTP server và phần chưa kiểm

Server demo cũ8091 phát PHP Notice ở Laravel server.php dòng21: ghi php://stdout bị errno13 Permission denied, Notice chèn trước JSON. Lần kiểm đó thất bại khi parse và không được tính PASS. Helper snapshot ban đầu còn bị tên receipt trùng file dữ liệu; đã tách tên và kiểm lại hash từ raw log, không có bằng chứng database bị đổi bởi các lần HTTP thất bại.

Dùng server test riêng8092, cùng PHP/source/router Laravel/profile cô lập, stdout/stderr truyền trực tiếp vào file log. Không sửa vendor, php.ini, guard, Work settings hoặc che lỗi bằng display_errors. 15request sau đó đạt với JSON sạch.

**Browser1366/768/320, ảnh giao diện, thao tác bằng chuột và Print Preview/Save PDF: PENDING.** Phiên này không có công cụ browser/computer callable; open_in_codex chỉ mở tab, không cho agent kiểm/chụp trang. HTML hóa đơn/JS/CSS đã GET200; không coi đó là kiểm bố cục A4. Không tạo ảnh giả hoặc dùng ảnh C1 thay thế.

## Trình diễn Windows và bàn giao

Đọc [ghi chú chạy C2-R](c2/C2_R_NOTE.md). URL test đã kiểm: `http://127.0.0.1:8092/sales`; phiếu32 và trang in32 giữ nguyên. Server cần kiểm lại nếu đổi phiên/máy.

C1 main vẫn08ffd61a43a315b685deb0b74f1a1d85c1dbf020. C2/C2-R chưa stage/commit/push. Các thay đổi G7/audit có sẵn được giữ riêng; Git status có trong ZIP.

ZIP `MNM_NHOM6_C2_R_DRAFT_20261021.zip` chỉ gói3file source/test sửa, diff so source trong ZIP C2 gốc036cdd72…b538391, hồ sơ/evidence mới và manifest SHA-256. Không gói lại source/dependencies không đổi, credential hoặc database dump. Receipt SHA ZIP/CRC/readback cuối nằm tại `.agent/logs/c2-r/package-final.json` và tin nhắn bàn giao.

Điểm dừng: phần chức năng C2-R đã kiểm; toàn checkpoint vẫn DRAFT chờ nghiệm thu và visual/in thực tế. Không tự khóa Git C2 hoặc mở C3/G7.
