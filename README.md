# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

G5 Blade/Bootstrap/dashboard đã được Thy/GPT Web **ACCEPTED/LOCKED** trong phạm vi source/test/HTTP/browser QA. Được duyệt publication riêng G5 trên `main`; kiểm trước commit tại [G5_PUBLICATION_PREFLIGHT](docs/evidence/G5_PUBLICATION_PREFLIGHT.json), SHA checkpoint xem lịch sử Git. Nền G4B411aa5d716c1162a836dd35eda994dcefab1cd4c. Sau checkpoint Git chỉ chuẩn bị G6 DRAFT tài liệu/sơ đồ/demo, chưa nghiệm thu hoặc được push G6. Đọc [G5_RESULT](docs/G5_RESULT.md) và [bằng chứng giao diện](docs/evidence/G5_BROWSER_QA.json).

Source tại `D:\Hoctap\KhoaLuanCuNhan\LapTrinhMaNguonModoAN` (ổ mạng); database riêng `mnm_vat_tu_nong_nghiep`. Laravel13.33/PHP8.3.30/MySQL8.4.3, Eloquent, Blade và Bootstrap5.3.8 offline/MIT. [Repository riêng](https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo).

Admin quản lý vật tư, danh mục, đơn vị và tài khoản; User xem/tìm/phân trang. Dashboard chỉ đọc dữ liệu thật. G5 bổ sung menu responsive, biểu mẫu/báo lỗi tiếng Việt, bộ lọc, trạng thái, chi tiết và xác nhận xóa; giữ Auth/role/CSRF/rule/schema/32route cũ. Không Shop/POS/đơn hàng/kho/công nợ.

Kết quả: **31test/674assertions**,29request TCP,33route (32cũ+dashboard),54PHP lint và JS syntax;22ảnh browser thực ở1366/768/320. Đây là ma trận mẫu đã nêu, chưa kiểm mọi trang trên mọi kích thước/thiết bị vật lý hoặc đa trình duyệt. Demo3users/5categories/3units/4products giữ nội dung/schema/ledger; AUTO_INCREMENT có thể tiến khi rollback. Không migrate/seed/reset demo.

Bàn giao `MNM_NHOM6_G5_VERIFIED_20261017.zip`:26file source/test/assets thay đổi trên nền commit G4B, hồ sơ/evidence/ảnh và manifest; không phải dependency hoặc toàn source Laravel. Đọc BAT_DAU_DOC.md bên trong; SHA ZIP giao ngoài archive. ZIP G5 giữ nguyên byte như hồ sơ trước publication; source checkpoint G5 được publish riêng theo quyền mới. Không .env/credentials/private/log thô/vendor/node_modules/dump. Preview localhost chỉ dùng khi tiến trình hiện tại còn chạy; không mặc định dịch vụ production.

Điểm tiếp tục: [context](context.md), [PLAN](.agent/PLAN.md), [checklist](.agent/G5_PUBLICATION_G6_CHECKLIST.md). G5 đã nghiệm thu; G6 DRAFT được chuẩn bị sau publication, rubric gốc vẫn chưa nhận. G5_RESULT/ảnh/log là evidence theo checkpoint, giữ nguyên. [Phân công đề xuất](docs/FUNCTIONS_USE_CASES.md) cho Nguyễn Đức Thành Phát, Lý Vũ Nhân Hậu, Nguyễn Văn Minh, Nguyễn Hồ Minh Trọng.
