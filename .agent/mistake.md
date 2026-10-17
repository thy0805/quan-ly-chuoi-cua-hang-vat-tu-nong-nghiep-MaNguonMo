# Lỗi cần tránh

## Laravel 13 gộp cấu hình mặc định và console bootstrap

Laravel13 mặc định merge connection/store framework vào config ứng dụng, nên file chỉ khai báo MySQL vẫn có SQLite/PostgreSQL trong container. G4A đã dùng dontMergeFrameworkConfiguration, cấu hình view compiled riêng và withExceptions để boot/đọc lỗi thật; connection extension phải gán config name trước tạo MySqlConnection để migrator không đổi default thành null. Kiểm container và migration thật, không chỉ lint/config file.

## Không xuất cấu hình tổng thể hoặc giữ phiên quản trị trong REPL khi chạy test dài

Probe từng xóa password ở mysql nhưng connection mặc định do framework merge còn lặp credential ở driver khác, làm hiện mật khẩu tài khoản ứng dụng mới trong đầu ra. Đã thay riêng credential mới này qua root session hợp lệ, đồng bộ private/.env và xóa log local chứa giá trị cũ; không đổi root hoặc account có sẵn. Phòng tránh: chỉ whitelist các trường không bí mật; runner redacts credential đã biết trước ghi log, không lấy config toàn thể.

Node REPL timeout/reset khi chạy chuỗi snapshot+test đã đóng rootPDO trong RAM. Không thử khôi phục mật khẩu hoặc suy còn session. Broker riêng giữ root qua anonymouspipe và chỉ xử lý hai trigger schema đã duyệt; test chạy qua process runner riêng. Khi cần input lại phải nói đúng nguyên nhân, không mở lại chẩn đoán ENV04.

## Dependency trên ổ mạng và ZIP của PHP CLI

G4A: PHP CLI có DLL ZIP nhưng chưa nạp nên Composer chuyển từ dist sang clone source, kéo dài cài đặt. Đã giữ receipt đầu, dừng đúng cây Composer đã xác minh, rồi bật extension=zip chỉ cho lệnh Composer bằng -d; không sửa php.ini hoặc process MySQL. Giải nén nhiều file trên SMB vẫn chậm; theo dõi receipt/progress thật, không chạy hai installer đồng thời trên cùng vendor. Trước cài kiểm ZIP/lock/scripts/plugins và giữ cwd D:, safe.directory chỉ truyền cho tiến trình con.

## Dựng scaffold thay vì clone thật

Lỗi: ban đầu chỉ copy 11 file trung lập và đề xuất schema sáu bảng/role/trạng thái mới quá sớm. Nguyên nhân: diễn giải chọn lọc thành dựng khung mới và tinh gọn nghiệp vụ khi chưa chốt mapping. Sửa: lưu 60 file khung trước theo hash, clone 102 file Laravel thật nguyên byte, thay hồ sơ hiện hành bằng source-first mapping. Phòng tránh: phân biệt chuyển kỹ thuật với đổi nghiệp vụ; chọn module rồi giữ rule/state/formula/relation hoặc trình duyệt thay đổi riêng.

## Ổ mạng và Git ownership

D: là \\CUTE\USBSSD; kiểm Test-Path/net use/Get-PSDrive, không suy Get-Volume thiếu D là mất dữ liệu. Git SID ownership trên SMB chỉ xử lý bằng safe.directory đúng UNC cho từng lệnh; không đổi global hoặc wildcard.

## Bản clone chưa phải runtime độc lập

Giữ byte nguồn nghĩa là Mongo defaults, command klcn186_dev, Composer migrate và scheduler còn tồn tại. Không khởi động/chạy tests/scripts trước bước cô lập cấu hình được duyệt. Hash/syntax không chứng minh MySQL compatibility.

## Composer và working directory UNC

Trong G3, helper resolve D: thành UNC rồi dùng làm cwd khiến tiến trình con CMD của Composer không khởi chạy. Sửa bằng giữ cwd dạng ổ D: cho Composer, chỉ dùng UNC đã xác minh cho safe.directory truyền riêng vào tiến trình Git. Không sửa cấu hình Git/PHP toàn cục; không bỏ qua lỗi Composer để báo đạt.


## MySQL Windows monitor và log không có nhãn ERROR

Lỗi: helper startup G4A nhận PID monitor từ Start-Process rồi kiểm cổng trên PID đó và yêu cầu chỉ1process, nên báo gate fail dù một instance server con đã ready. Sửa bằng readback riêng: kiểm một cây parent/child cùng binary/config, PID con sở hữu3306/33060, TCP greeting8.4.3; không restart. MySQL Windows mặc định có monitor/server, không đếm sốprocess như sốinstance.

Startup stderr có lỗi tải component_reference_cache.dll ở dạng văn bản, không nhãn [ERROR]; validate-config exit0/log rỗng không thay thế kiểm startup log. Không kết luận đạt từ exitcode hoặc ready riêng lẻ. Phải kiểm lỗi Can't open/Cannot load và ghi đúng filelog; không gán lỗi startup cho validation khi chưa tách file. ENV03 phải dừng theo Thy, không sửa/cài DLL để ép chạy khi chưa duyệt.

## Shutdown Windows và probe cổng timeout

ENV04: MySQL native event đã tạo normal shutdown/complete, server và monitor exit0 nhưng Python TCPconnect timeout; helper chỉ chấp nhận ConnectionRefused nên trả gate incomplete. Giữ receipt gốc và readback độc lập Get-NetTCPConnection/Win32_Process: không còn listener3306/33060/process trướccopy, không signal lại hoặc force. Quy tắc: timeout không tương đương refused và không tự chứng minh cổng đóng; dùng bảng TCP/PID Windows cùng exit/log khi xác minh shutdown. Native event chỉ dùng khi Thy cho phép owner shutdown và exact source/PID/owner/ACL đã kiểm; không suy OSrights thành SQLrights.
