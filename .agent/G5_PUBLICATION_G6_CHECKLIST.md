# Checkpoint G5 và G6 DRAFT

ALLOWED: Kiểm đọc26source đã nghiệm thu, test MySQL rollback và metadata schema riêng; explicit whitelist commit/push G5 main đúng repository; cập nhật governance/publication receipt. Sau remote readback/clean, chỉ viết docs/g6 và diagrams/g6, tài liệu minh họa/rubric/demo cùng evidence/ZIP.

FORBIDDEN: Sửa source G4A/G4B/G5, rule/schema/config/dependencies, MySQL installation; migrate/seed/reset/xóa demo; ghi KLCN/NoSQL/Word/Rose/repo cha; force push; G6 commit/push hoặc chức năng mới.

SOURCE OF TRUTH: decision G5_ACCEPTED_G6_DRAFT_AUTHORIZED; accepted G5 ZIP/SOURCE_DIFF; actual source và MySQL metadata; G2/ERD thiết kế chỉ đối chiếu, không dùng câu TODO cũ làm runtime.

INVARIANTS:26source hash;31test674assertions baseline;33route;4business tables/3FK RESTRICT/7CHECK/2trigger/1Admin;3users/5categories/3units/4products và rowhash/schema/ledger giữ trừ AUTO_INCREMENT; protected Git/hash theo baseline.

ACCEPTANCE: G5 archive/source/regression/secret/protected/staged readback PASS → push noforce/remote SHA/tree/clean; G6 schema-derived ERD/UML source+render visually checked, docs/code/test/UI rubric links readback; ZIP CRC/exact-set/SHA; dừng DRAFT nghiệm thu.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| P01 | ZIP26source/HEAD/protected; secret trước stage | Accepted ZIP/Git | VERIFIED | G5_PUBLICATION_PREFLIGHT; source/ZIP/protected | Không sửa nguồn |
| P02 | Hồi quy/fixture/lint trước commit | MySQL/test | VERIFIED |31/674;fixture giữ;54lint/JS | Rollback, không seed |
| P03 | Whitelist stage/commit/push/readback | main/origin riêng | IN_PROGRESS | Preflight đạt; đang rà stage | Không force |
| D01 | Đọc metadata schema thực | MySQL riêng | TODO | Chưa thực hiện | SELECT only |
| D02 | ERD/UML nguồn và render | Metadata/code/route | TODO | Chưa có | Không dùng nguyên KLCN |
| D03 | Tài liệu/rubric/phân công/demo/startup | Source/test/UI | TODO | Chưa có | Không thêm chức năng |
| V01 | Link/semantic/visual/protected | Artifact cuối | TODO | Chưa có | G6 vẫn DRAFT |
| H01 | ZIP/manifest/SHA/bàn giao | Whitelist | TODO | Chưa có | Không G6 push/stage mới |
