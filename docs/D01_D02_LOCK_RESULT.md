# D01/D02 — Bản vá thông báo đã nghiệm thu

Ngày: 21/10/2026. Baseline trước bản vá: `ab2e564b6e433c0ceb7ee5ced86d1776e265b2f1`.

Thy đã nghiệm thu và cho phép công bố riêng bản vá. Commit gồm đúng hai file code/test và ba tệp hồ sơ liên quan; không gom G7/audit hoặc các thay đổi tài liệu có từ trước.

## Phạm vi được khóa

| Lỗi | Thay đổi | Giữ nguyên |
|---|---|---|
| D01 | Thông báo “Vật tư ở dòng :position bị trùng. Mỗi vật tư chỉ được chọn một lần trong phiếu.” cho cả phiếu bán và phiếu nhập | `distinct:strict`, error key, HTTP status và chỉ số dòng gốc |
| D02 | Nhãn `address` thành `địa chỉ`; lỗi quá dài hiện “Địa chỉ không được dài quá 500 ký tự.” | nullable/string/max:500 và dữ liệu nhập |

Mã ứng dụng chỉ thêm bốn dòng trong [validation.php](../lang/vi/validation.php). Không đổi FormRequest, controller, service, view, schema, transaction, tính tiền, tồn kho hoặc phân quyền.

## Bằng chứng đã kiểm

- Trước vá: 13 ca tái hiện thất bại đúng hai thông báo cũ, 65 assertions.
- Sau vá: 19 ca mới cùng bốn ca hồi quy, **23/23 đạt, 188 assertions** trên MySQL test cô lập. Không chạy lại toàn bộ suite hoặc test đồng thời trong bản vá này.
- [Test mới](../tests/Feature/ValidationLocalizationTest.php) kiểm create/edit, chỉ số liên tục/thưa 0/2 và 2/5, HTML redirect/old input, JSON 422, địa chỉ Unicode 500/501 ký tự, User bị chặn và CSRF.
- Chín ảnh thực của V02/V07/V10 ở 1366×768, 768×1024 và 320×700 đã được mở kiểm; thông báo tiếng Việt đọc được và không tràn ngang toàn trang trong các checkpoint này.
- Dữ liệu/cấu trúc năm bảng nghiệp vụ và 12 bảng test khớp trước/sau bản vá và lượt chụp. So sánh schema bỏ bộ đếm AUTO_INCREMENT do test rollback có thể tăng bộ đếm. Không tuyên bố datadir bất biến từng byte.

Test phụ thuộc profile test và các fixture demo hiện hữu; không có mật khẩu trong repository. Cần chuẩn bị đúng môi trường test riêng trước khi chạy; không chạy trên database nghiệp vụ.

Hồ sơ nghiệm thu riêng: `MNM_NHOM6_C2_C3_QA_FOLLOWUP_DRAFT_20261021.zip`, SHA-256 `ad1ffbb929624c10d4ae502f18d4cc8b5622ed155b6e7fd278c7fe60da0c2b0d`, CRC và 49/49 payload khớp manifest. ZIP ảnh/PDF và log riêng không được đưa vào commit public. Hash nguồn và kết quả tóm tắt nằm trong [verification](evidence/D01_D02_VERIFICATION_20261021.json).

## Phần còn tiếp tục riêng

Khóa D01/D02 không khóa Print. Khi nghiệm thu bản vá, hai PDF BH95/PN48 đang là Letter; P01/P02/P05 PARTIAL, P03/P04 BLOCKED. Thy giao chat Browser Use xuất lại PDF thật A4 dọc, bỏ headers/footers nếu có, mở/render kiểm và bổ sung chứng từ nhiều trang. Không dùng ảnh/HTML hoặc thay MediaBox để giả bằng chứng PDF in thực.

Không mở G7, module mới hoặc migration/seed database nghiệp vụ trong lượt công bố này. [Quyết định nghiệm thu](../.agent/decisions/D01_D02_LOCKED_20261021.md) là nguồn ưu tiên đối với các ghi chú DRAFT/chưa commit trước đó trong đúng phạm vi bản vá.
