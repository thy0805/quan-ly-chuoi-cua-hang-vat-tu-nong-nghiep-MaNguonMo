# G6-R — sửa lệnh khởi động tài liệu

ALLOWED: Sửa tối thiểu block PowerShell trong docs/g6/DEMO_KHOI_DONG.md; evidence/verification G6, kết quả G6-R và governance liên quan; helper/log private và ZIP mới. Đọc fixture/metadata để xác minh; GET login/assets trên server tạm bằng đúng lệnh tài liệu, chỉ thay cổng rảnh.

FORBIDDEN: Mọi source G5, migration/model/config/guard/dependencies, database mutation/migrate/seed, hai sơ đồ và ảnh, KLCN/NoSQL/Word/repo cha, MySQL installation/restart, commit/push. Không ghi đè ZIP G6 gốc.

SOURCE OF TRUTH: Thy/GPT Web review G6 ngày17/10/2026; G5 commit68770c0d2dc8b0e743a69564112be41eed54dd69; ZIP G6 gốc SHA55d1f9ef7063f7835d8af7058be4da40ab10a681d97b20c325b07c982c649338; file/path thực tại máy; baseline fixture G4B.

INVARIANTS: Ngoài block khởi động, nội dung DEMO_KHOI_DONG giữ nguyên;7Markdown G6 còn lại và mọi diagram giữ byte; source G5/fixture/schema/ledger/protected Git giữ baseline; không stage/commit/push.

ACCEPTANCE: Không control C0/C1 ngoài TAB/CR/LF trong8Markdown; fence code cân bằng và link tương đối tồn tại; PowerShell Parser không lỗi; PHP/public/router tồn tại; chạy block từ Markdown chỉ đổi cổng, login+4assets200 và server tạm dừng; fixture/protected/source/diagram hash giữ; ZIP mới CRC/manifest/member SHA/readback PASS. G6 vẫn DRAFT.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| R01 | Baseline và sửa block | ZIP G6/file hiện tại | VERIFIED | .agent/logs/g6r/before.json; chỉ3control gốc và block startup thay đổi | Không sửa nội dung khác |
| R02 | Parser/path/exact runtime | Block Markdown | VERIFIED | G6R_STARTUP_CHECK;Parser0lỗi/GET5x200/asset byte khớp/server dừng | Chỉ thay cổng8086→64134 |
| R03 | Quét Markdown/link/fence và bảo toàn | Source/ZIP/fixture | VERIFIED chỉ khi G6R_VERIFICATION là VERIFIED |8Markdown control0/fence cân bằng/link tồn tại;G6R_VERIFICATION và G6_VERIFICATION quyết định gate cuối | Không nghiệp vụ ghi |
| R04 | ZIP mới và dừng DRAFT | Manifest whitelist | VERIFIED chỉ khi receipt final PASS | .agent/logs/g6r/package-final.json:CRC/exact-set/từng SHA/readback | Không commit/push |

Nếu receipt R03/R04 chưa đạt, trạng thái tương ứng vẫn APPLIED_UNVERIFIED và không báo hoàn tất. Kiểm kỹ thuật không thay nghiệm thu của Thy; giữ G6 DRAFT.
