# G6 publication → G7 DRAFT

ALLOWED: Kiểm đọc accepted ZIP/source/Git; explicit whitelist commit/push G6 trên main repository riêng; cập nhật governance/decision/preflight. Sau readback sạch mới tạo docs/g7, G7_RESULT/evidence/ZIP; chỉ đọc fixture xác minh.

FORBIDDEN: Sửa source G5/migration/domain/Auth/CRUD/config/dependencies, tám nội dung G6/sơ đồ/ảnh/lệnh đã khóa, database mutations/migrate/seed/cleanup, MySQL installation/ENV04, KLCN/NoSQL/Word/Rose/repo cha; force push; G7 commit/push hoặc G8.

SOURCE OF TRUTH: Quyết định [G6 ACCEPTED](decisions/G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018.md), G6-R ZIP a432d9ad…b01f/44payload, G5 code68770c0d…d69 và accepted metadata/evidence, ảnh danh sách nhóm Thy gửi18/10. Rubric gốc/biểu mẫu trường chưa được cung cấp.

INVARIANTS:26source hash; app source0diff; G6 accepted content giữ byte;4business tables26columns3FK7CHECK2trigger/1Admin;fixture3/5/3/4; protected Git so đầu lượt18/10. Repo cha có2Word lockfile mới trước task, HEAD/index/config giữ; không nhận là task này tạo/đụng.

ACCEPTANCE: Manifest/CRC/source/link/secret/staged diff/remote SHA/tree/list/clean PASS trước G7. G7 source-grounded, ảnh đã kiểm/historical đúng checkpoint, phân công đề xuất/giới hạn rõ, link/control/fence và ZIP CRC/member hashes/readback PASS; dừng DRAFT.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| P01 | Accepted ZIP/source/protected trước publication | G6-R ZIP/G5 Git | VERIFIED | G6_PUBLICATION_PREFLIGHT;44payload/26source | Không sửa app |
| P02 | Whitelist/secret/staged blobs | Git index riêng | IN_PROGRESS | .agent/logs/g7/g6-stage-review.json khi PASS | Không add -A |
| P03 | Commit/push/remote readback/clean | main/origin | TODO | g6-publication-readback.json | Sau P02 |
| D01 | Báo cáo Markdown + nguồn/ảnh/nhóm | Code/G6/ảnh Thy | TODO | docs/g7 sau P03 | Không G7 push |
| D02 | Slide/demo/rubric/thiếu | Code/test/browser evidence | TODO | docs/g7 sau P03 | 10–12phút |
| V01 | Audit links/semantic/bảo toàn | Artifact cuối | TODO | G7_VERIFICATION | Không giả runtime mới |
| H01 | ZIP/SHA/CRC và dừng DRAFT | Whitelist manifest | TODO | .agent/logs/g7/package-final.json | G7 chưa nghiệm thu |
