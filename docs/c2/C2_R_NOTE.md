# Chạy và rà C2-R

Đọc [kết quả C2-R](../C2_R_RESULT.md) và [evidence](../evidence/C2_R_VERIFICATION.json). Đây là bổ sung cho C2, không phải source website mới.

Server test được kiểm ở `http://127.0.0.1:8092/sales`, PHP8.3.30/MySQL8.4.3, database mnm_inventory_test_20261021. Dùng tài khoản demo/private hiện hữu theo [hướng dẫn C2](CHAY_VA_DEMO.md). Không công khai mật khẩu, không đổi .env hoặc database nghiệp vụ.

1. Mở phiếu `/sales/32`: hai dòng, đã xác nhận,55000.00VND.
2. Xem tồn C2-HTTP-A/B:8.000 và6.500. Xác nhận lại không ghi thêm movement.
3. Mở `/sales/32/print`: HTML hóa đơn nội bộ đã kiểm. Print Preview/PDF vẫn chờ trình duyệt thật.
4. Khi được kiểm browser, thử form nhiều dòng/validation/tìm lọc phân trang/menu/cuộn ngang tại1366/768/320; giữ đúng quyền owner và chỉ test database riêng.

Nếu server dừng, từ PowerShell ở thư mục source dùng launcher test hiện hữu với một cổng trống; nó không migration/seed và sẽ từ chối port bận:

```powershell
Set-Location -LiteralPath 'D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN'
& './tools/Start-InventoryDemo.ps1' -Port 8092
```

Lượt này server8091 cũ có lỗi ghi stdout làm Notice chèn JSON. Server8092 đã kiểm dùng helper cục bộ `.agent/scripts/c2_r_start_http.py` và handle log trực tiếp. Không gọi lại helper đang có server sống hoặc tự sửa vendor/config để ép chạy; nếu Notice trở lại phải ghi nhận, không bỏ prefix lỗi rồi coi JSON đạt.

Để chạy kiểm thử sau khi có thay đổi source, không cần migration/seed:

```powershell
& './tools/Test-Sales.ps1'
& './tools/Test-Sales.ps1' -ConcurrencyOnly
```

Suite hiện có61bài. Bốn bài C2-R nằm trong SaleTest.php; fixture nghiệp vụ được rollback. Race test giữ fixture riêng để readback. Không dùng các lệnh này với database khác profile test đã xác minh.

ZIP C2-R là bản diff/overlay3file trên ZIP C2 gốc, không phải bộ cài portable. Chưa commit/push và chưa có ảnh C2-R hoặc PDF in thật.
