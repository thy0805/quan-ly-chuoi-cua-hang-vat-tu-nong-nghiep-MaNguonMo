# Kế hoạch hiện hành — G4A checkpoint rồi G4B

Nguồn chuẩn: G4B_CHECKLIST.md và decisions/G4A_ACCEPTED_G4B_AUTHORIZED_20261017.md. G4A đã ACCEPTED/LOCKED theo Thy, không còn chờ nghiệm thu. G4A_RESULT/ZIP giữ byte theo checkpoint được duyệt.

| Pha | Trạng thái | Evidence/bước tiếp |
|---|---|---|
| G4A acceptance | LOCKED phạm vi | ZIP48hash/11tests131assertions;Thy17/10 |
| G4A publication A01–A03 | IN_PROGRESS | Base/origin21c8842;scan/stage/commit/push/readback |
| G4B HTTP B01–B04 | TODO | Chỉ bắt đầu sau push/tree sạch |
| G4B commit/push | TODO,chưa được duyệt | Chờ nghiệm thu G4B |
| G5/G6 | TODO,chưa mở | Không mở rộng |

Không migrate/seed/DROP/TRUNCATE/reset demo. Test MySQL transaction/fixture rollback và readback nội dung trước/sau. Phân biệt đã viết/đã kiểm/được nghiệm thu.
