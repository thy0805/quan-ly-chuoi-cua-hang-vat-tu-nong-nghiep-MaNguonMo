# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

Môn Lập trình mã nguồn mở. Source Laravel độc lập dựa trên KLCN thật. **G2/G3: đã chốt đặc tả và cô lập runtime; chưa có ứng dụng MySQL/CRUD/Blade chạy được.**

Repo [MaNguonMo](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo). Root `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`. KLCN/NoSQL có source, cấu hình, Git, database riêng và được giữ nguyên.

## Đọc để nghiệm thu

1. [G2_DECISIONS](docs/G2_DECISIONS.md): Admin quản lý/User nhân viên, DEC01–06.
2. [G3_ISOLATION_RESULT](docs/G3_ISOLATION_RESULT.md): thay đổi, kiểm trực tiếp, giới hạn.
3. [ERD_MYSQL](docs/ERD_MYSQL.md):4 bảng nghiệp vụ/3FK, constraint theo module.
4. [CONVERSION_MAPPING](docs/CONVERSION_MAPPING.md), [ARCHITECTURE](docs/ARCHITECTURE.md), [IMPLEMENTATION_PLAN](docs/IMPLEMENTATION_PLAN.md).
5. [FUNCTIONS_USE_CASES](docs/FUNCTIONS_USE_CASES.md): rubric/phân công; chức năng còn TODO.
6. [Manifest G1](docs/evidence/G1_MANIFEST.json), [diff102 file](docs/evidence/SOURCE_DIFF.json), [verification G3](docs/evidence/G23_VERIFICATION.json).
7. [Publication](docs/PUBLICATION.md), [context](context.md), [checklist](.agent/G23_CHECKLIST.md).

## Source G1 và source hiện tại

Commit G1 `87b605b26cda9b8e3210beef807efb130a273a74` lưu102 file Laravel thật +2 mẫu/ignore trước sửa. Manifest lưu SHA filesystem nguyên byte; archive104 giữ local (Git có thể chuẩn hóa newline). G2/G3 giữ15, sửa22, quarantine65 khỏi runtime. Source legacy xem tại Git G1; không gọi như tính năng mới. Biên bản/audit/inventory G1 có nhãn lịch sử;75lint/17gate ở đó thuộc G1. Scaffold6 bảng ban đầu không dùng làm kiến trúc.

## Trạng thái kỹ thuật

Đích: Laravel/MySQL mnm_vat_tu_nong_nghiep/Eloquent/Migration/Seeder/Blade/Bootstrap/Auth session/middleware Admin/User. Chưa triển khai CRUD/Auth/UI; POS/Shop/checkout/kho/lô/Mongo/worker không thuộc đợt đầu.

G3 chỉ MySQL config riêng, storage/mnm, session/cache file riêng, queue null. HTTP503 chủ động; provider từ chối mọi kết nối DB. Artisan chặn lệnh ghi trước autoload; Composer scripts/plugins/discovery khóa. Không .env thật, vendor/node_modules, dữ liệu DB, migration execution hoặc tài khoản mới.

Đã kiểm lint26/26, probe38 assertions, entrypoint12 tiến trình và Composer validate/platform/dry-run3/3. Provider probe dùng test double; chưa boot Laravel/MySQL/browser. Không lấy tests G1 thay kiểm ứng dụng mới.

## Điểm tiếp tục

G2/G3 source đã xuất bản và readback tại `c73608680a9e94d6679e6cd791e2373aca560cb1`; biên bản publication nằm ở commit tiếp theo. Dừng chờ nghiệm thu checkpoint. G4 cần Thy cho phép dependency/runtime/DB riêng trước migrations/seed/Auth/Eloquent/Blade. Không gỡ guard hoặc chạy setup/migration để thử nhanh. Không credential vào Git.

Nhóm6: Nguyễn Đức Thành Phát, Lý Vũ Nhân Hậu, Nguyễn Văn Minh, Nguyễn Hồ Minh Trọng; phân công đề xuất ở FUNCTIONS_USE_CASES.
