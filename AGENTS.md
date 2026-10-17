QUYỀN/TRẠNG THÁI MỚI: Thy đã ACCEPTED G4B source/test/HTTP/MySQL và duyệt checkpoint G4B rồi G5 theo .agent/decisions/G4B_ACCEPTED_G5_AUTHORIZED_20261017.md và G5_CHECKLIST. Các câu G4B chờ nghiệm thu/chưa có quyền push/G5 chưa mở ở phần dưới là checkpoint cũ. Source G5 chưa sửa; đang publication G4B. G5 chưa commit/push, G6 chưa mở.

# Quy tắc riêng Mã nguồn mở — Nhóm 6

- Giao tiếp tiếng Việt có dấu, gọi Thy/xưng tui; không thêm comment code khi chưa được yêu cầu.
- Chỉ sửa LapTrinhMaNguonModoAN. KLCN/NoSQL/Mobile/Word/Rose và repo cha chỉ đọc; không thay index/config hoặc database của chúng.
- Đầu phiên đọc context.md, .agent/PLAN.md, G4B_CHECKLIST.md, DOC_INDEX.md, mistake.md và decision hiện hành. Viết hồ sơ phải đọc .agent/rule.md.
- Quyền hiện hành: Thy nghiệm thu G4A, duyệt publication G4A rồi G4B theo decision G4A_ACCEPTED_G4B_AUTHORIZED_20261017. G4A đã push main 61332e5e7236a7b28dd71197b3f3848b14aaff74; G4B local, chờ nghiệm thu, chưa được commit/push. Các giới hạn cũ G4B chưa mở/HTTP503/DB-deny chỉ mô tả checkpoint G3/G4A.
- G1/G2/G3/ENV04/G4A LOCKED theo phạm vi; giữ accepted ZIP/evidence và schema/models/migrations. Không replay clone/scaffold/ENV repair hoặc migration/seed/reset demo.
- G4B dùng đúng bốn bảng MySQL riêng, Eloquent, Laravel session/CSRF/active và Admin/User. User xem/tìm/phân trang; mọi ghi do Admin trên server. Không public registration/remember/reset-password hoặc Sanctum/token.
- Giữ singleton Admin, cây/active-parent/active references, decimal-string/DecimalId, DELETE vật lý RESTRICT/transaction; không cascade. Không tự đổi nghiệp vụ/constraint để test qua.
- G4B kiểm MySQL fixture transaction/rollback và baseline readback; không migrate/seed/DROP/TRUNCATE. Runtime chỉ account schema CRUD; không root trong ứng dụng, không in config/credential.
- Thy đã xác nhận phương thức local auth ở ghi chú private; không mặc định yêu cầu nhập GUI lại. ENV04 giữ khóa, không điều tra lại DLL/ICU hoặc restart khi không có yêu cầu.
- G5 Bootstrap/UI hoàn chỉnh, G6 báo cáo/demo, Shop/POS/checkout/kho/lô/công nợ/Mongo/worker chưa mở. Blade tối thiểu G4B chỉ phục vụ luồng HTTP.
- Repo Public duy nhất: https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo.git. Push mới cần nghiệm thu/quyền tương ứng; explicit stage whitelist, secret/protected/readback, không force.
- Không public .env/key/token/credentials/private/archive/rawlogs/dependencies/cache/build/dump/nested Git; chỉ source và evidence đã lọc. Một ZIP nhỏ/SHA mỗi checkpoint, chỉ vị trí hồ sơ rõ cho GPT Web.
- VERIFIED cần source/runtime/readback phù hợp, khác LOCKED do Thy nghiệm thu. Dừng sau G4B, không tự mở giai đoạn tiếp theo.
