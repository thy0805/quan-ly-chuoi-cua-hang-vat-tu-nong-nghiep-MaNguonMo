# Chạy và trình diễn C3 trên Windows

## Môi trường đã kiểm

Source `D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN`; PHP8.3.30/MySQL8.4.3; test DB `mnm_inventory_test_20261021`. Private profile C1 giữ cục bộ, không có trong Git/ZIP. Dùng tài khoản ứng dụng `admin_demo` và `nhanvien_demo_1`, mật khẩu private hiện hữu. Không gửi mật khẩu lên chat hoặc sửa .env để chạy thử.

Server test8092 đã chạy, ưu tiên mở `http://127.0.0.1:8092/login`. Nếu cổng đó đang chạy, không khởi động thêm. Nếu đã dừng, mở PowerShell và dùng helper foreground sau (giữ terminal mở,Ctrl+C để dừng):

```powershell
Set-Location -LiteralPath 'D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN'
& './tools/Start-PurchasingDemo.ps1' -Port 8092
```

Helper từ chối cổng bận, xác minh profile test, không migrate/seed/đổi .env. Cùng lệnh đã chạy thực tế ở cổng trống8093 và11HTTPrequest đạt;8093 đã dừng. Chạy foreground giữ stdout hợp lệ, tránh Notice của helper background cũ8091. Không sửa vendor/PHP config. Không tự khởi tạo MySQL nếu phiên bản/datadir/account chưa khớp guard.

## Demo có sẵn, không cần ghi thêm

1. Admin vào **Nhà cung cấp** và **Nhập hàng**; xem `http://127.0.0.1:8092/purchases/48` (PN-00000048,tổng420000.00).
2. Xem hai dòng: A nhập20×12500;B nhập8.5×20000; nguồn theo dòng69/70, mỗi dòng đúng1movement.
3. User vào `/sales/95`: BH-00000095,tổng67500.00; bán A2×15000,B1.5×25000.
4. Tìm tồn kho bằng mã `C3-HTTP-f738f634-A` và `C3-HTTP-f738f634-B`: còn18.000 và7.000. Lịch sử mỗi vật tư có1nhập purchase và1xuất sale, before/after nối tiếp.
5. User đọc được supplier/purchase và trang in confirmed nhưng không thấy nút lập/sửa/xác nhận. Truy cập ghi trực tiếp trả403. Admin không xóa supplier đã có phiếu.
6. `/purchases/48/print` và `/sales/95/print`: HTML đúng; **chưa xác nhận bố cục in/PDF**. Không coi mở tab là agent đã xem trực quan.

Nếu muốn thử tạo mới, chỉ dùng vật tư test riêng: tạo supplier → lập phiếu → lưu/sửa nháp → kiểm tổng → xác nhận → tra lịch sử. Không nhập thêm vào fixture C1 id21 hoặc hóa đơn đã nghiệm thu. Chưa có hủy/hoàn tác; không dùng dữ liệu thật.

## Chạy lại kiểm thử khi sửa source

```powershell
& './tools/Test-Purchases.ps1'
& './tools/Test-Purchases.ps1' -ConcurrencyOnly
```

Wrapper đã kiểm syntax; bộ test thực tế chạy PHP entrypoint trực tiếp bằng runner private tương đương profile: PHPUnit75bài/1277assertions,6race/12worker. PHPUnit rollback fixture; race giữ fixture test riêng để readback. Không mặc định chạy seed khi khởi động. PurchaseDemoSeeder được gọi2lần trong transaction test để kiểm replay, không được đưa vào DatabaseSeeder mặc định.

## Còn PENDING

Thy/GPT Web nghiệm thu C3 DRAFT; browser1366/768/320 và Print Preview/PDF của C2+C3; triển khai lên database nghiệp vụ cần quyền riêng. Không tự commit/push C3, không G7/Word/slide. Không dùng ảnh kho C1 để thay ảnh C2/C3.
