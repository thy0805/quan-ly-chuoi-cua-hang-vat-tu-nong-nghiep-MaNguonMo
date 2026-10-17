# ENV03_DIAGNOSTIC — MySQL 8.4.3 Windows x64

Ngày 17/10/2026. Trạng thái: chẩn đoán chỉ đọc; G4A vẫn BLOCKED tại gate môi trường/quyền. Chưa sửa MySQL, chưa restart/shutdown, chưa SQL/provision/migration/seed, chưa thay source runtime, chưa commit/push. Hồ sơ này thay phần “nguyên nhân chưa chẩn đoán” trong G4A startup, không thay nghiệm thu G2/G3.

## Kết luận và giới hạn

Đường dẫn MySQL thực sự thử nạp là `C:/laragon/bin/mysql/mysql-8.4.3-winx64/lib/plugin/component_reference_cache.dll`. File này không tồn tại vì toàn bộ `lib` không tồn tại. Đây là bằng chứng file đích thiếu; không phải chỉ suy từ errno126 rằng dependency thiếu. Không tìm thấy override plugin_dir trong my.ini đã chọn; đường dẫn log khớp layout của package chính thức. Không có bằng chứng phải đổi plugin_dir/my.ini. Chưa truy vấn SQL `@@plugin_dir` hoặc `@@datadir`.

126 file cài sẵn có tên tương ứng trong package Oracle đều khớp SHA-256, bao gồm mysqld.exe. Không phát hiện file trùng tên nhưng sai hash. Bộ cài có tổng127 file:126 từ package và my.ini local. Package chính thức có297 file; thiếu171 file, gồm166 file trong lib và5 file PDB. Không suy rằng cả171 file đều bắt buộc cho ứng dụng hoặc cả server hỏng. Chưa xác định ai/thao tác nào làm lib bị thiếu; có thể là bản phân phối rút gọn hoặc giải nén/sao chép thiếu, chưa có lịch sử chứng minh.

Source đúng tag8.4.3 gọi nạp reference-cache sau khi khởi tạo đường dẫn plugin; thành phần này tùy chọn và bên sử dụng kiểm khả dụng. Vì vậy server vẫn có thể ready dù DLL tải thất bại; validate-config cũng bỏ qua đoạn load. Đây là giải thích hành vi, không phải quyết định bỏ component. Nhánh ICU kiểm thư mục dữ liệu và unames.icu rồi cảnh báo nếu thiếu. Nguồn: [mysqld.cc tag mysql-8.4.3, dòng7677–7803 và13023–13029](https://github.com/mysql/mysql-server/blob/mysql-8.4.3/sql/mysqld.cc), [target component](https://github.com/mysql/mysql-server/blob/mysql-8.4.3/components/reference_cache/CMakeLists.txt).

## Server hiện hành

| Hạng mục | Đọc trực tiếp |
|---|---|
| Máy chạy MySQL | C:/laragon trên Windows hiện tại; D: là ổ mạng chứa source |
| Process | Monitor18852 → server33712; cùng executable/config; giữ nguyên từ startup |
| Instance/service | 1instance; không có Windows Service MySQL/MariaDB |
| Port | 3306 và33060 thuộc33712, listen `::`; TCP127.0.0.1:3306 đọc greeting8.4.3, không gửi xác thực/SQL |
| Binary | bin/mysqld.exe; FileVersion/ProductVersion8.4.3.0; Authenticode Valid, Oracle America, Inc. |
| Datadir | my.ini trỏ C:/laragon/data/mysql-8.4; thư mục tồn tại; không đọc nội dung DB, chưa xác nhận qua SQL |
| my.ini SHA-256 | a0795e25b68c1fd804888087fb3bb9227fd580a5851e8d7b2fb657453ea8c12e |
| mysqld SHA-256 | ee6b6ba65d99bcea50b6605bc0650588c4d5b91b11aefcf4d9e5bd2c12e07d8e |
| Phiên/quyền/DB riêng | Chưa xác thực; không biết DB mnm_vat_tu_nong_nghiep đã tồn tại hoặc quyền/ownership |

Xem [readback](evidence/ENV03_READBACK.json), [startup đã lọc](evidence/G4A_STARTUP_LOG_SUMMARY.json) và [verification cuối](evidence/ENV03_VERIFICATION.json). Cảnh báo secure-file-priv và CA self-signed vẫn giữ; không sửa cấu hình để làm log sạch. Không chứng nhận tính toàn vẹn datadir từ việc server ready. Server có thể tự ghi dữ liệu nội bộ trong vận hành bình thường; agent không ghi trực tiếp datadir hoặc phát SQL.

## Package tham chiếu và độ tin cậy

Nguồn: [Oracle archive MySQL8.4.3 Windowsx64](https://cdn.mysql.com/archives/mysql-8.4/mysql-8.4.3-winx64.zip), [MD5](https://cdn.mysql.com/archives/mysql-8.4/mysql-8.4.3-winx64.zip.md5), [chữ ký](https://cdn.mysql.com/archives/mysql-8.4/mysql-8.4.3-winx64.zip.asc), [public key Oracle](https://repo.mysql.com/RPM-GPG-KEY-mysql-2023). Lựa chọn layout theo [tài liệu Windows ZIP](https://dev.mysql.com/doc/refman/8.4/en/windows-choosing-package.html); đối chiếu cụ thể dựa trên inventory ZIP đã đọc, không suy từ package phiên bản khác.

- Archive259751071byte; SHA-256 `e401d0420486d91bd14eda7b6b449498007863ca340991e529adff4787352e7e`.
- MD5 khớp Oracle: `28c56dda41e66c07512f2de5d0b9c8dc`.
- GnuPG trả VALIDSIG fingerprint `BCA43417C3B485DD128EC6D4B7B3B788A8D3785C`; chữ ký18/09/2024. Key archived hết hạn22/10/2025, nên hiện có EXPKEYSIG/KEYEXPIRED. Không bỏ cảnh báo hoặc sửa ngày; chữ ký đúng về mật mã, không mô tả key là còn hạn. [Hướng dẫn GPG của Oracle](https://dev.mysql.com/doc/refman/8.4/en/checking-gpg-signature.html).
- DLL ứng viên có Authenticode Valid, Oracle America, Inc., version8.4.3.0. SHA server hiện có khớp byte package; không cần thay bin để khắc phục file thiếu đã quan sát.
- Không có package local tin cậy tìm được bằng tìm tên trong Downloads, Laragon/tmp, Laragon/bin/mysql và Laragon. Không coi đây là quét toàn máy.

Archive và44 file ứng viên chỉ nằm trong `.agent/private/env03/`; không thực thi DLL hoặc chép vào Laragon. ZIP bàn giao chỉ chứa hồ sơ, không chứa archive259MB, DLL, credentials hoặc raw logs. [Package evidence](evidence/ENV03_OFFICIAL_PACKAGE.json), [inventory297 file](evidence/ENV03_PACKAGE_INVENTORY.json).

## Dependency

PE của DLL chính thức là AMD64/0x8664, export list_components, version8.4.3.0. Import trực tiếp: MSVCP140.dll, VCRUNTIME140.dll, VCRUNTIME140_1.dll, KERNEL32.dll và4 hợp đồng api-ms-win-crt. Bốn DLL vật lý có trong System32; VC runtime14.51.36247.0, UCRT10.0.26100.9444. Không thấy dependency import trực tiếp nào yêu cầu thêm file từ lib/plugin. Không LoadLibrary, không chạy component; API-set, ABI, forwarders và phụ thuộc động/transitive chưa được xác nhận bằng loader. Không kết luận “mọi dependency runtime đều đạt”. Xem [PE inspection](evidence/ENV03_DEPENDENCIES.json).

## Phương án tối thiểu — CHƯA THỰC HIỆN

| Phương án | File/byte | Mục tiêu |
|---|---|---|
| A | 1file /72304byte | Khôi phục lib/plugin/component_reference_cache.dll cho lỗi component |
| A+B đề xuất | 44file /3837376byte | A và43file dữ liệu ICU nguyên bộ lib/private/icudt73l,3765072byte, cho cả hai thông báo thiếu file đã quan sát |

DLL A: SHA-256 `ca3503295026c7ec00c98291cf57924f49cbd066f806ba878e59cbfa29f8f180`, version8.4.3.0. Nguồn `.agent/private/env03/official-files/lib/plugin/component_reference_cache.dll`; đích `C:/laragon/bin/mysql/mysql-8.4.3-winx64/lib/plugin/component_reference_cache.dll`. Không dùng DLL trong lib/plugin/debug. Nhóm B giữ nguyên43 đường dẫn tương đối từ package, không tạo một file ICU giả để qua gate kiểm thư mục.

[ENV03_REPAIR_MANIFEST.json](evidence/ENV03_REPAIR_MANIFEST.json) là whitelist chính xác44file, đầy đủ nguồn staging/entry archive/đích/version/bytes/SHA-256. Không bao gồm122 file lib khác hoặc5 PDB; không thay toàn bộ server. Phương án này xử lý file thiếu đã quan sát, không khẳng định phục hồi đầy đủ mọi tính năng/plugin của package.

Không có lý do kỹ thuật từ evidence hiện tại để đổi my.ini/datadir/registry/ACL/tài khoản/mật khẩu: path mặc định khớp package, file đích thiếu, server/bin khớp bản chính thức, dependency trực tiếp đã hiện diện. Hiệu lực sửa lỗi cuối chỉ xác nhận được sau phục hồi/restart được duyệt; nếu lỗi mới thì dừng, không tự mở rộng whitelist hay sửa config.

### Điều kiện, backup, shutdown và rollback khi được duyệt riêng

1. Thy duyệt rõ A hay A+B, quyền tạo các thư mục đích còn thiếu và một chu kỳ shutdown/start. Kiểm lại binary/config/PID/hash, file đích vẫn absent, đường dẫn tuyệt đối trong đúng bộ cài, không reparse/junction. Nếu file đích xuất hiện hoặc hash nguồn đổi thì dừng; không ghi đè.
2. Lưu backup my.ini và inventory/hash127 file cài sẵn cùng receipt “đích absent”, process/config/port và log trước thao tác vào vùng private. Giữ package đã xác minh; không thay file cài sẵn nên rollback chủ yếu là loại bỏ đúng file mới tạo. Đây không phải backup dữ liệu MySQL. Nếu cần backup dữ liệu trước restart, phải có phương án nhất quán đã được chủ dữ liệu duyệt và quyền hợp lệ; không sao chép datadir đang chạy rồi gọi là backup nhất quán, không tự dump DB khác.
3. Chỉ shutdown có kiểm soát qua mysqladmin của chính bộ cài bằng tài khoản quản trị hợp lệ đã đăng nhập cục bộ, tùy chọn --no-defaults/--no-login-paths, TCP127.0.0.1:3306, mật khẩu nhập tương tác (-p), không ghi vào dòng lệnh/log. Không dò password hoặc đọc credential store. Chưa có phiên đó; không có phiên thì STOP. Không taskkill/Stop-Process/force/UAC bypass. Đợi server và monitor thoát, cổng3306/33060 đóng, log xác nhận shutdown xong; nếu không thì dừng.
4. Khi server dừng và các gate đạt, chỉ phục hồi file missing trong whitelist được duyệt, giữ đường dẫn tương đối và kiểm hash từng file. Không giải nén đè installation; không thay bin/config/datadir hoặc ACL. Nếu không có quyền ghi thì dừng. Không có thao tác INSTALL COMPONENT/system schema, initialize hoặc nâng cấp.
5. Khởi động đúng một instance bằng binary/my.ini/datadir hiện hữu như lần đã duyệt; kiểm PID tree, port/version và log mới riêng. Xác nhận không còn lỗi tải reference-cache; với A+B xác nhận cảnh báo ICU không còn. Nếu cần kiểm load/module hoặc biến runtime, dùng phiên hợp lệ và scope riêng; không phát SQL nghiệp vụ. Những cảnh báo bảo mật ngoài scope vẫn báo nguyên trạng.
6. Nếu không đạt: ghi log, không sửa lan. Khi có quyền shutdown hợp lệ, dừng an toàn, kiểm hash và ownership của từng file vừa tạo; chỉ gỡ chính các file đó nếu vẫn khớp receipt. Không xóa đệ quy, không xóa thư mục đã có trước; chỉ gỡ thư mục mới tạo nếu trống và nằm trong đường dẫn đã kiểm. my.ini/bin/datadir không đổi; không rollback datadir bằng bản sao cũ vì có thể mất ghi hợp lệ. Khi rollback thay đổi file còn chưa rõ hoặc không có quyền shutdown thì dừng chờ Thy. Rollback quay về tình trạng lỗi ban đầu, không chứng nhận G4A đạt.

## Bảo toàn và bàn giao

Phạm vi xác minh bảo toàn:127 file installation cùng tập đường dẫn/hash;68 file source/runtime từ baseline G4A; Git HEAD/status/index/config/packed-refs của KLCN/NoSQL/repo cha theo snapshot ENV03. Không quét hash toàn bộ nội dung KLCN/NoSQL, vì vậy Git-status không được gọi là bằng chứng mọi byte toàn workspace. Các file Mobile KLCN đã dirty trước phiên được giữ nguyên trạng thái. Không chạm PostgreSQL/MongoDB, không gửi kết nối SQL, không sửa product source. [Verification cuối](evidence/ENV03_VERIFICATION.json) ghi số lượng và giới hạn.

G1/G2/G3 vẫn LOCKED theo phạm vi nghiệm thu trước. G4A dependency, DB riêng, migration/model/seeder, quyền và QA MySQL vẫn TODO; G4B/G5 chưa mở. Local main giữ21c8842027c510ec4db0d09b859ccd522f8785de; remote SHA này được readback ở checkpoint trước, không fetch/push trong ENV03.

Bước tiếp theo: Thy/GPT Web đọc hồ sơ và whitelist, duyệt phạm vi sửa môi trường riêng. Chưa thực hiện phục hồi. Sau môi trường được nghiệm thu vẫn cần phiên quản trị hợp lệ trước kiểm quyền/ownership và provision riêng mnm_vat_tu_nong_nghiep.
