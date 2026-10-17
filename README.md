# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

**G4A: VERIFIED trong phạm vi G4A; chờ Thy nghiệm thu.** Laravel 13.33.0/MySQL 8.4.3 đã boot,migrate,seed và kiểm11 tests/131 assertions trên database độc lập. Rollback đúng bốn migration đạt; readback chỉ còn ledger migrations rỗng và0trigger; remigrate/reseed rồi11 tests/131 assertions cuối đạt.

Đọc [G4A_RESULT](docs/G4A_RESULT.md),[MySQL QA](docs/evidence/G4A_MYSQL_QA.json),[source diff](docs/evidence/G4A_SOURCE_DIFF.json),[protected verification](docs/evidence/G4A_PROTECTED_VERIFICATION.json). Một ZIP `MNM_NHOM6_G4A_VERIFIED_20261017.zip` chứa hồ sơ/code G4A để gửi GPT Web. Chưa commit/push G4A.

Repo riêng: [MaNguonMo](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo). Checkpoint GitHub đã kiểm trước đó là21c8842027c510ec4db0d09b859ccd522f8785de; code G4A mới nằm local/ZIP, chưa push và không liên hệ remote trong lượt này.

Root `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN`;database `mnm_vat_tu_nong_nghiep`. KLCN/NoSQL độc lập,không bị sửa theo protectedreadback. G1/G2/G3/ENV03/ENV04 LOCKED theo phạm vi;[G2_DECISIONS](docs/G2_DECISIONS.md)/[ERD_MYSQL](docs/ERD_MYSQL.md) giữ thiết kế4 bảng/3 FK/1 Admin. G1source giữ tại commit87b605b và archive local;không lấy testlegacy chứng nhận runtime mới.

Hiện có migration,Eloquent,validation domain,synthetic seed và testMySQL. HTTP vẫn503;Auth/CRUD HTTP/search/pagination/Blade/Bootstrap chưa triển khai. Không POS/Shop/checkout/kho/lô/Mongo/worker trong phạm vi đầu. Credential chỉ private ignored,không gửi qua chat hoặc Git/ZIP.

Điểm tiếp tục: [context](context.md),[PLAN](.agent/PLAN.md),[checklist](.agent/G4A_IMPLEMENTATION_CHECKLIST.md). Không chạy migrate:fresh hoặc seeder đè dữ liệu. Rollback chỉ fixture mới có exact-set/rowhashguard;triggerDDL cần phiên quản trị hợp lệ theo [decision](.agent/decisions/G4A_TRIGGER_ADMIN_DDL_20261017.md). Dừng nghiệm thu trước G4B/G5/commit/push.

Nhóm 6: Nguyễn Đức Thành Phát,Lý Vũ Nhân Hậu,Nguyễn Văn Minh,Nguyễn Hồ Minh Trọng. Phân công/rubric ở [FUNCTIONS_USE_CASES](docs/FUNCTIONS_USE_CASES.md).
