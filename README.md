QUYỀN/TRẠNG THÁI MỚI: Thy đã ACCEPTED G4B source/test/HTTP/MySQL và duyệt checkpoint G4B rồi G5 theo .agent/decisions/G4B_ACCEPTED_G5_AUTHORIZED_20261017.md và G5_CHECKLIST. Các câu G4B chờ nghiệm thu/chưa có quyền push/G5 chưa mở ở phần dưới là checkpoint cũ. Source G5 chưa sửa; đang publication G4B. G5 chưa commit/push, G6 chưa mở.

# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

G4A đã được Thy nghiệm thu và push lên `main`: **61332e5e7236a7b28dd71197b3f3848b14aaff74**. G4B Auth/Authorization/CRUD đã kiểm trên MySQL thật: **26 test / 425 assertions**, gồm11 G4A và15 G4B; thêm24 lượt HTTP qua TCP localhost. G4B còn local, chờ nghiệm thu, chưa commit/push.

Đọc trước: [G4B_RESULT](docs/G4B_RESULT.md) → [HTTP QA](docs/evidence/G4B_HTTP_QA.json) → [source diff](docs/evidence/G4B_SOURCE_DIFF.json) → [fixture integrity](docs/evidence/G4B_FIXTURE_INTEGRITY.json). Source G4B chưa có trên GitHub; ZIP `MNM_NHOM6_G4B_VERIFIED_20261017.zip` cung cấp23 file source/test mới hoặc sửa và hồ sơ đã lọc, đối chiếu trên nền commit G4A.

Repository: [MaNguonMo](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo). Root `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`; database độc lập `mnm_vat_tu_nong_nghiep`. Laravel 13.33/PHP 8.3.30/MySQL 8.4.3; Eloquent/migration/seeder G4A và Laravel Web session/CSRF/Blade tối thiểu G4B.

Admin quản lý tài khoản, danh mục, đơn vị và vật tư; User xem/tìm/phân trang. DELETE chặn tham chiếu; cây/active/decimal/Unicode giữ theo [G2](docs/G2_DECISIONS.md). Demo vẫn3 users/5 categories/3 units/4 products, rowhash và ledger không đổi; AUTO_INCREMENT có thể tiến khi test rollback. Không migrate/seed/reset demo trong G4B.

G5 Bootstrap/giao diện hoàn chỉnh và G6 báo cáo/demo chưa mở. Chưa visual QA/responsive, chưa render UML/ERD; không Shop/POS/checkout/kho/công nợ. Không xem kiểm thử G4B như nghiệm thu toàn bộ sản phẩm môn học. Credential chỉ private ignored, không nằm Git hoặc ZIP.

Điểm tiếp tục: [context](context.md), [PLAN](.agent/PLAN.md), [G4B checklist](.agent/G4B_CHECKLIST.md). Chờ Thy/GPT Web nghiệm thu G4B; không commit/push G4B hoặc tự mở G5.

Nhóm6: Nguyễn Đức Thành Phát, Lý Vũ Nhân Hậu, Nguyễn Văn Minh, Nguyễn Hồ Minh Trọng. [Rubric/phân công](docs/FUNCTIONS_USE_CASES.md).
