# G2 — quyết định kiến trúc theo phạm vi môn học

Ngày 17/10/2026. Thy ACCEPTED G1 trong phạm vi hồ sơ clone + audit; số 102/102 SHA và gate17/17 là evidence Codex, GPT Web chưa kiểm trực tiếp source gốc. Yêu cầu mới cho phép G2/G3 và xuất bản repo riêng, chưa cho phép tạo hoặc ghi DB.

## DEC01 — Admin quản lý, User nhân viên

Admin quản lý tài khoản, danh mục, vật tư và đơn vị tính. User đăng nhập, xem danh mục/đơn vị, tìm vật tư và phân trang; mọi thao tác ghi phải kiểm quyền ở server. Đây là đơn giản hóa quyền đã được Thy duyệt, không tương đương quyền chain_owner/branch_manager/sales_staff hay phạm vi chi nhánh KLCN.

Một user có đúng một role `admin` hoặc `user`; lưu enum ngay trong users vì chỉ có hai vai trò cố định, không có cấp quyền theo tổ chức. Giữ nguyên giới hạn tối đa một tài khoản quản trị danh mục từ unique partial index G1 bằng `admin_slot` generated unique; không gỡ giới hạn này ngầm. Admin bị vô hiệu hóa vẫn chiếm slot. Khi xây tài khoản phải chặn vô hiệu hóa/hạ quyền Admin cuối cùng; đổi số lượng Admin cần quyết định riêng. Không có tài khoản khách hàng, tự đăng ký, nhớ đăng nhập hay đặt lại mật khẩu trong phạm vi đầu.

## DEC02 — giữ Auth và catalog, hoãn tồn kho/giao dịch

Giữ users, cây product_categories, units, products và quy tắc validation có nguồn. Tài khoản bổ sung role theo DEC01. Bốn bảng là kết quả chọn module, không phải ép số bảng trước audit.

Không giữ chains/branches/warehouses, phân công vai trò theo chi nhánh, product_lots/inventories/movements hoặc chứng từ trong schema đợt đầu: chúng phục vụ tổ chức/tồn/giao dịch đã hoãn, không được CatalogWriteController dùng trực tiếp. Không bịa cột số lượng tồn ở products khi bỏ lô/kho. expiry_warning_days vẫn là thuộc tính vật tư; không tuyên bố có cảnh báo tồn/hạn dùng tự động.

## DEC03 — tách dependency theo đường gọi

Đã đối chiếu import, controller, route và bảng được đọc/ghi, không chỉ xóa package:

| Đường gọi G1 | Nguồn phụ thuộc | Xử lý |
|---|---|---|
| CatalogController → BranchAccess | assignments/roles/branches/chains | Quarantine route/controller/access; G4 thay kiểm quyền Admin/User rõ ràng |
| CatalogWriteController → CatalogPermission | active + catalog admin + chain_owner assignment | Quarantine; giữ rule giá/cha/cycle để chuyển FormRequest/service G4 |
| CatalogWriteController → DecimalId/DecimalLimit | Validation, không DB/Mongo | Hai helper giữ nguyên byte trong app/ |
| AuthController → Auth → User → HasApiTokens | Session có thể tách được khỏi Sanctum | Bỏ token trait/dependency; giữ username/password_hash/is_active; login/logout chưa kích hoạt |
| ProductContentController → ProductContentStore | MongoDB Client | Quarantine toàn nhánh |
| NotificationController → NotificationStore | MongoDB Client | Quarantine toàn nhánh |
| Sales/Purchase/Transfer/Debt services → OutboxService | PostgreSQL/outbox, nhiều bảng giao dịch | Quarantine; không phải phụ thuộc trực tiếp của catalog |
| console alerts:scan → InventoryAlertScanner → OutboxService | scheduler mỗi phút + DB write | Gỡ command/schedule và nguồn khỏi autoload runtime |
| DemoDataSeeder / klcn:create-user | guard klcn186_dev, ghi DB | Quarantine; không gọi và không chuyển thành seed thật |

Toàn bộ G1 được giữ qua Git checkpoint và archive104 khớp SHA trên máy; 65 file được đưa khỏi cây runtime, không mất nguồn đối chiếu. Call graph và danh sách từng file ở docs/evidence/. Bản Git G1 cho phép GPT Web xem source kế thừa; archive/private/log không public.

## DEC04 — không POS/checkout đợt đầu

Không bật bán hàng, hóa đơn, kho/lô, công nợ, Shop, giỏ hàng hoặc lịch sử đơn. Shop G1 không có checkout thật. Sau Auth/CRUD đạt mới xét phạm vi bán hàng riêng; không thay luồng draft→confirmed thành quy trình bán online.

## DEC05 — DELETE có bảo toàn tham chiếu

Đây là chức năng bổ sung cho rubric, chưa có implementation G3. Xóa vật lý chỉ Admin; CSRF/session/active account và validation ID tại server. Category có con hoặc product tham chiếu, kể cả inactive, phải bị từ chối. Unit có product phải bị từ chối. Product chỉ xóa khi không có phụ thuộc; schema G2 chưa có bảng giao dịch tham chiếu product, khi thêm module phải cập nhật guard trước mở DELETE. FK dùng RESTRICT, không cascade hoặc tự chuyển cha.

Thực hiện kiểm phụ thuộc trong transaction; bắt lỗi FK khi có ghi đồng thời. `is_active=false` là ngừng hoạt động, không xóa, không tự làm mất con. Test G4 bắt buộc: guest/User/inactive ghi bị chặn; xóa category lá rỗng đạt; có con/product thì bị chặn; product không phụ thuộc xóa đạt; unit có product bị chặn; race tạo tham chiếu→FK không mất dữ liệu; inactive không biến thành DELETE.

## DEC06 — MySQL theo bốn bảng được giữ

Nguồn chuẩn schema: ERD_MYSQL.md. MySQL 8.4, InnoDB/utf8mb4, BIGINT signed tương thích DecimalId. Ba FK gồm self parent và product→category/unit; unique code/username; khoảng giá/thuế/hạn dùng giữ. Quy tắc cây tự trỏ cần trigger thay CHECK trực tiếp trên auto_increment, còn vòng nhiều cấp giữ service transaction/lock như KLCN. Không bỏ ràng buộc vì khác dialect.

Eloquent dự kiến: User Authenticatable; ProductCategory parent/children/products; Unit products; Product category/unit. Catalog models timestamps=false vì nguồn không có timestamp; User chỉ có created_at UTC. Không viết hàng loạt models/migrations trong G3.

## Kế hoạch migration/seeder G4

Sau nghiệm thu G2/G3 và duyệt quyền DB riêng: migration users, categories, units, products; unique/FK/CHECK, generated Admin slot và self-parent triggers có rollback. Kiểm server MySQL thật trước chạy; không dùng SQLite để chứng nhận MySQL. Chỉ sau đó seed synthetic: một Admin demo, hai nhân viên demo, vài đơn vị, cây danh mục và vật tư biên giá/thuế. Không dùng dữ liệu/tài khoản KLCN. Mật khẩu demo được cung cấp riêng khi thực thi seed, không đưa vào repo; không tài khoản nào được tạo trong G2/G3.

G2 VERIFIED cho đặc tả/đối chiếu source, không phải migration/runtime. Việc thực thi schema, bảo vệ DELETE và Auth/Blade/Bootstrap vẫn TODO G4.
