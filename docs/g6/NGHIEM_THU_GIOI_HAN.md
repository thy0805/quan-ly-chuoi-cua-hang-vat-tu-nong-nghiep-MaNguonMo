# Nghiệm thu, giới hạn và bàn giao — G6 DRAFT

## Trạng thái các nguồn

G5 ACCEPTED/LOCKED trong source/test/HTTP/browser QA đã cung cấp; GPT Web kiểm ZIP/hash/ảnh/log, không tự chạy Laravel tại máy. Publication G5 main **68770c0d2dc8b0e743a69564112be41eed54dd69**, remote SHA/tree/fetched commit khớp;257tracked/78file checkpoint/26source. G6 chỉ docs/diagram/evidence/governance, chưa commit/push, chờ nghiệm thu.

Hồi quy mới trước push31/674; demo nội dung/schema/ledger giữ theo fixtureverify (AUTO_INCREMENT có thể tiến khi rollback). G6 chỉ SELECT schema/readback, thử boot/assets bằng temporaryPHP rồi dừng; không migrate/seed/reset/xóa demo hoặc ghi PostgreSQL/MongoDB. Browser ứng dụng G5 không chạy lại toàn bộ trong G6; ảnh cũ giữ checkpoint, visual G6 là kiểm sơ đồ riêng.

## ENV04 được kiểm riêng

Tìm thấy [báo cáo ENV04 gốc](../ENV04_REPAIR_RESULT.md), [44file readback](../evidence/ENV04_FILE_READBACK.json) và [verification lịch sử](../evidence/ENV04_VERIFICATION.json). Lượt G6 chỉ đọc44file hiện tại, tổng3.837.376byte;44/44hash khớp manifest lịch sử, không sửa/copy/cài/khởi động lại MySQL. Receipt đã lọc [G6_ENV04_EVIDENCE_READBACK](../evidence/G6_ENV04_EVIDENCE_READBACK.json) đưa từng relative path/hash để GPT Web đối chiếu.

Bằng chứng này tách việc MySQL đang phục vụ SQL với hồ sơ phục hồi. Hash hiện tại xác minh hiện hữu/khớp manifest; không độc lập tái dựng hành vi copy/shutdown quá khứ hoặc kiểm mọi thành phần installation/datadir. Không nâng kết luận từ log G5 hoặc replay repair ENV04. Báo cáo ENV04 cũ giữ trạng thái theo thời điểm, không sửa kết quả quá khứ.

## Giới hạn và điểm cần nghiệm thu

- Rubric gốc chưa có; bảng đối chiếu theo mô tả Thy, không tự kết luận điểm số hoặc đáp ứng mọi yêu cầu giảng viên.
- Phân công bốn thành viên là đề xuất; nhóm chưa xác nhận đóng góp/thời lượng thực tế.
- ERD/UML là G6 DRAFT từ schema/source thật; technical structural/semantic/render QA không thay nghiệm thu của Thy.
- Chưa có báo cáo Word/PDF/slide nộp chính thức hoặc buổi demo thầy; chưa chạy liveCRUD demo ghi dữ liệu ở G6.
- Responsive chỉ ma trận mẫu1366/768/320, chưa mọi thiết bị/trình duyệt hoặc stress nhiều client; concurrency nâng cao theo giới hạn G4A/B cũ.
- Không upload/AJAX ghi dữ liệu/POS/Shop/checkout/đặt hàng/kho/công nợ. Không tự thêm sơ đồ hoặc chức năng dự kiến vào phần đã hoàn thành.
- Source cần private runtime config trên máy hiện có; hướng dẫn startup không là bộ cài portable cho máy khác.
- Protected verification HEAD/status/Gitmetadata và hash nêu rõ, không hash mọi ignored file hoặc audit database khác; secret scan known values/patterns không bảo đảm mọi loại bí mật.

## Cách gửi GPT Web

Source G5 đọc trên GitHub bằng SHA trên. G6 chưa trên GitHub: gửi một ZIP G6 DRAFT có README/BAT_DAU_DOC, docs/g6, metadata/publication/verification/rubric/demo và manifest; không vendor/.env/private/log thô/dump hoặc ZIP G5 lặp lại. Đọc G6 README → ERD/UML → RUBRIC → DEMO → receipt. SHA ZIP giao ngoài archive. Dừng sau G6 DRAFT, không stage mới hoặc G6 commit/push.

Visual G6 đã xem SVG tĩnh/kiểm chữ không ngoài khung. Công cụ browser ghi2thông báo promise/animation TypeError không kèm nguồn; haiSVG không có script/animation. Chưa xác định nguồn nên không tuyên bố console sạch hoặc quy kết cho Laravel; giữ nguyên thông báo trong G6_RENDER_QA để truy vết.
