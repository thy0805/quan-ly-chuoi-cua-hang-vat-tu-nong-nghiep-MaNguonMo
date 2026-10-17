Cập nhật hiện hành18/10/2026: G6/G6-R đã ACCEPTED/LOCKED, được chốt Git theo decision G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018. Publication đang kiểm, SHA sau push đọc tại context/receipt mới. Sau remote readback sạch chỉ chuẩn bị G7 DRAFT, chưa commit/push G7. Các trạng thái G6 DRAFT/chưa push bên dưới giữ checkpoint cũ; source/nghiệp vụ và accepted G6 content không sửa.

# Quản lý cửa hàng vật tư nông nghiệp — Nhóm 6

**G5 ACCEPTED/LOCKED và đã push main 68770c0d2dc8b0e743a69564112be41eed54dd69.** [Receipt SHA/tree/257files](docs/evidence/G6_G5_PUBLICATION.json). G6 là hồ sơ **DRAFT chưa commit/push**, đọc [G6_RESULT](docs/G6_RESULT.md) → [hồ sơ/ERD/UML/rubric/demo](docs/g6/README.md). Source G5 không sửa trong G6.

Laravel13.33/PHP8.3.30/MySQL8.4.3, Blade/Bootstrap5.3.8 offline/MIT; database riêng mnm_vat_tu_nong_nghiep. Admin quản lý danh mục/đơn vị/vật tư/tài khoản; User tra cứu. G5 hồi quy mới trước push31/674; browser G5/22ảnh1366/768/320 đã nghiệm thu. G6 đọc schema thật4tables26columns3FK7CHECK2trigger và dựng SVG có browserQA; xem [ERD](docs/g6/diagrams/erd.svg), [UML](docs/g6/diagrams/use-cases.svg).

G6 gồm8Markdown, trang xem sơ đồ, source Mermaid/PlantUML/JSON và SVG/ảnh browser, minh họa code thật, rubric và kịch bản demo10–12phút. Chưa rubric gốc/nhóm xác nhận phân công/báo cáo Word-PDF-slide/buổi demo thật; không upload/AJAX/POS/Shop/checkout/kho/công nợ. Scaffold Vite/Tailwind cũ còn lưu nhưng không route hiện hành dùng.

Khởi động trên máy đã provision theo [DEMO_KHOI_DONG](docs/g6/DEMO_KHOI_DONG.md); không tự migrate/seed/reset demo hoặc sửa guard/config. Credentials chỉ private ignored. Accepted G5 ZIP giữ nguyên byte; bàn giao G6 một ZIP nhỏ/SHA với manifest, không dependency/.env/private/rawlogs/dump. ENV04 có readback riêng44/44hash hiện khớp manifest; không replay repair hoặc suy từ MySQL service.

Điểm tiếp: [context](context.md), [PLAN](.agent/PLAN.md), [checklist](.agent/G5_PUBLICATION_G6_CHECKLIST.md). **Dừng G6 DRAFT chờ Thy/GPT Web nghiệm thu; không G6 commit/push hoặc mở stage mới.**
