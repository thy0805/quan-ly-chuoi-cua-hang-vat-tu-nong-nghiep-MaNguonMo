# Lỗi cần tránh

## Dựng scaffold thay vì clone thật

Lỗi: ban đầu chỉ copy 11 file trung lập và đề xuất schema sáu bảng/role/trạng thái mới quá sớm. Nguyên nhân: diễn giải chọn lọc thành dựng khung mới và tinh gọn nghiệp vụ khi chưa chốt mapping. Sửa: lưu 60 file khung trước theo hash, clone 102 file Laravel thật nguyên byte, thay hồ sơ hiện hành bằng source-first mapping. Phòng tránh: phân biệt chuyển kỹ thuật với đổi nghiệp vụ; chọn module rồi giữ rule/state/formula/relation hoặc trình duyệt thay đổi riêng.

## Ổ mạng và Git ownership

D: là \\CUTE\USBSSD; kiểm Test-Path/net use/Get-PSDrive, không suy Get-Volume thiếu D là mất dữ liệu. Git SID ownership trên SMB chỉ xử lý bằng safe.directory đúng UNC cho từng lệnh; không đổi global hoặc wildcard.

## Bản clone chưa phải runtime độc lập

Giữ byte nguồn nghĩa là Mongo defaults, command klcn186_dev, Composer migrate và scheduler còn tồn tại. Không khởi động/chạy tests/scripts trước bước cô lập cấu hình được duyệt. Hash/syntax không chứng minh MySQL compatibility.

## Composer và working directory UNC

Trong G3, helper resolve D: thành UNC rồi dùng làm cwd khiến tiến trình con CMD của Composer không khởi chạy. Sửa bằng giữ cwd dạng ổ D: cho Composer, chỉ dùng UNC đã xác minh cho safe.directory truyền riêng vào tiến trình Git. Không sửa cấu hình Git/PHP toàn cục; không bỏ qua lỗi Composer để báo đạt.
