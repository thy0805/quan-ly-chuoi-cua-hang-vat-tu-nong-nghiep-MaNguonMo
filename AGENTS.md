# Quy tắc riêng Mã nguồn mở — Nhóm 6

- Giao tiếp tiếng Việt có dấu, gọi Thy/xưng tui; không thêm comment code khi chưa được yêu cầu.
- Chỉ sửa LapTrinhMaNguonModoAN. KLCN/NoSQL/Mobile/Word/Rose và repo cha chỉ đọc; không thay index/config hoặc database của chúng.
- Đầu phiên đọc context.md, .agent/PLAN.md, G5_PUBLICATION_G6_CHECKLIST.md, DOC_INDEX.md, mistake.md và decision hiện hành. Viết hồ sơ đọc .agent/rule.md.
- Quyền hiện hành: G5 ACCEPTED/LOCKED trong source/test/HTTP/browser QA; được commit/push G5 main sau kiểm, rồi chuẩn bị G6 DRAFT tài liệu/ERD/UML/demo. G6 chưa được commit/push; dừng G6 DRAFT. Xem decision G5_ACCEPTED_G6_DRAFT_AUTHORIZED_20261017.
- Giữ accepted ZIP/evidence và schema/models/migrations/Auth/Requests/Middleware/domain/config/dependencies/32route cũ. Dashboard mới chỉ GET có auth+active, thống kê bằng Eloquent trên bốn bảng hiện có.
- Admin ghi, User chỉ xem/tìm/phân trang; middleware kiểm trước controller/render. Không public registration/remember/reset-password hoặc Sanctum/token. Giữ singleton Admin, cây/active references/DecimalId/decimal-string/DELETE RESTRICT/transaction.
- G5 dùng Blade/Bootstrap5.3.8 offline có MIT license; validation tiếng Việt giữ nguyên rule/field/error key/status. Không thêm Next/Flutter/Shop/POS/checkout/kho/lô/công nợ/outbox/worker.
- Không migrate/seed/reset demo/DROP/TRUNCATE. Test transaction/rollback; baseline private chỉ verify. Runtime account chỉ CRUD schema riêng, không root; không in credential/config tổng thể.
- Thy đã xác nhận local auth; không mặc định yêu cầu GUI/mật khẩu lại. ENV04 giữ evidence riêng; không dùng MySQL chạy thay bằng chứng44file, không repair/restart khi không cần. Browser hoạt động sau restart Codex; phải kiểm trực tiếp, không suy setting tắt từ lỗi công cụ.
- Repo Public duy nhất: https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo.git. Push mới cần nghiệm thu/quyền tương ứng; explicit stage whitelist/secret/protected/readback, không force.
- Không public .env/key/token/credentials/private/archive/rawlogs/dependencies/cache/dump/nested Git. Một ZIP/SHA mỗi checkpoint, có BAT_DAU_DOC và manifest; chỉ vị trí rõ cho GPT Web.
- VERIFIED kỹ thuật khác LOCKED do Thy nghiệm thu. Không replay helper checkpoint cũ hoặc dùng hồ sơ lịch sử làm trạng thái runtime hôm nay.
