> Cập nhật visual21/10 sau lần bàn giao bên dưới: browser đã kết nối lại;15ảnh desktop/tablet/mobile1366/768/320 đã được chụp và xem trực tiếp. ZIP ảnh riêng MNM_NHOM6_C1_ANH_GIAO_DIEN_20261021.zip; [checklist mới](../../.agent/C1_VISUAL_CHECKLIST_20261021.md). Các dòng visual PENDING/disabled bên dưới là trạng thái lúc bàn giao cũ; evidence test và ZIP cũ giữ nguyên. C1 vẫn chờ Thy nghiệm thu/LOCKED; không chạy lại test hay ghi giao dịch trong lượt chụp.

# Chạy và trình diễn trên Windows

Các bước dưới dùng môi trường test C1 đã provision trên máy Laragon hiện tại. Không tạo lại database, không chạy migration/seed/reset. Không dùng database nghiệp vụ để diễn tập.

PowerShell, từ thư mục source:

```powershell
Set-Location 'D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN'
& './tools/Start-InventoryDemo.ps1' -Port 8091
```

PHP và thư mục public/router đã được kiểm tồn tại. Tool chỉ bind 127.0.0.1, chạy nền ẩn, đọc cấu hình private, dùng đúng account test và không chạy migration/seed. Nếu cổng đã dùng, tool từ chối khởi động server thứ hai; dùng server hiện tại tại [đăng nhập](http://127.0.0.1:8091/login). C1 đã chạy thực tế trên cổng này. Source ở ổ mạng, MySQL/PHP chạy trên máy Windows có Laragon.

Tài khoản demo `admin_demo`, User `nhanvien_demo_1` trong database test. Mật khẩu chỉ ở `.agent/private/c1/credentials.json` trên máy, không có trong ZIP/repo/log public. Dùng thông tin **test** tại máy; không nhập account nghiệp vụ vào demo. Tool không yêu cầu nhập lại root.

1. Admin mở **Quản lý tồn kho**, tìm mã `C1-DEMO-20-5`.
2. Vật tư demo này đã được kiểm bằng HTTP thật: nhập `20`, ghi chú `Demo C1 in`; xuất `5`, ghi chú `Demo C1 out`.
3. Tồn hiện tại `15.000`; bấm Lịch sử của vật tư để thấy đúng 2 dòng: 0 → 20 và 20 → 15. Có thể lọc chiều và ngày, đổi số dòng/trang.
4. Thử xuất `16`: lỗi không đủ tồn; số tồn và lịch sử giữ nguyên. Gửi lại đúng phiếu đã xác nhận không cộng/trừ lần nữa; mở phiếu mới là giao dịch mới.
5. Đăng xuất, dùng User để xem tồn/lịch sử. GET/POST nhập/xuất trực tiếp bị 403, không chỉ ẩn nút.

Để diễn lại từ 0 mà giữ lịch sử demo đã kiểm, Admin tạo **vật tư khác chỉ trong database test**, mở hai phiếu mới nhập20/xuất5. Không nhập tiếp vào C1-DEMO-20-5 rồi kỳ vọng còn15; không xóa lịch sử/seed lại. CRUD thông thường không có ô sửa tồn.

Chạy lại kiểm thử trên schema test hiện hữu:

```powershell
& './tools/Test-Inventory.ps1'
& './tools/Test-Inventory.ps1' -ConcurrencyOnly
```

Lệnh đầu chạy 44 test, các thay đổi trong test được rollback. Lệnh sau tạo vật tư QA riêng để kiểm nhiều tiến trình thật; giữ fixture và sổ kho test, không xóa lịch sử bất biến. Không chạy hai lệnh cùng lúc. Tool không provision hoặc nâng quyền; thiếu private/vendor/MySQL/schema sẽ báo lỗi, không chuyển sang database nghiệp vụ.

Đây là tool cho máy hiện tại, dùng PHP Laragon 8.3.30 và database test cố định. Máy khác cần cài dependency theo composer.lock và chuẩn bị cấu hình/test schema riêng theo chính sách dự án trước; không sao chép credentials hoặc datadir. Chưa có quy trình cài đặt tự động cho máy khác trong C1.

HTTP login, session/CSRF, bốn assets và các thao tác kho đã kiểm. Kiểm giao diện bằng mắt ở 1366/768/320px và ảnh mới còn PENDING vì công cụ browser trả lỗi disabled; không khẳng định đạt responsive dựa vào các class Bootstrap.
