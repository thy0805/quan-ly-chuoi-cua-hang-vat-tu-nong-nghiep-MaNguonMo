# G3 — kết quả cô lập runtime

Ngày 17/10/2026. Phạm vi được duyệt: thiết kế G2 và cô lập runtime clone; không tạo/kết nối/ghi database. G1 được lưu trước mọi thay đổi, xem docs/evidence/G1_CHECKPOINT.json.

## Thay đổi thực tế

- Trong 102 file G1: 15 COPIED còn nguyên byte, 22 ADAPTED và 65 QUARANTINED khỏi cây runtime. Hai file mẫu/ignore G1 tiếp tục được điều chỉnh riêng. Bảng từng file và SHA ở evidence/SOURCE_DIFF.json; bản G1 nguyên byte104 giữ ở archive riêng trên máy.
- Các controller/service/Mongo/outbox/scanner, middleware token, migration Sanctum, SQL PG, seeders KLCN và tests legacy không còn trong autoload/cây thực thi. Không xóa nguồn đối chiếu: xem Git G1 hoặc archive. Các quy tắc catalog được mô tả trong G2; chưa chuyển thành CRUD mới.
- config/database chỉ còn connection MySQL, tên mặc định mnm_vat_tu_nong_nghiep; không DB_URL hoặc Redis/PG/Mongo fallback. Host chỉ loopback, InnoDB/utf8mb4. Nếu biến môi trường chỉ DB cũ thì provider từ chối khởi động.
- Session/cache/storage/log/mail/cookie riêng: storage/mnm; session file + cookie mnm_nhom6_session, domain null; cache file; queue null; mail array. Không dùng DB cho session/cache/jobs. Không remote mail/filesystem/cors Next.
- RuntimeIsolation được provider gọi trước dùng DB; connector MySQL luôn ném lỗi trong G3. HTTP entrypoint trả 503 cố định, không nạp Laravel. Artisan chặn mọi lệnh ngoài whitelist kiểm tra trước autoload; migrate/seed/worker/scheduler/tinker bị chặn. API route không được đăng ký; web/console không còn nghiệp vụ hoặc scheduler.
- Composer bỏ MongoDB/Sanctum/Tinker; lock bỏ thêm psy/psysh, không đổi version package còn lại. Scripts rỗng, plugins false, auto-discovery bị tắt; không lifecycle migrate/key/seed. Chưa cài vendor hoặc tạo .env. Nền Laravel 13 KLCN vẫn giữ, không tạo Laravel mới.
- Chưa đổi giao diện tài nguyên/assets G1 sang Bootstrap; các assets cũ không được route/entrypoint phục vụ như chức năng. Chưa gọi Laravel Authentication hoặc login/logout thật.

## Kiểm tra thực hiện

| Kiểm | Kết quả | Evidence |
|---|---|---|
| G1 archive nguyên byte | 104/104, trong đó 102 source trước sửa khớp nguồn | G1_CHECKPOINT.json và manifest |
| PHP syntax | 26/26 file active và harness | G23_VERIFICATION.json |
| Config, stale env, driver, path/cookie, DB-deny factory | 38 assertion đạt | ISOLATION_TESTS.json |
| Artisan negative tests và route:list khi chưa vendor | 12 tiến trình exit78 như dự kiến | ENTRYPOINT_TESTS.json |
| Composer validate strict | Đạt, manifest/lock hợp lệ | COMPOSER_CHECKS.json |
| Composer platform --lock | Đạt trên PHP8.3.30 + pdo_mysql hiện có | COMPOSER_CHECKS.json |
| Composer install --dry-run, scripts/plugins tắt | Resolve/install plan đạt; không cài package | COMPOSER_CHECKS.json |
| Protected KLCN/NoSQL/parent | Đối chiếu snapshot trước/sau | PROTECTED_SOURCE.json khi hoàn tất snapshot |

Không bỏ qua lỗi: lần helper chạy Composer bằng UNC bị CMD từ chối; sửa cwd dạng D: và scoped safe.directory cho Git rồi chạy lại đủ ba kiểm Composer. Không chỉnh cấu hình hệ thống. Các cờ Composer được đối chiếu [tài liệu Composer](https://getcomposer.org/doc/03-cli.md).

## Giới hạn và điểm dừng

G3 VERIFIED cho thay đổi tĩnh và kiểm cô lập ở biên entrypoint/config; đây là readiness checkpoint có runtime khóa chủ động. Test ServiceProvider/DB manager dùng test double; chưa xác minh integration thật với Laravel container/package-discovery. HTTP503 được thực thi bằng include entrypoint, chưa mở server/network. Không gọi driver DB, tạo bảng, migration, seeder, account hoặc tests legacy. Không chứng nhận MySQL chạy được, login/CRUD/Blade/Bootstrap đã có.

G4 vẫn TODO và cần nghiệm thu checkpoint hiện tại trước khi mở. G4 phải cài dependency riêng có scripts/plugins bị kiểm soát, kiểm boot Laravel thật dưới DB guard, đối chiếu server/version MySQL và duyệt DB riêng; rồi mới migrations/Eloquent/Auth/CRUD/Blade. Các guard/HTTP503/CLI allowlist của G3 chỉ được gỡ cùng evidence G4; không gỡ để ép app chạy.
