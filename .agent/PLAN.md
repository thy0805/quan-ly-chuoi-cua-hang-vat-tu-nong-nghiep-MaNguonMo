# Kế hoạch G2/G3 và GitHub

Scope contract/checklist hiện hành: [G23_CHECKLIST](G23_CHECKLIST.md). Thy đã cho phép G2/G3 và commit/push repo Mã nguồn mở, thay điểm dừng G1.

| Giai đoạn | Trạng thái | Evidence / điều kiện tiếp |
|---|---|---|
| G1 clone/audit | VERIFIED, ACCEPTED hồ sơ | G1 commit87b605b, archive104 và manifest102 |
| G2 decision/schema | VERIFIED đặc tả | G2_DECISIONS + ERD_MYSQL,4 bảng/3FK; chưa DDL |
| G3 isolation | VERIFIED tĩnh/biên | Lint26,probe38,process12,Composer3; runtime chủ động khóa, chưa boot Laravel |
| Publication | IN_PROGRESS | P04–P06, whitelist/secret/protected/readback |
| G4 migrations/Eloquent/Auth/CRUD | TODO | Thy nghiệm thu và cho phép dependency/DB riêng trước thực thi |
| G5 Blade/Bootstrap/MySQL/browser QA | TODO | G4 parity, quyền/DELETE/race đạt |
| G6 báo cáo/demo | TODO | Rubric gốc, render sơ đồ và evidence chức năng thật |

G1 CLONE_CHECKLIST và Z01 đóng gói trước là lịch sử. Không chạy verify_clone/package_handoff/clone/scaffold cũ lên source G3. verify_g23.py là kiểm hiện hành. Không đọc trạng thái giai đoạn chỉ từ commit.

Bàn giao GitHub source ưu tiên và một ZIP hồ sơ nhỏ có SHA-256. Không public raw logs/archive/private/runtime. Sau publication dừng, không tự triển khai tiếp.
