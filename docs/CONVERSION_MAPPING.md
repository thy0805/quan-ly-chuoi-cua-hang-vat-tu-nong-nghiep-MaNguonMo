# Mapping hiện hành G2/G3 — source G1 đến runtime môn học

DEC01–06 đã được Thy chốt trong yêu cầu 17/10; xem G2_DECISIONS.md. G1 source lưu trong Git checkpoint, không tạo Laravel mới.

| OLD G1 | G2/G3 hiện tại | G4 còn làm |
|---|---|---|
| chain/branch roles, is_catalog_admin | Đã chốt Admin quản lý/User nhân viên; giữ tối đa một Admin | Middleware/policy/role enum và unique slot |
| PG DB config + Mongo clients/default klcn186_dev | MySQL-only riêng; source legacy quarantine; DB connector deny | Server MySQL thật và quyền DB riêng trước bật |
| CatalogWrite/Read + BranchAccess/CatalogPermission | Source giữ ở Git/archive, route cũ gỡ; DecimalId/Limit nguyên byte | Eloquent/FormRequest/service theo validation và cây G1 |
| User Sanctum token | User bỏ trait token, giữ password_hash/is_active | Auth session/active/role, login/logout Blade |
| Cây category + unit + product | Schema G2 giữ parent/category/unit, giá/thuế/active | 3 FK, constraint/trigger và parity MySQL |
| Chưa DELETE catalog | Thiết kế bổ sung restrict, không cascade | Negative/race/FK/permission tests MySQL |
| Scheduler/scanner/outbox/POS/kho/lô | Quarantine khỏi autoload/commands/routes | Ngoài đợt đầu; không dựng tồn kho giả |
| Composer scripts migrate + discovery/plugin | Scripts rỗng, plugins tắt, discovery tắt, lock gỡ4 package | Cài dependency riêng và kiểm boot có guard |
| Shop mock/Next/Flutter | Không chức năng chạy môn học | Ngoài phạm vi, cần quyết định riêng |
| Default welcome/Tailwind assets | Giữ tham chiếu source, không reachable | Blade/Bootstrap đúng rubric sau CRUD/Auth |

Từng file COPIED/ADAPTED/QUARANTINED và SHA: evidence/SOURCE_DIFF.json. G2 không đổi công thức giá hoặc trạng thái POS: POS chưa được chuyển; không tuyên bố đã có Eloquent/Blade/Bootstrap hoàn chỉnh.
