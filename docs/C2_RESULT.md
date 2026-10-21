## C2-R hiện hành — 21/10/2026

[Hồ sơ C2-R](C2_R_RESULT.md): checked-add trước phép cộng,61/61tests/1095assertions,HTTP15cổng8092 và4race C2 đạt. Browser1366/768/320/in thật PENDING;chưa commit/push. Phần dưới là snapshot C2 trước sửa.

---

# C2 DRAFT — Bán hàng tại quầy và hóa đơn nội bộ

Ngày kiểm: 21/10/2026. Nhóm6. Tiếp tục source Laravel hiện hữu của môn Mã nguồn mở.

## Checkpoint và chức năng

C1 ACCEPTED/LOCKED đã commit/push main `08ffd61a43a315b685deb0b74f1a1d85c1dbf020`; local/remote/tree khớp. Commit thay đổi54file, tổng320file tracked. [Receipt C1](evidence/C1_PUBLICATION_20261021.json). C2 chỉ ở working tree, **chưa commit/push, chưa LOCKED**. Thay đổi G7/audit có từ trước được giữ ngoài checkpoint C1 và source diff C2.

C2 thêm lập/sửa nháp nhiều vật tư, tính tiền dòng/tổng, xác nhận bán trừ kho một lần, danh sách/tìm/lọc/phân trang, chi tiết và trang in hóa đơn nội bộ. Dùng kho C1; không Web Shop, Customer, thanh toán, công nợ hoặc C3.

| Chức năng | Admin | User | Đường dẫn |
|---|---|---|---|
| Danh sách/tìm/lọc/phân trang | Tất cả phiếu | Chỉ phiếu mình | `/sales` |
| Lập/sửa nháp | Phiếu mình | Phiếu mình | `/sales/create`, `/sales/{id}/edit` |
| Xác nhận bán | Phiếu mình | Phiếu mình | POST `/sales/{id}/confirm` |
| Chi tiết/in hóa đơn đã xác nhận | Tất cả phiếu | Chỉ phiếu mình | `/sales/{id}`, `/sales/{id}/print` |
| Nhập/xuất kho thủ công C1 | Có | Không | `/inventory` |

Admin đọc được phiếu nhân viên nhưng không sửa/xác nhận thay người lập. Confirmed bất biến; draft chưa giữ/trừ tồn. Kiểm quyền cả controller/service dựa trên tài khoản hiện hành.

## Kiểm tra thực tế

| Lớp kiểm | Kết quả | Evidence trong C2_VERIFICATION.json |
|---|---|---|
| PHPUnit/MySQL | 57/57bài,1.041assertions,16.898ms | regression |
| Đồng thời C2 | 4ca/8worker PHP thật, hàng rào khóa | c2_concurrency |
| Hồi quy đồng thời C1 | 4ca/8worker PHP thật | c1_concurrency_regression |
| HTTP TCP | 34request session/CSRF, nháp/replay/confirm, quyền, assets, HTML in | http_demo |
| MySQL chỉ đọc | 8bảng nghiệp vụ/62cột/9FK/16CHECK/9trigger;7migration | mysql_readback |
| Kho/phiếu | 39movement/22vật tư có stock;9phiếu/13dòng/10sale movement; chuỗi tồn, tổng, nguồn khớp | mysql_readback |
| Seeder C2 | Chạy2lần test DB, lần2 không thay count/phiếu | seeder |
| Tĩnh/JS | 62PHP không lỗi cú pháp,2PowerShell không lỗi; JS syntax và8assertions Node DOM giả lập đạt | static |
| Route | 47tổng,8C2 có auth/active/schema guard | routes |
| Browser1366/768/320, in thật | **PENDING**, công cụ không callable | Không có ảnh C2 |

PHPUnit hoàn tất trước sửa tối thiểu chỉ số JS khi validation trả old input có keys thưa. JS sau sửa được kiểm syntax và8assertions Node VM; không sửa PHP ứng dụng sau lần PHPUnit này. Node DOM giả lập không chứng minh responsive/thao tác chuột/hộp thoại in.

Demo HTTP `BH-00000032`: A tồn10 bán2×12500 còn8; B tồn8 bán1.5×20000 còn6.5; tổng55000.00VND. Movement165/166 có source_type=sale và source_id=44/45 theo từng sale_line. Xác nhận lại giữ đúng hai movement này. Seeder riêng tạo phiếu33 với C2-DEMO-A/B và không cộng/trừ thêm khi chạy lại.

## File và demo Windows

C2 có **30file source/test/tool thêm hoặc sửa**, danh sách đầy đủ tại [evidence](evidence/C2_VERIFICATION.json). Gồm migration7; Sale/SaleLine/SaleMoney/SaleService; tích hợp InventoryService; controller/2request/middleware;7Blade,JS/CSS;3file kiểm thử mới/cập nhật metadata test C1; SaleDemoSeeder; Test-Sales.ps1; models/route/layout liên quan.

[Hướng dẫn demo](c2/CHAY_VA_DEMO.md) · [Thiết kế và schema](c2/THIET_KE.md) · [Bắt đầu](c2/README.md). Server test dùng `http://127.0.0.1:8091/sales`; phải kiểm lại live state khi đổi phiên. Không tự migration database nghiệp vụ.

## Bảo toàn và giới hạn

Database nghiệp vụ `mnm_vat_tu_nong_nghiep` giữ nguyên hash dữ liệu/DDL/trigger5bảng; chưa triển khai kho/bán hàng. Fixture C1 id21 vẫn15.000 và2movement. 27artifact G6/G7/ZIP giữ hash. HEAD/status KLCN/NoSQL khớp đầu lượt; không coi kiểm HEAD/status là hash toàn bộ source hai repo.

Không đổi dependencies/package/lock, Word, Rose hoặc triển khai C3. Chưa visual C2, responsive, thiết bị cảm ứng và print dialog. HTML trang in đã trả200/nội dung đúng, chưa xác nhận bố cục bản in. Không kết luận C2 đã triển khai nghiệp vụ hoặc production-ready.

## Bàn giao và điểm dừng

ZIP `MNM_NHOM6_C2_DRAFT_20261021.zip` chứa source overlay/diff so C1, hồ sơ C2, evidence gọn và manifest SHA-256. Không gói dependencies/source không đổi, database dump, credential hoặc ảnh C1 thay thế. Receipt CRC/SHA/readback cuối ở `.agent/logs/c2/package-final.json`; SHA ZIP ghi tại receipt/tin nhắn bàn giao để tránh self-hash vòng lặp.

Chờ Thy/GPT Web rà C2 DRAFT và phần browser còn PENDING. **Chưa commit/push C2, chưa mở C3/G7.**
