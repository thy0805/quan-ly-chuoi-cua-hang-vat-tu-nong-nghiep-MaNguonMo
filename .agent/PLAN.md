# C1 ACCEPTED/LOCKED — 21/10/2026

Thy đã nghiệm thu chức năng và15ảnh responsive; xem .agent/decisions/C1_LOCKED_C2_AUTHORIZED_20261021.md và docs/evidence/C1_VISUAL_ACCEPTED.json. Database nghiệp vụ chưa migrate; chưa kiểm đầy đủ cảm ứng điện thoại thật. Các trạng thái chưa nghiệm thu/visual pending/chưa commit trong snapshot bên dưới phản ánh thời điểm trước nghiệm thu. C2 được giao riêng, chưa có implementation trong checkpoint C1 này. G7/audit đang làm được giữ ngoài commit.

# Kế hoạch hiện hành — Cụm 1

## C1 hiện hành — 21/10/2026

Canonical: [checklist C1](C1_INVENTORY_CHECKLIST.md), [quyền và quyết định](decisions/C1_INVENTORY_20261021.md), [kết quả](../docs/C1_RESULT.md). Mở rộng được giao trọn C1 từ schema đến UI/tests; C2/3 chỉ kế hoạch. Không commit/push, không migrate nghiệp vụ, không Word/slide.

| Hạng mục | Trạng thái | Evidence |
|---|---|---|
| Baseline/test isolation/schema/service | VERIFIED theo phạm vi test | C1_VERIFICATION; schema/readback và business hashes |
| HTTP/role/validation/Blade render | VERIFIED chức năng | 44tests/828assertions và TCP demo |
| Đồng thời/atomicity/replay | VERIFIED | 4cases/8workers; rollback injection; ledger chain |
| Giao diện trực quan 1366/768/320 | VERIFIED phạm vi15ảnh; chưa LOCKED | C1_VISUAL_CHECKLIST_20261021.md; logs/c1/visual-20261021/verification.json; ZIP ảnh riêng |
| ZIP/source diff/bảo toàn | Kiểm theo receipt cuối | .agent/logs/c1/final-checks.json, package-final.json |
| Nghiệm thu/triển khai nghiệp vụ | TODO | Chờ Thy; không tự migrate/push |
| Cụm2 bán hàng, Cụm3 nhà cung cấp | TODO | Chỉ dùng chung InventoryService khi được giao |

Các kế hoạch G7 bên dưới là snapshot trước mở rộng; không lấy giới hạn không-code/không-kho cũ để phủ nhận quyền C1. Không dùng quyền C1 để sửa hồ sơ nghiệm thu cũ. Rà Markdown tại milestone theo final-checks, không viết lại lịch sử hoặc thư viện.

---

## Lịch sử từ checkpoint G6 đã công bố

# Kế hoạch hiện hành — khóa Git G6 rồi G7 DRAFT

Canonical: [checklist G6 publication/G7](G6_PUBLICATION_G7_CHECKLIST.md); [quyền18/10](decisions/G6_ACCEPTED_G7_DRAFT_AUTHORIZED_20261018.md). G6/G6R_RESULT/checklist/evidence là checkpoint accepted giữ byte; các câu DRAFT/chưa push trong đó thuộc thời điểm trước nghiệm thu.

| Pha | Trạng thái | Evidence/bước tiếp |
|---|---|---|
| G1–G5/ENV04 | LOCKED theo phạm vi nghiệm thu | Không replay setup/tests/repair hoặc sửa app |
| G6/G6-R nội dung/sơ đồ/lệnh demo | ACCEPTED/LOCKED | Thy/GPT Web kiểm ZIP44/44/CRC/SHA; decision18/10 |
| Publication G6 | IN_PROGRESS | Whitelist/staged/remote SHA/tree/clean bắt buộc |
| G7 report/slide outline/demo/rubric/missing | TODO, DRAFT được duyệt sau publication | Chỉ Markdown, source/ảnh/checkpoint thực |
| G7 verification/ZIP | TODO | Link/control/fence/semantic/protected/SHA/CRC |
| G7 nghiệm thu/commit/push hoặc G8 | TODO, chưa được duyệt | Dừng G7 DRAFT |

Không database write/migrate/seed/cleanup hoặc điểm cộng. Đọc fixture mới trước publication, giữ private config. Protected baseline đầu lượt18/10 khác lịch sử đúng2Word lockfile ở parent; HEAD/index/config/KLCN/NoSQL giữ. Rà Markdown sở hữu, current vs history, giữ accepted G6 artifacts; không sửa skill/third-party hoặc protected docs. G7 phân công đề xuất/rubric gốc/mẫu nộp/demo thầy pending rõ.
