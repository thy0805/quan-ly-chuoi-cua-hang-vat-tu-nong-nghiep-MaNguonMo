# G2/G3 nghiệm thu và phạm vi G4A — 17/10/2026

Nguồn: Thy gửi văn bản nghiệm thu qua attachment ngày 17/10/2026, đối chiếu checkpoint `21c8842027c510ec4db0d09b859ccd522f8785de` và ZIP G2/G3 SHA-256 `b1774440c3da8d1d80b48c9c66c3cf2304ff9364443fbe81e2cdbbd7143d9d81`.

G2/G3 ACCEPTED/LOCKED trong phạm vi thiết kế và kiểm tĩnh/cô lập; không phải nghiệm thu ứng dụng chạy thật. Được mở G4A: kiểm MySQL 8.4 và quyền quản trị hợp lệ, provision duy nhất `mnm_vat_tu_nong_nghiep`, dependency theo lock, Laravel boot riêng, migration/seeder tối thiểu, Eloquent và kiểm trên MySQL thật. Giữ một Admin theo generated unique admin_slot; nếu không bảo toàn được phải trình Thy.

Không sửa KLCN/NoSQL/Flutter/Word/Rose; không kết nối DB KLCN/NoSQL; không DROP/TRUNCATE/migrate dữ liệu ngoài manifest QA. Không mở Auth/CRUD G4B, Blade/Bootstrap G5. Không tự commit/push sản phẩm chưa nghiệm thu. Mật khẩu demo truyền an toàn, không vào Git. SQLite không chứng minh MySQL.

Điểm dừng được Thy yêu cầu: “Nếu môi trường MySQL hoặc quyền provision không đáp ứng, dừng với blocker và hướng xử lý cụ thể.” Tại preflight đầu (lịch sử), chưa có server MySQL chạy; giữ G3, chưa cài dependency hay triển khai migration/model/seeder. Kết quả mới sau Thy duyệt direct-start và bước tiếp ở docs/G4A_RESULT.md; decision G4A_DIRECT_MYSQL_AUTHORIZED_20261017.md bổ sung quyền startup.
