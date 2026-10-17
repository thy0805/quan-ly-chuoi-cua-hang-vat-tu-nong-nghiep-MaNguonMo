# Kiến trúc hiện hành sau G2/G3

Mục tiêu: một Laravel monolith, MySQL riêng, Eloquent, migrations/seeders, Blade/Bootstrap, Auth session và middleware Admin/User. Admin là quản lý, User là nhân viên. G2_DECISIONS.md và ERD_MYSQL.md là nguồn thiết kế.

Hiện có Laravel clone G1 đã cô lập: MySQL-only config, DB-deny provider, HTTP503, CLI allowlist, routes rỗng, không vendor/.env/DB. Chưa runtime hoặc chức năng nghiệp vụ mới.

Đích G4: route web → Auth/session/active + role middleware → FormRequest/policy → service transaction cây/DELETE → Eloquent → MySQL. Blade/Bootstrap dùng session CSRF, tìm kiếm và phân trang; quyền kiểm ở server trước render. Không API Mobile/Next/Mongo/worker/POS đợt đầu.

Source kế thừa được giữ trong Git G1, archive tại máy và manifest, không dùng làm route dormant có thể bị gọi. Quá trình port từng module phải đối chiếu rule với source G1, đặc biệt cha/cycle, active references, DecimalLimit, ID và permission đã đơn giản hóa.
