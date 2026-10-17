# Kế hoạch hiện hành sau chốt phạm vi G2/G3

| Giai đoạn | Trạng thái | Điều kiện tiếp tục |
|---|---|---|
| G1 clone/audit | ACCEPTED hồ sơ theo Thy; evidence hash đã kiểm local | Giữ manifest và Git G1 |
| G2 quyết định/schema | VERIFIED đặc tả theo DEC01–06 | Thy/GPT Web nghiệm thu hồ sơ hiện tại |
| G3 runtime isolation | VERIFIED kiểm tĩnh/biên, chưa boot Laravel/MySQL | Chưa được gỡ guard tự ý |
| G4 migrations/Eloquent/Auth/catalog | TODO | Duyệt checkpoint, cấu hình/dependency/DB riêng và evidence boot; rồi mới thực thi DB |
| G5 Blade/Bootstrap và QA MySQL/browser | TODO | Auth/CRUD đủ test quyền, cây, giá, DELETE race và parity |
| G6 báo cáo/demo | TODO | Rubric gốc, ERD/Use Case render, chứng minh chức năng thật, dữ liệu demo synthetic |

G4 đi theo module: (1) nền dependency/boot/config isolation; (2) migrations/FK/constraint và synthetic seed; (3) Auth/role/active; (4) category/unit; (5) product/search/pagination/DELETE; (6) UI và regression. Mỗi bước có test MySQL thật phù hợp, không dùng SQLite để xác nhận dialect MySQL. Không coi G1 tests legacy đã pass là test ứng dụng mới.

Mốc publication GitHub chỉ chuyển source/hồ sơ đã kiểm lên repo riêng, không mở quyền G4 hoặc runtime/database.
