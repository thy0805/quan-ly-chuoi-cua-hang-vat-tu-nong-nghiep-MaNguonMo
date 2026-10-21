# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

## C1 hiện hành — 21/10/2026

Laravel + MySQL + Eloquent/Migration/Seeder + Blade/Bootstrap; hai role Admin/User. Giữ Auth, CRUD danh mục/đơn vị/vật tư/tài khoản. **Đã thêm Cụm1 tồn kho**: xem tồn, nhập/xuất thủ công, lịch sử bất biến, tìm/lọc/phân trang, validation và ngăn xuất quá tồn/gửi trùng/ghi đồng thời sai.

[Bắt đầu C1](docs/c1/README.md) · [Kết quả và giới hạn](docs/C1_RESULT.md) · [Thiết kế](docs/c1/THIET_KE.md) · [Demo Windows](docs/c1/CHAY_VA_DEMO.md).

Kiểm mới:44/44tests,828assertions;4racecases/8workers;TCP demo20-5=15/2movement. Đã bổ sung15ảnh browser ở1366/768/320 ngày21/10; [checklist visual](.agent/C1_VISUAL_CHECKLIST_20261021.md) và ZIP ảnh riêng MNM_NHOM6_C1_ANH_GIAO_DIEN_20261021.zip. Chờ Thy nghiệm thu C1; chưa kiểm mọi thao tác cảm ứng. Demo chỉ trên database test riêng, database nghiệp vụ chưa migrate hai bảng kho và được giữ nguyên.

Baseline main ad30d92dbc0550839c788e3d17a293dcc9218c45; **C1 còn local, chưa commit/push và chờ nghiệm thu**. Cụm2 bán hàng/Cụm3 nhà cung cấp chỉ kế hoạch. G1–G6 evidence và G7 draft giữ nguyên; các phần dưới là snapshot trước mở rộng, không mô tả chức năng hiện tại đầy đủ. Không Word/slide trong C1.

---

## Lịch sử từ checkpoint G6 đã công bố

Cập nhật hiện hành18/10/2026: G6/G6-R đã ACCEPTED/LOCKED, được chốt Git theo decision G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018. Publication đang kiểm, SHA sau push đọc tại context/receipt mới. Sau remote readback sạch chỉ chuẩn bị G7 DRAFT, chưa commit/push G7. Các trạng thái G6 DRAFT/chưa push bên dưới giữ checkpoint cũ; source/nghiệp vụ và accepted G6 content không sửa.

# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

**G5 ACCEPTED/LOCKED và đã push main 68770c0d2dc8b0e743a69564112be41eed54dd69.** [Receipt SHA/tree/257files](docs/evidence/G6_G5_PUBLICATION.json). G6 là hồ sơ **DRAFT chưa commit/push**, đọc [G6_RESULT](docs/G6_RESULT.md) → [hồ sơ/ERD/UML/rubric/demo](docs/g6/README.md). Source G5 không sửa trong G6.

Laravel13.33/PHP8.3.30/MySQL8.4.3, Blade/Bootstrap5.3.8 offline/MIT; database riêng mnm_vat_tu_nong_nghiep. Admin quản lý danh mục/đơn vị/vật tư/tài khoản; User tra cứu. G5 hồi quy mới trước push31/674; browser G5/22ảnh1366/768/320 đã nghiệm thu. G6 đọc schema thật4tables26columns3FK7CHECK2trigger và dựng SVG có browserQA; xem [ERD](docs/g6/diagrams/erd.svg), [UML](docs/g6/diagrams/use-cases.svg).

G6 gồm8Markdown, trang xem sơ đồ, source Mermaid/PlantUML/JSON và SVG/ảnh browser, minh họa code thật, rubric và kịch bản demo10–12phút. Chưa rubric gốc/nhóm xác nhận phân công/báo cáo Word-PDF-slide/buổi demo thật; không upload/AJAX/POS/Shop/checkout/kho/công nợ. Scaffold Vite/Tailwind cũ còn lưu nhưng không route hiện hành dùng.

Khởi động trên máy đã provision theo [DEMO_KHOI_DONG](docs/g6/DEMO_KHOI_DONG.md); không tự migrate/seed/reset demo hoặc sửa guard/config. Credentials chỉ private ignored. Accepted G5 ZIP giữ nguyên byte; bàn giao G6 một ZIP nhỏ/SHA với manifest, không dependency/.env/private/rawlogs/dump. ENV04 có readback riêng44/44hash hiện khớp manifest; không replay repair hoặc suy từ MySQL service.

Điểm tiếp: [context](context.md), [PLAN](.agent/PLAN.md), [checklist](.agent/G5_PUBLICATION_G6_CHECKLIST.md). **Dừng G6 DRAFT chờ Thy/GPT Web nghiệm thu; không G6 commit/push hoặc mở stage mới.**
