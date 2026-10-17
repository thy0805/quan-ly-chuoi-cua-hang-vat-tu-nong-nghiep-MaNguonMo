# G6 DRAFT — tài liệu, ERD, UML và demo

Ngày17/10/2026. **Bản nháp G6 chờ Thy/GPT Web nghiệm thu, chưa commit/push.** Source G5 được nghiệm thu/LOCKED và đã push main **68770c0d2dc8b0e743a69564112be41eed54dd69**; remote SHA/tree/fetch khớp, worktree sạch trước G6. [Publication receipt](evidence/G6_G5_PUBLICATION.json), [staged review78file](evidence/G6_G5_STAGE_REVIEW.json), [preflight](evidence/G5_PUBLICATION_PREFLIGHT.json).

**Hiệu chỉnh G6-R theo review Thy/GPT Web:** nội dung và sơ đồ đã được rà; G6 chưa khóa vì block PowerShell demo lỗi escape. Xem [G6R_RESULT](G6R_RESULT.md) và [exact-command startup mới](evidence/G6R_STARTUP_CHECK.json). Chỉ block demo/evidence/governance liên quan được sửa; các nội dung và sơ đồ còn lại giữ byte. G6_STARTUP_CHECK hiện hành đã thay bằng kiểm chạy lệnh trích trực tiếp Markdown, chỉ đổi cổng; kết quả helper G6 cũ dùng path riêng không đủ chứng minh block bị lỗi. ZIP G6 gốc giữ nguyên lịch sử; dùng **MNM_NHOM6_G6R_DRAFT_20261017.zip** cho nghiệm thu tiếp, không commit/push.

## Nội dung bàn giao

- [8tài liệu G6](g6/README.md): đề tài/chức năng/phân công, ERD/từ điển26cột, Use Case/luồng, minh họa Laravel từ source thật, rubric và kế hoạch khởi động/demo, giới hạn.
- [ERD SVG](g6/diagrams/erd.svg), [UML SVG](g6/diagrams/use-cases.svg), source Mermaid/PlantUML/JSON và2ảnh browser. [Trang xem](g6/index.html) có sẵn để mở trực tiếp; SVG được render trong browser, không giả engine PlantUML/Mermaid đã compile.
- [Metadata](evidence/G6_MYSQL_SCHEMA.json)/[readback](evidence/G6_MYSQL_READBACK.json):4bảng nghiệp vụ/26cột/3FK RESTRICT/7CHECK/2trigger/1Admin. Migration ledger không tính là bảng nghiệp vụ; users không có FK giả.
- [Startup probe](evidence/G6_STARTUP_CHECK.json): đúng cwd public/frameworkrouter/private .env hiện có; login+4assets200; server tạm đã dừng, không migrate/seed hoặc inject DB password. Runtime storage file riêng có thể được framework ghi bình thường.
- [ENV04 evidence riêng](evidence/G6_ENV04_EVIDENCE_READBACK.json): hồ sơ gốc có44file; kiểm đọc hiện tại44/44hash/3.837.376byte khớp, không replay phục hồi/đổi installation. Tách kết luận này khỏi việc MySQL phục vụ G5; không tái dựng hành vi quá khứ từ service.
- [Verification cuối](evidence/G6_VERIFICATION.json): structural/semantic/render/readback, link/snippet/rubric, protected/secret/source-diff và phạm vi giới hạn. ZIP CRC/exact-set/member SHA và SHA toàn ZIP ở receipt local .agent/logs/g6/package-final.json khi đã PASS.

Gói bàn giao: **MNM_NHOM6_G6_DRAFT_20261017.zip** tại root dự án, đọc BAT_DAU_DOC.md trước. Gói gồm hồ sơ G6, sơ đồ/ảnh, receipt publication và kiểm chứng, governance hiện hành cùng ba bằng chứng ENV04 lịch sử. Source Laravel lấy tại commit GitHub nêu trên; không nén vendor/private hoặc toàn bộ source lần nữa. Bàn giao chỉ khi receipt package-final PASS.

## Kiểm và giới hạn

Hồi quy trước publication G5 mới31/674, lint54PHP/JS;26source khớp accepted ZIP;78staged blob/secret readback, remote tree khớp, tổng257tracked. G6 không chạy lại full browser G5 hoặc thêm app test: source không sửa, chỉ SELECT metadata và tài liệu/render/startup GET. Fixture demo3users/5categories/3units/4products giữ nội dung/schema/ledger theo readback; AUTO_INCREMENT có thể tiến khi rollback. Protected KLCN/NoSQL/parent HEAD/status/Gitmetadata phải khớp baseline; không là audit toàn bộ ignored/source/database.

Rubric dựa mô tả Thy, chưa phiếu chấm gốc; phân công là đề xuất chưa xác nhận đóng góp; chưa buổi demo thật/báo cáo Word-PDF-slide nộp cuối. LiveCRUD demo cần scope dữ liệu QA riêng, không sửa/xóa demo. Không upload/AJAX/POS/Shop/checkout/kho/công nợ. Source G5 đã ở GitHub; G6 chỉ local/ZIP cho GPT Web. Dừng G6 DRAFT, không tự commit/push hoặc mở stage.

Audit tài liệu cuối: inventory57Markdown sở hữu,0link tương đối hỏng;8trích đoạn khớp đúng source và dòng,12tham chiếu test tồn tại. ERD26cột/3FK và UML2actor/9UC/14association khớp schema/routes; chỉ admin_slot là cột GENERATED STORED. Có hai lỗi console promise/animation không rõ nguồn trong công cụ browser, được giữ trong G6_RENDER_QA và giới hạn nghiệm thu; SVG không chứa script, ảnh hiển thị và khung chữ đã kiểm riêng. Không tuyên bố console sạch.
