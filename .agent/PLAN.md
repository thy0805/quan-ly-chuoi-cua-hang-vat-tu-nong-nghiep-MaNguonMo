QUYỀN/TRẠNG THÁI MỚI: Thy đã ACCEPTED G4B source/test/HTTP/MySQL và duyệt checkpoint G4B rồi G5 theo .agent/decisions/G4B_ACCEPTED_G5_AUTHORIZED_20261017.md và G5_CHECKLIST. Các câu G4B chờ nghiệm thu/chưa có quyền push/G5 chưa mở ở phần dưới là checkpoint cũ. Source G5 chưa sửa; đang publication G4B. G5 chưa commit/push, G6 chưa mở.

# Kế hoạch hiện hành — dừng sau G4B

Nguồn chuẩn: G4B_CHECKLIST.md, decision G4A_ACCEPTED_G4B_AUTHORIZED_20261017 và docs/G4B_RESULT.md. G4A accepted evidence/ZIP giữ nguyên byte.

| Pha | Trạng thái | Evidence / bước tiếp |
|---|---|---|
| G4A nghiệm thu | LOCKED theo phạm vi | Thy; ZIP48hash/11test131assertions |
| G4A publication A01–A03 | VERIFIED | main61332e5e7236a7b28dd71197b3f3848b14aaff74; remote SHA/tree/clean |
| G4B HTTP B01/B02 | VERIFIED kỹ thuật | Auth/session/active/admin/CRUD/Blade tối thiểu;15HTTP test trong suite |
| G4B QA B03 | VERIFIED |26test425assertions;24TCP;32route;fixture/schema/ledger/trigger/protected |
| G4B handoff B04 | VERIFIED kỹ thuật | G4B_RESULT/diff/scan/protected;ZIP preview52payload53entry CRC/hash;final receipt package-final.json;dừng nghiệm thu |
| G4B commit/push | TODO, chưa được duyệt | Chờ nghiệm thu và quyền tương ứng |
| G5/G6 | TODO, chưa mở | Không mở rộng |

Không migrate/seed/DROP/TRUNCATE/reset demo. Tests rollback và exact content readback; counter AUTO_INCREMENT không phải invariant. Chưa visual/responsive/load/stress nhiều client. Mốc accepted khác VERIFIED kỹ thuật.
