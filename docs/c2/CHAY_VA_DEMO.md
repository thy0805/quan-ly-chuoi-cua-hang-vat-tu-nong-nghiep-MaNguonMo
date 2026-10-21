# Chạy và trình diễn C2 trên Windows

## Môi trường hiện hữu

Source: `D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN`. PHP Laragon8.3.30, MySQL8.4.3 cổng3306. Database test/demo `mnm_inventory_test_20261021`; runtime riêng chỉ SELECT/INSERT/UPDATE/DELETE. Không dùng database nghiệp vụ `mnm_vat_tu_nong_nghiep` để thử ghi.

Private profile giữ cục bộ tại `.agent/private/c1/credentials.json`, không trong ZIP/Git. Dùng tài khoản ứng dụng demo hiện hữu `admin_demo` và `nhanvien_demo_1` với mật khẩu private đã cấp; không chép mật khẩu vào hồ sơ/chat. Không reset/seed lại khi chưa rõ fixture. Khi chuyển máy thiếu private profile, tài liệu này không tự cấp quyền provision/migration.

MySQL đã chạy bằng cấu hình/datadir Laragon hiện hữu. Nếu dịch vụ dừng sau đổi phiên/máy, kiểm lại binary/config/datadir/port; không initialize/reset mật khẩu hoặc mở database khác.

## Khởi động Web

Server test đã chạy ở8091 trong lượt bàn giao. Mở `http://127.0.0.1:8091/login`, đăng nhập rồi chọn **Bán hàng**. Nếu server đã dừng, dùng PowerShell sau; helper từ chối khi port bận, không migration/seed:

```powershell
Set-Location -LiteralPath 'D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN'
& './tools/Start-InventoryDemo.ps1' -Port 8091
```

PHP/public/router của helper đã chạy thực tế với C2 source và test DB. Không đổi .env sang database nghiệp vụ để thử C2.

## Demo có sẵn, ưu tiên chỉ đọc

1. Đăng nhập User `nhanvien_demo_1`; `/sales` chỉ liệt kê phiếu mình lập.
2. Mở `/sales/32`: BH-00000032, đã xác nhận, hai dòng, tổng55000.00 VND.
3. Xem `/inventory?q=C2-HTTP-A`:10−2=8.000; B:8−1.5=6.500. Lịch sử có sale/44 và sale/45, mỗi dòng đúng một movement xuất.
4. Mở `/sales/32/print`. HTML đã kiểm; nút In/Lưu PDF và bố cục bản in chưa được kiểm bằng trình duyệt thật.
5. Admin đọc được phiếu32 của User nhưng không sửa/xác nhận thay chủ phiếu. User không mở form nhập/xuất kho thủ công.
6. Seeder C2 riêng đã tạo phiếu33 với C2-DEMO-A/B; chạy lần2 giữ nguyên count. Không cần chạy lại để trình diễn.

Thử lập phiếu mới: chọn vật tư có tồn trên test DB → lượng/đơn giá → lưu nháp → kiểm tổng → xác nhận. Không ghi thêm vào fixture C1 đã khóa. Hóa đơn đã xác nhận có lịch sử bất biến, C2 chưa có xóa/hủy/hoàn tác.

## Chạy lại kiểm thử khi có thay đổi

Các lệnh dùng test profile đã xác minh, không đổi .env:

```powershell
& './tools/Test-Sales.ps1'
& './tools/Test-Sales.ps1' -ConcurrencyOnly
& './tools/Test-Inventory.ps1' -ConcurrencyOnly
& 'C:/Users/thy/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/bin/node.exe' './tests/Support/sales_form.js'
```

57 bài PHPUnit rollback fixture theo transaction. Các bài concurrency giữ fixture riêng để đọc lại; không xóa lịch sử bất biến nhằm ép count. Node kiểm handler với DOM giả lập, không thay browser QA. Path cục bộ phụ thuộc máy Thy, không là cấu hình triển khai mới.

## Còn PENDING

Kiểm/chụp thật1366/768/320: danh sách, form nhiều dòng, validation, phiếu xác nhận/trang in; thử thêm/xóa dòng/chọn giá bằng chuột, cuộn ngang mobile và hộp thoại in/PDF. Phiên C2 không có công cụ browser callable nên chưa có ảnh. C2 DRAFT chờ Thy/GPT Web rà và xử lý/chấp nhận riêng giới hạn này; chưa commit/push.
