> Hồ sơ lịch sử G1 đã được Thy nghiệm thu về clone/audit ngày 17/10/2026. Số đếm/điểm dừng phản ánh G1; trạng thái hiện hành ở G2_DECISIONS.md, G3_ISOLATION_RESULT.md và README.md.

# Biên bản checkpoint clone + audit — 17/10/2026

**Source mới:** `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`.

**Nguồn Laravel thật:** `D:\Hoctap\KhoaLuanCuNhan\SourceCode\api-laravel`, HEAD `fc514c8a040d7d2804791ceea5a255427f5bf086` của Git SourceCode. Không dùng NoSQL clone làm nguồn.

## Phạm vi bàn giao

- 102 file COPIED nguyên byte từ Laravel KLCN, có manifest SHA-256 source/target: app43/bootstrap2/config13/database18/public4/resources3/routes3/tests8/root8.
- ADAPTED chỉ `.env.example` và `.gitignore`: mẫu kết nối trống, bỏ secret/runtime khỏi Git. Không chỉnh nội dung PHP/route/service/model/SQL/test/composer/lock của 102 file.
- NEW gồm 46 file hồ sơ quản lý/audit/mapping, helpers kiểm kê/clone/check và các file giữ chỗ; manifest provenance liệt kê riêng. Số đếm payload không bao gồm .git, archive và logs/evidence. Chưa có mã nghiệp vụ mới hoặc refactor.
- OMITTED có 15 mục file/thư mục nguồn được ghi lý do; thư mục dependency/runtime được loại ở cấp thư mục, không duyệt/copy từng file con. `.git` nguồn nằm ở repo cha SourceCode và không được đưa sang.
- 60 file scaffold/tài liệu trước điều chỉnh đã lưu nguyên byte tại `.agent/archive/initial-scaffold-20261017/`. Thiết kế sáu bảng/role/states cũ chưa duyệt và đã rút khỏi hồ sơ hiện hành.
- Git mới độc lập đã khởi tạo, không commit/stage/remote/push. Không symlink/junction. Không có Next.js/Flutter/worker directory trong bản sao.

## Trạng thái Git và bảo toàn nguồn

| Repository | HEAD | Trạng thái trước và sau clone |
|---|---|---|
| KLCN SourceCode | fc514c8a040d7d2804791ceea5a255427f5bf086 | 11 dòng dirty có sẵn, gồm 4 modified và 7 untracked Mobile; giữ nguyên |
| NoSQL SourceCode-Mongo | 21288936d172b8485b425c1adc7ca21972da5e48 | Clean, giữ nguyên |
| Mã nguồn mở mới | Chưa có commit, branch main | Các file mới untracked; index rỗng, không remote |
| Repo tài liệu cha | 4ee9b0d1ed52f18cf984b4511cc89df841a4721e | 5.343 dòng dirty cũ sau khi loại riêng thư mục đích; giữ nguyên |

Snapshot source so HEAD, Git status, SHA-256 và Git metadata HEAD/index/config/packed-refs nếu tồn tại: KLCN source 319 file tracked + untracked không ignored; NoSQL source 1.030 file; repo tài liệu cha 2.969 file tracked. Cả ba bộ so sánh trước/sau đều khớp. Không khẳng định toàn bộ file ignored hoặc dữ liệu database đã được kiểm hash.

Thư mục đích nằm trong repo tài liệu cha nên xuất hiện như một mục mới ở Git cha; không sửa ignore/config/index của cha để che nó. So sánh cha chỉ loại đường dẫn `LapTrinhMaNguonModoAN/` và vẫn giữ đầy đủ dirty cũ.

## Kiểm chứng cuối

**VERIFIED trong phạm vi clone + audit, chưa được Thy nghiệm thu LOCKED.** Gate kiểm trực tiếp đạt 17/17:

1. Cấu trúc: đủ 102 file ứng dụng kế thừa, manifest/provenance, thư mục và Git riêng; không .env thật/dependency/runtime data/link.
2. Ngữ nghĩa: 102/102 SHA nguồn và đích khớp; controllers/services/validation/permission/routes/SQL/Composer giữ nguyên; audit và mapping đối chiếu code/SQL thật. Thiết kế sáu bảng cũ bị rút khỏi nguồn chuẩn.
3. Artifact/readback: 60/60 file scaffold lưu trữ khớp; PHP CLI 8.3.30 chạy `-n -l` đạt 75/75 file PHP/Artisan; link tài liệu nội bộ hợp lệ, tài liệu hiện hành được đọc lại. Source snapshot trước/sau đều khớp.

Lệnh kiểm: Python `.agent/scripts/verify_clone.py`; đọc lại tài liệu bằng `--documents-only` sau cập nhật checkpoint, không chạy lại PHP lint khi mã không đổi. Kết quả ở `.agent/logs/clone/verification.json`, `php-lint.json`, `document-readback.json`, `file-provenance.json`, `new-git-status.json`. Sơ đồ Mermaid/PlantUML mới kiểm source, chưa render; không xác nhận bố cục hình cuối.

Evidence nguồn: `.agent/logs/clone/before.json`, `after.json`, `source-comparison.json`, `clone-manifest.json`, `preexisting-target-inventory.json`. Mã gốc và dirty Mobile, Git index/config của các nguồn được xác nhận giữ nguyên trong phạm vi snapshot.

## Chưa làm và chưa được kết luận

Chưa chạy Composer/Artisan/tests/server/scheduler hoặc kết nối bất kỳ DB nào; chưa tạo MySQL database/migrate/seed. PHP lint là kiểm cú pháp, không chạy ứng dụng. Không sửa hay kiểm lại Mobile M01 runtime/checkpoint gốc.

Code clone còn PostgreSQL/MongoDB/Sanctum, setup migrate, command ghi klcn186_dev và scheduler. Chưa cô lập runtime, chưa MySQL/Eloquent/Blade/Bootstrap; không được boot thử với env nguồn. Composer/lock được copy để đối chiếu, chưa resolve/cài dependency.

## Điểm nghiệm thu

Thy/GPT Web nghiệm thu **bản sao Laravel KLCN thật, độc lập về file/Git, giữ nguyên code**. Sau đó duyệt DEC01–06 trong [CONVERSION_MAPPING](CONVERSION_MAPPING.md) trước chuyển từng module.

Hồ sơ có [audit và REUSE/REWRITE/OMIT](AUDIT_KLCN.md), [file đã copy](REUSED_FILES.md), [kiến trúc](ARCHITECTURE.md), [ERD nguồn và hướng MySQL](ERD_MYSQL.md), [rubric/UML/phân công](FUNCTIONS_USE_CASES.md), [kế hoạch](IMPLEMENTATION_PLAN.md).

**Dừng tại checkpoint clone + audit, chờ Thy/GPT Web duyệt.** Không tự tiếp tục chuyển đổi hoặc ghi DB.
