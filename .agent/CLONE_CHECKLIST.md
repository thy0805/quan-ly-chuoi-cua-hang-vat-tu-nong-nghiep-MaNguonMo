> Lịch sử G1. Thy đã ACCEPTED hồ sơ và mở G2/G3 + publication ngày17/10; scope/trạng thái mới ở G23_CHECKLIST và G23_ACCEPTED_SCOPE_20261017. Không áp hạn chế cũ thay yêu cầu mới.

# Điều chỉnh theo yêu cầu clone thật

Yêu cầu Thy bổ sung ngày 17/10/2026 thay thế phương án scaffold tối thiểu. Chưa duyệt thay đổi nghiệp vụ hoặc schema sáu bảng trong bản nháp trước.

ALLOWED: Kiểm kê file đã tạo; lưu bản scaffold và tài liệu nháp trong archive; sao chép nguyên byte source Laravel thật vào root mới; bổ sung manifest, mapping và biên bản.
FORBIDDEN: Ghi KLCN/NoSQL; chạy app/DB/migration/seed/test có DB; refactor/chuyển MySQL/Eloquent/Blade; sao chép secret, dependency, runtime, link ngược; commit/push/remote.
SOURCE OF TRUTH: SourceCode/api-laravel thực tế, HEAD và working tree baseline mới trước clone; yêu cầu bổ sung của Thy. Không dùng schema sáu bảng nháp làm nguồn nghiệp vụ.
INVARIANTS: Các file COPIED giữ nguyên SHA-256; mọi file đích bị thay phải có bản lưu khớp hash trước; chỉ khác .env.example/.gitignore và hồ sơ quản lý; toàn bộ route/service gốc chỉ được kế thừa ở trạng thái dormant, chưa chạy.
ACCEPTANCE: Có source controllers/models/services-support/routes/migrations/seeders/tests/composer/lock thực tế; manifest COPIED/ADAPTED/NEW/OMITTED; Git riêng; không kết nối DB; KLCN/NoSQL HEAD/status/hash/index/config không đổi trong phạm vi snapshot.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| C01 | Kiểm kê scaffold, copied/reference và Git | File hiện tại, manifest phase1 | VERIFIED | logs/clone/preexisting-target-inventory.json: 60 file | Chưa chạy DB/migration |
| C02 | Snapshot mới trước clone | Git và file gốc | VERIFIED | logs/clone/before.json | Source319/NoSQL1030/parent2969 hash |
| C03 | Lưu scaffold và clone nguyên byte | Allowlist Laravel gốc | VERIFIED | clone-manifest.json, verification.json: 102/102 và archive60/60 | Không ghi đè chưa lưu |
| C04 | Audit, mapping OLD→NEW, khóa đề xuất chưa duyệt | Code/schema gốc | VERIFIED | docs/AUDIT_KLCN.md, CONVERSION_MAPPING.md và readback | Chỉ xác minh hồ sơ, chưa duyệt thiết kế |
| C05 | Hash/diff/syntax và source before/after | Snapshot + final source | VERIFIED | logs/clone/verification.json PASS17/17; php-lint75/75; source-comparison tất cả true | Không runtime/DB tests |
| C06 | Bàn giao và dừng nghiệm thu | Biên bản | VERIFIED | docs/BIEN_BAN_GIAI_DOAN_1.md; context recovery | Đã chuẩn bị bàn giao, chờ Thy/GPT Web, chưa LOCKED |

Các trạng thái VERIFIED xác nhận checkpoint clone/audit theo evidence đã chạy; không xác nhận thiết kế đích được duyệt hoặc chức năng ứng dụng chạy. Không mở bước chuyển đổi tiếp theo.
