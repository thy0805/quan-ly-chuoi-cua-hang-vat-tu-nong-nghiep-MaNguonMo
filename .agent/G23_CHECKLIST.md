# G2/G3 và xuất bản GitHub — 17/10/2026

> Checkpoint lịch sử đã được Thy nghiệm thu về thiết kế/kiểm tĩnh. Quyền hiện hành ở decisions/G4A_AUTHORIZED_20261017.md và G4A_CHECKLIST.md; không dùng lệnh cấm DB G23 để ghi đè quyền G4A mới. Nội dung gate G23 dưới đây giữ nguyên để truy vết.

ALLOWED: Chỉ source và hồ sơ LapTrinhMaNguonModoAN; lưu G1, chốt DEC01–06 và schema từ module, cô lập cấu hình/entrypoint/autoload/commands; kiểm tĩnh không DB; commit riêng G1 và G2/G3; push main tới https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo.git theo bổ sung Thy.
FORBIDDEN: Sửa hoặc stage source/repo cha KLCN, NoSQL, Mobile, Word; tạo/kết nối/ghi DB/migration/seed; triển khai CRUD/Blade hàng loạt; public secret/log/cache/dump/archive/private; force push hoặc đổi lịch sử remote.
SOURCE OF TRUTH: Yêu cầu Thy đính kèm 17/10 và bổ sung GitHub; G1 manifest102, source thật và working tree trước sửa; remote thực tế khi kiểm.
INVARIANTS: Lưu nguyên byte G1 trước sửa; ghi COPIED→ADAPTED/QUARANTINED; không đường gọi runtime tới PG/Mongo; Admin quản lý/User nhân viên; không POS; repo mới độc lập.
ACCEPTANCE: Decision/schema có căn cứ; cấu hình chỉ MySQL riêng, DB execution bị khóa trong G3; CLI/HTTP và route legacy được cô lập; syntax/config/negative tests/Composer kiểm trực tiếp, ghi rõ chưa boot Laravel/MySQL; source protected before/after giữ; stage whitelist/secret scan đạt; remote SHA/tree khớp.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| P01 | Snapshot, G1 hash và Git checkpoint | G1 manifest + disk | VERIFIED | G1 commit87b605b; archive104; source102 khớp trước sửa | Không commit dữ liệu ngoài whitelist |
| P02 | Call graph và DEC01–06/schema | Source KLCN đã clone | VERIFIED | G2_DECISIONS/ERD, call graph43 classes | Đặc tả, chưa migration implementation |
| P03 | Cô lập runtime và dependency | P01/P02 | VERIFIED | G3_ISOLATION_RESULT; diff15/22/65; lock gỡ4 | Không chạy DB |
| P04 | Kiểm ba lớp và diff | Source/checkpoint + config | VERIFIED | lint26,probe38,process12,Composer3; source-comparison toàn true | Chưa boot Laravel thật; schema chưa DDL |
| P05 | Đồng bộ hồ sơ và gói checkpoint | Kết quả kiểm | VERIFIED | Đọc lại23 Markdown, link0 lỗi; ZIP preview32 entry CRC/byte đạt; gói cuối kiểm riêng sau metadata | Hồ sơ G1 là lịch sử đã nghiệm thu |
| P06 | Stage scan/commit/push/readback nguồn | Repo Mã nguồn mở duy nhất | VERIFIED | Sourcec73608680a9e94d6679e6cd791e2373aca560cb1, API tree102/102 khớp, scan102+G1104; working tree clean | Metadata receipt/ZIP cuối kiểm riêng; không force |
