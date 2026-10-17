# Khởi động và kịch bản demo — G6 DRAFT

## 1. Phạm vi hướng dẫn

Áp dụng máy Windows/Laragon hiện tại, source trên D: ổ mạng, đã có vendor theo composer.lock, private .env/APP_KEY và hai account MySQL được provision trước. Không là installer cho máy bất kỳ. Guard hiện cố định127.0.0.1:3306/MySQL8.4/datadir Laragon/account/schema riêng; đổi máy/cấu hình phải có kế hoạch cô lập được duyệt, không tắt guard cho chạy tạm.

G5 source checkpoint xem [receipt](../evidence/G6_G5_PUBLICATION.json). Chuẩn bị kết nối ổ mạng và kiểm đúng Git root LapTrinhMaNguonModoAN, không chạy ở SourceCode/NoSQL. MySQL phải là instance đã duyệt đang chạy; nếu dừng cần xác minh binary/my.ini/datadir/process trước khi khởi động theo quyền hiện hữu. Không initialize/reset password/cài lại/Start All hoặc sửa cấu hình từ hướng dẫn này.

## 2. Chạy Web trên máy hiện tại

Nếu preview hiện tại còn sống, mở [login](http://127.0.0.1:52999/login); port này không được bảo đảm sau reboot. Để chạy thủ công một preview mới, kiểm8086 chưa bị chiếm, mở PowerShell và thực hiện:

```powershell
Set-Location -LiteralPath 'D:/Hoctap/KhoaLuanCuNhan/LapTrinhMaNguonModoAN/public'
& 'C:/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe' -S 127.0.0.1:8086 '../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php'
```

Giữ terminal này khi demo; mở http://127.0.0.1:8086/login. Router đi kèm Laravel trả file static trực tiếp và chuyển route khác vào public/index.php. Đúng cwd public rất quan trọng. Ctrl+C chỉ dừng serverPHP này khi kết thúc; không tắt MySQL hoặc tiến trình khác.

Cách khởi động cùng router/cwd đã kiểm bằng server tạm/cổng rảnh: login và4assets trả200, không chèn DB credential qua environment, private .env hiện có nạp bình thường; server tạm đã dừng. [G6_STARTUP_CHECK](../evidence/G6_STARTUP_CHECK.json). 8086 trong hướng dẫn không được giữ chỗ hoặc tuyên bố đã chạy cố định; kiểm port trước dùng.

Không dùng php artisan serve trong cấu hình hiện tại vì CLI allowlist chưa cho serve; không sửa guard. Không npm build/CDN. Không tự composer update, generate key, migrate/fresh/rollback/db:seed để chuẩn bị demo. Nếu thiếu vendor/private credential, ghi blocker setup riêng thay vì copy .env KLCN/NoSQL hoặc chạy lệnh reset. Account ứng dụng chỉ schema CRUD; root không phải tài khoản đăng nhập Web.

Tài khoản demo: admin_demo, nhanvien_demo_1/nhanvien_demo_2; mật khẩu lấy qua kênh private tại máy, không ghi báo cáo/Git/ZIP. Không hiển thị .env, credential JSON, password_hash hoặc session cookie lên màn chiếu.

## 3. Kịch bản khoảng10–12phút, giữ nguyên dữ liệu demo

| Bước | Thời lượng | Thao tác | Kỳ vọng / bằng chứng |
|---|---|---|---|
| 1 |1phút| Giới thiệu đề tài, Laravel/MySQL/Blade/Bootstrap, phạm vi ba source riêng | Không nói đã có bán hàng/kho |
| 2 |1phút| Admin login, mở dashboard |4vật tư/5danh mục/3đơn vị; chỉ Admin thấy3tài khoản |
| 3 |2phút| Tìm NPK, lọc danh mục/trạng thái, mở /products?per_page=1 và Trang sau | Kết quả thật, giữ query, chi tiết có category/unit/giá |
| 4 |1phút| Mở form tạo; gửi tên/mã phù hợp nhưng giá âm | Validation tiếng Việt/old-input, không thêm bản ghi |
| 5 |1phút| Mở sửa nhưng không lưu; mở modal xóa rồi Hủy | Không thay demo, giải thích CSRF/middleware/RESTRICT |
| 6 |2phút| Mở code migration/model/seeder, ERD/UML; trình bày test CRUD rollback | Chỉ xem source/log; không chạy seeder/migration hoặc giả liveCRUD đã diễn |
| 7 |1phút| Logout Admin; login User, mở danh sách rồi truy cập /users hoặc /products/create | Không nút ghi; route trực tiếp403 ở server |
| 8 |1phút| Thu hẹp viewport/mobile, mở menu/cuộn bảng; logout | Theo ma trận G5; không tuyên bố điện thoại vật lý đã kiểm |
| 9 |1phút| Tóm tắt ràng buộc/giới hạn và phân công đã thống nhất | Không nhận upload/AJAX/đặt hàng là đã có |

CRUD thành công đã kiểm trong PHPUnit transaction/rollback, không chỉ có form. Nếu buổi chấm bắt buộc CRUD thành công trực tiếp, chuẩn bị riêng một kế hoạch dữ liệu QA/disposable record và cleanup được Thy duyệt trước; không dùng/xóa/chỉnh demo hiện hữu hoặc tự seed lại. Đây là việc demo chuẩn bị thêm, không chức năng backend còn thiếu.

## 4. Checklist trước và sau demo

- [ ] Đúng source/root/commit và MySQL riêng; còn4/5/3/3dữ liệu demo tương ứng vật tư/danh mục/đơn vị/user.
- [ ] Login/assets hoạt động; tài khoản private sẵn sàng; không lưu mật khẩu vào slide.
- [ ] ERD/UML mở trực tiếp, font tiếng Việt đọc được; có ZIP/ảnh offline để xem evidence khi mạng chậm.
- [ ] Nhóm xác nhận phân công, người thao tác và người trả lời từng module.
- [ ] Sau demo logout, không commit dữ liệu/log/credential; dừng đúng PHPpreview nếu do nhóm mở.

Các checkbox là kịch bản đề xuất, chưa đánh dấu như buổi demo đã diễn. Không tự chạy lại kiểm thử hàng loạt trên màn chiếu khi chưa cân nhắc thời gian/fixture/rollback.
