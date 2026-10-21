# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

# Quy tắc hiện hành Mã nguồn mở

## C1 hiện hành — 21/10/2026

- Đọc context, PLAN, DOC_INDEX, mistake và checklist/decision C1. Thy đã giao triển khai trọn Cụm1 tồn kho; quyền này thay giới hạn chỉ docs/không sửa source/không kho trong nhiệm vụ G7 cũ.
- Chỉ repository này. Không sửa KLCN/NoSQL/Word/slide/evidence ZIP/sơ đồ đã khóa; không tự commit/push hoặc làm Cụm2/3.
- Chỉ ghi test database mnm_inventory_test_20261021 với profile và account test chính xác. Database nghiệp vụ chỉ đọc; không tự migrate/seed/reset. Giữ guard bình thường, không dùng root cho runtime, không in credentials.
- Một dịch vụ kho dùng transaction, product lock, decimal milli-unit, token/source unique, lịch sử bất biến/FK RESTRICT. Không sửa tồn qua CRUD vật tư hoặc đổi đơn vị sau giao dịch.
- Chưa visual QA C1: công cụ browser báo disabled không chứng minh setting của Thy bị tắt. Không lấy ảnh G5 làm ảnh kho mới. Chỉ ghi PASS khi có runtime/readback thực tế.
- Giữ các quy tắc giao tiếp, bảo mật và nguồn chuẩn bên dưới khi không mâu thuẫn với phạm vi C1 mới. Phần scope G7 bên dưới là lịch sử, không là quyền tự mở G7 hoặc cấm C1 đã giao.

---

## Lịch sử từ checkpoint G6 đã công bố

# Quy tắc riêng Mã nguồn mở — Nhóm 6

- Giao tiếp tiếng Việt có dấu, gọi Thy/xưng tui; không thêm comment code khi chưa được yêu cầu.
- Chỉ sửa LapTrinhMaNguonModoAN. KLCN/NoSQL/Mobile/Word/Rose và repo cha chỉ đọc; không thay index/config hoặc database của chúng.
- Đầu phiên đọc context.md, .agent/PLAN.md, G6_PUBLICATION_G7_CHECKLIST.md, DOC_INDEX.md, mistake.md và decision hiện hành. Viết hồ sơ đọc .agent/rule.md.
- Quyền hiện hành: G6/G6-R ACCEPTED/LOCKED theo decision G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018. Được explicit stage/commit/push riêng G6 sau kiểm; readback sạch rồi chỉ chuẩn bị G7 DRAFT Markdown/slide outline/rubric/demo. Không G7 commit/push hoặc G8; không sửa source hay accepted G6 content.
- Giữ accepted ZIP/evidence và schema/models/migrations/Auth/Requests/Middleware/domain/config/dependencies/32route cũ. Dashboard mới chỉ GET có auth+active, thống kê bằng Eloquent trên bốn bảng hiện có.
- Admin ghi, User chỉ xem/tìm/phân trang; middleware kiểm trước controller/render. Không public registration/remember/reset-password hoặc Sanctum/token. Giữ singleton Admin, cây/active references/DecimalId/decimal-string/DELETE RESTRICT/transaction.
- G5 dùng Blade/Bootstrap5.3.8 offline có MIT license; validation tiếng Việt giữ nguyên rule/field/error key/status. Không thêm Next/Flutter/Shop/POS/checkout/kho/lô/công nợ/outbox/worker.
- Không migrate/seed/reset demo/DROP/TRUNCATE. Test transaction/rollback; baseline private chỉ verify. Runtime account chỉ CRUD schema riêng, không root; không in credential/config tổng thể.
- Thy đã xác nhận local auth; không mặc định yêu cầu GUI/mật khẩu lại. ENV04 giữ evidence riêng; không dùng MySQL chạy thay bằng chứng44file, không repair/restart khi không cần. Browser hoạt động sau restart Codex; phải kiểm trực tiếp, không suy setting tắt từ lỗi công cụ.
- Repo Public duy nhất: https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo.git. Push mới cần nghiệm thu/quyền tương ứng; explicit stage whitelist/secret/protected/readback, không force.
- Không public .env/key/token/credentials/private/archive/rawlogs/dependencies/cache/dump/nested Git. Một ZIP/SHA mỗi checkpoint, có BAT_DAU_DOC và manifest; chỉ vị trí rõ cho GPT Web.
- VERIFIED kỹ thuật khác LOCKED do Thy nghiệm thu. Không replay helper checkpoint cũ hoặc dùng hồ sơ lịch sử làm trạng thái runtime hôm nay.
