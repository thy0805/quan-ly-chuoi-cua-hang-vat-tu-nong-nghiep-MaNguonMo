# Kiến trúc hiện hành — G4B

Laravel monolith13.33, PHP 8.3.30, MySQL 8.4.3 độc lập `mnm_vat_tu_nong_nghiep`. Thiết kế chuẩn: G2_DECISIONS.md và ERD_MYSQL.md. Schema bốn bảng/ba FK/bảy CHECK/hai trigger giữ nguyên G4A; không sử dụng Next.js/Flutter/PostgreSQL/MongoDB/worker.

Luồng HTTP hiện chạy: public/index.php → Laravel Web middleware (encrypted cookie, file session riêng, CSRF) → auth/active → admin cho thao tác quản lý → FormRequest → Controller/Eloquent + domain G4A → MySQL. Dữ liệu hiển thị qua Blade tối thiểu; JSON phục vụ kiểm contract cùng session, không bearer token. Bootstrap/UI hoàn chỉnh thuộc G5.

CatalogResources cố định model/field/search/relation theo ba route categories/units/products. CatalogController dùng query bindings, LOWER/LIKE với wildcard nguồn, phân trang name/id ổn định, per_page1..50. CatalogWriteRequest và model G4A giữ validation/cây/giá/quan hệ; CatalogDeletion khóa bản ghi trong transaction, kiểm phụ thuộc và bắt FK1451. UserController quản lý nhân viên, không mass-assign role/password_hash từ request; mật khẩu qua Hash::make, hash ẩn khỏi JSON/Blade.

Auth dùng username/password_hash/is_active và Laravel session; regenerate khi login, invalidate/regenerate token khi logout. Active middleware đọc lại user mỗi request; Admin middleware kiểm role trước controller/render. Singleton Admin được schema và model G4A bảo vệ. File session/cache/logs nằm storage/mnm, queue null; runtime account chỉ CRUD schema riêng, không root.

G3 HTTP503/DB-deny là checkpoint lịch sử, đã thay theo quyền G4A/G4B. CLI allowlist/isolation identity guard và source quarantine vẫn giữ. Source kế thừa nằm Git G1 và archive local để đối chiếu; không autoload/call module cũ. Các kết quả thực thi và giới hạn ở G4B_RESULT.md; không suy Bootstrap, production hoặc module giao dịch đã có.
