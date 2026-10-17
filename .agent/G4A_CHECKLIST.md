# G4A — preflight và phạm vi triển khai

ENV04 đã ACCEPTED / LOCKED. Checklist tiếp tục hiện hành là G4A_RESUME_CHECKLIST.md theo ENV04_ACCEPTED_G4A_RESUMED_20261017. Readback mới đã kiểm process/port/hash, G4A BLOCKED AUTH01 vì chưa có SQLsession hợp lệ. A03–A06 vẫn TODO; không tự tạo DB/account hoặc commit/push.

ALLOWED: Source chỉ LapTrinhMaNguonModoAN; Thy duyệt tự mở Laragon rồi direct-start đúng mysqld8.4.3/config/datadir hiện hữu. Một instance; không initialize/reset/reinstall/config edit. Sau môi trường/quyền hợp lệ mới G4A dependency/boot/DB riêng/migration/Eloquent/seeder/QA. Xem decision G4A_DIRECT_MYSQL_AUTHORIZED.
FORBIDDEN: Sửa KLCN/NoSQL/Flutter/Word/Rose; kết nối DB gốc; đổi quyền ngoài dự án; DROP/TRUNCATE dữ liệu ngoài manifest; G4B/G5; commit/push chưa nghiệm thu.
SOURCE OF TRUTH: Thy nghiệm thu và duyệt G4A ngày 17/10; checkpoint21c8842027c510ec4db0d09b859ccd522f8785de; G2_DECISIONS/ERD_MYSQL; môi trường đọc trực tiếp.
INVARIANTS: Một Admin; signed BIGINT/Decimal/cây/active/3FK/RESTRICT theo G2; dữ liệu synthetic; cấm PG/Mongo/DB ngoài dự án; giữ nguồn G1 và checkpoint G2/G3.
ACCEPTANCE: Server MySQL8.4 và quyền provision xác minh; Laravel boot thật; migrations/seed/model/FK/CHECK/unique/cycle/rollback/Unicode/Decimal được kiểm trên MySQL thật; nguồn bảo vệ không đổi; ZIP readback/SHA. Nếu preflight không đáp ứng, dừng và báo blocker, không coi G4A hoàn tất.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| A01 | Nghiệm thu, root và checkpoint | Thy/Git | VERIFIED | decision G4A; local/remote21c8842; ban đầu working tree sạch | Không tạo commit mới |
| A02 | MySQL startup/readback | ENV04 nghiệm thu/readback mới | VERIFIED process/port/version/component | G4A_RESUME_VERIFICATION:10032→4968,8.4.3;ENV04 LOCKED | SQL identity/quyền/DB chưa biết; AUTH01 |
| A03 | Provision DB/tài khoản ứng dụng riêng | Server/quyền hợp lệ | TODO | Chưa SQL | Phụ thuộc A02 môi trường đạt và phiên quản trị hợp lệ |
| A04 | Dependency/boot/guard G4A | composer.lock/G3 | TODO | Chưa cài vendor/.env | Không gỡ guard để thử |
| A05 | Migration/model/seeder | ERD/G2/sourceG1 | TODO | Chưa có file mới | 4 bảng/3FK/1Admin giữ thiết kế |
| A06 | QA MySQL thật/parity | A03–A05 | TODO | Chưa migrate/seed/test | Không dùng SQLite |
| A07 | Báo cáo/ZIP/readback | Evidence thực tế | VERIFIED trong phạm vi hồ sơ startup/blocker | G4A_STARTUP_VERIFICATION.json; packaging.json local cho ZIP cuối | G4A runtime vẫn TODO; không thay QA MySQL |

Trạng thái milestone: ENV04 LOCKED; G4A đã tiếp tục preflight và dừng AUTH01 theo Thy. Chưa có quyềnSQL/DB và A03–A06; không lấy ENV04 đạt thay G4A runtime/QA. Xem G4A_RESUME_CHECKLIST và G4A_RESULT hiện hành; giữ bằng chứng startup cũ theo thời điểm.
