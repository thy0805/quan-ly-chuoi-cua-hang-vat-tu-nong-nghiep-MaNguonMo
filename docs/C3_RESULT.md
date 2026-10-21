# C3 DRAFT — Nhà cung cấp và nhập hàng

Ngày kiểm: 21/10/2026. Nhóm 6. Phát triển trên source Laravel hiện hữu; chỉ MySQL test `mnm_inventory_test_20261021` được ghi.

## Checkpoint và phạm vi

C2/C2-R đã được Thy nghiệm thu **chức năng ACCEPTED/LOCKED**, commit/push `main` **2d5d323e5fa629508a83aa5f542081343a0f7902**. Remote SHA và tree readback khớp,51file trong commit,355file tracked. [Receipt công bố C2](evidence/C2_PUBLICATION_20261021.json). Source30file khớp ZIP C2 cùng overlay3file C2-R; kiểm từng staged blob/secret scan. Nội dung G7/audit chưa nghiệm thu giữ ngoài commit bằng index riêng cho phần header C2 của tài liệu trộn. C2 còn **Browser/Print PENDING**.

C3 bổ sung CRUD nhà cung cấp và phiếu nhập nhiều dòng; lưu/sửa nháp, kiểm version/token, xác nhận tăng kho đúng một lần, tra cứu/lọc/phân trang và trang in nội bộ. **C3 chưa stage/commit/push, chưa LOCKED**. Không sửa hồ sơ đã khóa hoặc tiếp tục G7.

| Chức năng | Admin | User | URL |
|---|---|---|---|
| Danh sách/tìm/lọc/phân trang/chi tiết nhà cung cấp | Có | Có | `/suppliers`, `/suppliers/{id}` |
| Thêm/sửa/xóa nhà cung cấp chưa được dùng | Có | Không | `/suppliers/create`, `/suppliers/{id}/edit` |
| Danh sách/chi tiết phiếu nhập | Toàn cửa hàng | Toàn cửa hàng, chỉ đọc | `/purchases`, `/purchases/{id}` |
| Lập/sửa nháp/xác nhận nhập | Phiếu do mình lập | Không | `/purchases/create`, `/purchases/{id}/edit`, POST confirm |
| Trang in phiếu đã xác nhận | Có | Có | `/purchases/{id}/print` |

Nhà cung cấp có phiếu nhập không xóa được (FK RESTRICT), có thể ngừng hoạt động. Nhà cung cấp/vật tư phải hoạt động tại lập nháp và xác nhận. Phiếu xác nhận bất biến ở model và5trigger MySQL mới. User không được cấp quyền nhập/xuất kho thủ công C1; C2 vẫn bán phiếu của mình.

## Kiểm chứng thực tế

| Hạng mục | Kết quả | Evidence |
|---|---|---|
| PHPUnit/MySQL hồi quy C1/C2/C3 | **75/75 bài,1.277 assertions**,20.491ms;14bài C3 | regression |
| Đồng thời C3 | **6ca/12PHP worker thật**, parent giữ product lock đến khi cả2worker sẵn sàng | concurrency |
| HTTP TCP8092 | **48request đạt**: session/CSRF,CRUD,nháp/sửa/version/confirm/replay/quyền/in/chuỗi bán | http |
| Lệnh chạy demo Windows | Server tạm8093,11request đạt sau sửa heading cuối; đã dừng8093 | demo_launcher |
| Schema test | **11bảng nghiệp vụ + migrations;94cột;13FK;23CHECK;14trigger;8migration** | mysql_readback |
| Sổ kho |71movement/36vật tư có stock, toàn chuỗi before/after và tồn khớp | mysql_readback.ledger |
| Phiếu nhập |10header/13dòng/11purchase movement; draft0/confirmed1movement mỗi dòng | mysql_readback.purchases |
| Seeder C3 |2lần trong transaction test, lần2 giữ snapshot; không đăng ký mặc định | PurchaseTest seeder |
| Route |62tổng/15C3;auth+active+schema guard, mọi đường ghi/create/edit cóadmin | routes |
| Tĩnh/JS |77PHP cú pháp đạt;2PowerShell parse;Sales vàPurchases mỗi8assertions DOM giả lập | static |
| Browser1366/768/320 và Print Preview/PDF | **PENDING**, không có công cụ callable | Không có ảnh/PDF giả |

Sáu ca đồng thời: xác nhận cùng phiếu; hai phiếu nhập cùng vật tư; nhiều dòng với thứ tự input đảo; tạo nháp trùng token; nhập và bán cùng vật tư; hai phiếu cùng chạm giới hạn tồn (một thành công, một validation). Hồi quy C1/C2 nằm trong75bài; không chạy lại các race cũ không đổi. Tổng tiền dùng nguyên cent/half-up và checked-add C2; không float. Kho dùng duy nhất InventoryService C1.

Demo HTTP **PN-00000048**: nhập A20×12500 và B8.5×20000, tổng420000.00. Sau đó User lập/xác nhận **BH-00000095**, bán A2×15000 và B1.5×25000, tổng67500.00. Tồn cuối **A18.000/B7.000**. Purchase movement402/403 tham chiếu purchase_line69/70; sale movement404/405 tham chiếu sale_line134/135. Xác nhận lặp không thêm movement. Fixture C1 id21 vẫn15.000/2movement; hóa đơn C2 id32 vẫn55000.00 và tồn8/6.5.

PHPUnit chạy sau source PHP nghiệp vụ cuối; chỉ đổi hai nhãn trang index sau đó, đã GET và kiểm nội dung trong11request server8093. Không dùng HTTP/DOM giả lập để khẳng định responsive hoặc bố cục A4. Seeder được kiểm hai lần trong transaction rollback, không tuyên bố fixture seeder đã lưu thường trú.

## File đã thêm/sửa

**36file source/test/tool:29mới,7sửa**; whitelist đầy đủ ở [C3_VERIFICATION](evidence/C3_VERIFICATION.json).

| Nhóm | File chính |
|---|---|
| Migration/seeder | `2026_10_21_000008_create_purchasing_tables.php`, `PurchaseDemoSeeder.php` |
| Models | Supplier,Purchase,PurchaseLine; thêm quan hệ Product/User |
| Nghiệp vụ | PurchaseService; InventoryService.receive dùng lại record hiện hữu |
| HTTP | SupplierController,PurchaseController;4FormRequest;EnsurePurchasesInstalled;routes;thông báo FK UserController |
| Giao diện |7Blade purchases,3Blade suppliers;menu layout;purchases.js;dùng lại Bootstrap/CSS in C2 |
| Kiểm thử/chạy | PurchaseTest,purchases_concurrency.php,purchases_form.js;metadata InventoryTest;Test-Purchases.ps1,Start-PurchasingDemo.ps1 |

[Thiết kế/schema/quyền](c3/THIET_KE.md) · [Chạy và trình diễn Windows](c3/CHAY_VA_DEMO.md) · [Bắt đầu đọc](c3/README.md).

## Bảo toàn và giới hạn

Database nghiệp vụ `mnm_vat_tu_nong_nghiep` được snapshot bằng SQL READ ONLY trước/sau: hash dữ liệu/DDL/trigger5bảng giữ nguyên. Không migrate/seed/ghi database đó. Có một lần helper đọc business bị guard từ chối do kế thừa profile test; không bypass guard, chạy lại đúng profile chỉ đọc và kết quả before/after khớp.

139file tracked source/config/dependency ngoài7điểm integration giữ nội dung so commit C2;29artifact G6/G7/ZIP giữ SHA-256. HEAD/status KLCN và NoSQL khớp đầu lượt; đây là kiểm Git state, không là hash toàn source hai repo. KLCN có9path Mobile dirty từ trước; NoSQL sạch. G7/audit dirty từ trước giữ riêng. Không sửa Word/Rose/dependency/config hoặc accepted ZIP.

Chưa triển khai C3 lên database nghiệp vụ; chưa kiểm cảm ứng thật/responsive/print dialog/PDF. Không bổ sung hoàn/hủy nhập, công nợ, nhiều chi nhánh, web shop hoặc thanh toán. Các fixture race/HTTP nằm trên test DB để readback; không xóa lịch sử bất biến để ép count. Count có thể tăng khi chạy lại các test giữ fixture.

## Bàn giao và điểm dừng

`MNM_NHOM6_C3_DRAFT_20261021.zip`: source overlay36file/diff so commit C2, hồ sơ mới/evidence đã lọc, Git state và business hashes; manifest SHA-256/CRC/readback. Không có dependencies,credential,dump hoặc ảnh giả. SHA ZIP/receipt ở tin nhắn bàn giao và `.agent/logs/c3/package-final.json`, không tự hash manifest.

Chức năng C3 VERIFIED trong môi trường test; toàn checkpoint **DRAFT chờ Thy/GPT Web nghiệm thu**. Browser/Print C2+C3 PENDING. **Không commit/push C3, không mở C4/G7/Word.**
