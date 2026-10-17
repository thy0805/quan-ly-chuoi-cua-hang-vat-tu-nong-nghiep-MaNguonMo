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
| P03 | Whitelist stage/commit/push/readback | main/origin riêng | VERIFIED | 68770c0d2dc8b0e743a69564112be41eed54dd69;G6_G5_PUBLICATION/STAGE_REVIEW | Không force |
| D01 | Đọc metadata schema thực | MySQL riêng | VERIFIED | G6_MYSQL_SCHEMA/READBACK;4tables26columns3FK7CHECK2trigger | SELECT only |
| D02 | ERD/UML nguồn và render | Metadata/code/route | VERIFIED kỹ thuật | G6_RENDER_QA;SVG/MMD/PUML/JSON;2ảnh browser | Không dùng nguyên KLCN |
| D03 | Tài liệu/rubric/phân công/demo/startup | Source/test/UI | VERIFIED kỹ thuật | G6_VERIFICATION;8trích đoạn khớp;12test reference;STARTUP_CHECK | G6 chưa nghiệm thu |
| V01 | Link/semantic/visual/protected | Artifact cuối | VERIFIED | G6_VERIFICATION;57Markdown;0link hỏng;26source/4archive giữ hash;protected khớp | G6 vẫn DRAFT |
| H01 | ZIP/manifest/SHA/bàn giao | Whitelist | VERIFIED chỉ khi receipt final PASS | Preview41member/40payload CRC/hash PASS; .agent/logs/g6/package-final.json quyết định ZIP cuối | Không G6 push/stage mới |

Gate bàn giao: receipt package-final phải PASS CRC/exact-set/từng SHA và readback byte workspace; nếu chưa PASS thì H01 vẫn APPLIED_UNVERIFIED và không báo hoàn tất ZIP. Quyết định nghiệm thu của Thy độc lập với kiểm kỹ thuật.
