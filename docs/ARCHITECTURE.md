# C2 FUNCTIONAL ACCEPTED / LOCKED — 21/10/2026

Thy đã duyệt C2/C2-R:61tests/1095assertions,4race/8workers,15HTTP. Browser1366/768/320 và Print Preview/PDF PENDING. Xem .agent/decisions/C2_FUNCTIONAL_LOCKED_C3_AUTHORIZED_20261021.md. Các trạng thái DRAFT/chưa nghiệm thu bên dưới là lịch sử. Database nghiệp vụ chưa migrate; G7/audit chưa nghiệm thu được giữ ngoài commit.

# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

## C1 hiện hành — 21/10/2026

Kiến trúc mới bổ sung hai bảng kho, InventoryService transaction/khóa product/idempotency và test profile tách riêng. Xem [thiết kế C1](c1/THIET_KE.md).

Nguồn hiện tại [C1_RESULT](C1_RESULT.md) và [C1_VERIFICATION](evidence/C1_VERIFICATION.json). Các trạng thái giữ source G5/4bảng/không kho bên dưới là snapshot trước mở rộng; G6/G7 đã khóa không bị viết lại. Database nghiệp vụ vẫn giữ schema cũ, test schema mới đã kiểm độc lập.

---

## Lịch sử từ checkpoint G6 đã công bố

Cập nhật hiện hành18/10/2026: G6/G6-R đã ACCEPTED/LOCKED, được chốt Git theo decision G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018. Publication đang kiểm, SHA sau push đọc tại context/receipt mới. Sau remote readback sạch chỉ chuẩn bị G7 DRAFT, chưa commit/push G7. Các trạng thái G6 DRAFT/chưa push bên dưới giữ checkpoint cũ; source/nghiệp vụ và accepted G6 content không sửa.

# Kiến trúc hiện hành — G5

Laravel monolith13.33, PHP 8.3.30, MySQL 8.4.3 độc lập `mnm_vat_tu_nong_nghiep`. Thiết kế chuẩn: G2_DECISIONS.md và ERD_MYSQL.md. Schema bốn bảng/ba FK/bảy CHECK/hai trigger giữ nguyên G4A; không sử dụng Next.js/Flutter/PostgreSQL/MongoDB/worker.

Luồng HTTP hiện chạy: public/index.php → Laravel Web middleware (encrypted cookie, file session riêng, CSRF) → auth/active → admin cho thao tác quản lý → FormRequest → Controller/Eloquent + domain G4A → MySQL. Dữ liệu hiển thị bằng Blade/Bootstrap5.3.8 offline; JSON contract cùng session giữ nguyên, không bearer token. G5 thêm GET dashboard có auth+active: Eloquent đếm và đọc vật tư/danh mục/đơn vị hiện hữu; thống kê tài khoản chỉ Admin. Không có ghi dữ liệu trong dashboard.

CatalogResources cố định model/field/search/relation theo ba route categories/units/products. CatalogController dùng query bindings, LOWER/LIKE với wildcard nguồn, phân trang name/id ổn định, per_page1..50. CatalogWriteRequest và model G4A giữ validation/cây/giá/quan hệ; CatalogDeletion khóa bản ghi trong transaction, kiểm phụ thuộc và bắt FK1451. UserController quản lý nhân viên, không mass-assign role/password_hash từ request; mật khẩu qua Hash::make, hash ẩn khỏi JSON/Blade.

Auth dùng username/password_hash/is_active và Laravel session; regenerate khi login, invalidate/regenerate token khi logout. Active middleware đọc lại user mỗi request; Admin middleware kiểm role trước controller/render. Singleton Admin được schema và model G4A bảo vệ. File session/cache/logs nằm storage/mnm, queue null; runtime account chỉ CRUD schema riêng, không root.

G3 HTTP503/DB-deny là checkpoint lịch sử, đã thay theo quyền G4A/G4B. CLI allowlist/isolation identity guard và source quarantine vẫn giữ. Source kế thừa nằm Git G1 và archive local để đối chiếu; không autoload/call module cũ. Kết quả thực thi và giới hạn hiện hành ở G5_RESULT.md; G4B_RESULT.md là checkpoint đã nghiệm thu. G5 không là deployment production hoặc module giao dịch.


G5 assets tại public/assets (Bootstrap CSS/bundle/MIT, app.css/app.js); không CDN runtime hoặc npm build. Components Blade dùng lại status/error/filter/pagination/modal; JavaScript hỗ trợ menu, hiện mật khẩu và xác nhận xóa, nội dung nhãn qua textContent. Bản dịch lang/vi/validation.php theo locale vi hiện hữu, không đổi validation rule/status. Bảng mobile cuộn trong vùng có nhãn và hướng dẫn, không ép tên thành từng ký tự. Chỉ số thống kê không giả doanh thu/tồn kho. Các controller/rule ghi dữ liệu cũ và dependency lock giữ nguyên.

Scaffold Vite/Tailwind cũ trong package.json và welcome.blade.php còn giữ nguyên; không route hiện hành render welcome, Blade G5 không gọi @vite và không cần cài/chạy npm. Đây là tệp kế thừa chưa dùng, không phải frontend đang phục vụ.

Source G5 đã ACCEPTED/LOCKED/publish; G6 chỉ tài liệu, kiến trúc runtime giữ nguyên. ERD runtime và minh họa đọc tại docs/g6; việc render sơ đồ không tạo thêm module hoặc bảng. Receipt commit hiện hành G6_G5_PUBLICATION.
