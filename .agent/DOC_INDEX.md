# Bản đồ tài liệu — G4A hiện hành

Thy đã ACCEPTED/LOCKED G4A và duyệt checkpoint Git rồi G4B ngày17/10. Trạng thái mới ưu tiên: context → G4B_CHECKLIST → G4A_ACCEPTED_G4B_AUTHORIZED decision. Các câu chờ nghiệm thu G4A phía dưới là lịch sử; không sửa accepted evidence/ZIP.

| Tài liệu mới | Vai trò | Trạng thái | Đọc khi | Ưu tiên |
|---|---|---|---|---|
| .agent/G4B_CHECKLIST.md | Scope/A01–A03/B01–B04 | Hiện hành | Đầu phiên | Thy/G2/G4A |
| .agent/decisions/G4A_ACCEPTED_G4B_AUTHORIZED_20261017.md | Nghiệm thu/quyền publication và HTTP | Hiện hành ưu tiên | Trước push/HTTP | Thy mới nhất |

Trạng thái mới ưu tiên: dependency/boot/4migration/seed/owned rollback/remigrate đã đạt;11MySQL tests/131assertions cuối và fixture rowhash giữ nguyên. Chờ nghiệm thu G4A/ZIP cuối theo receipt. AUTH01/resume-preflight/ZIP PENDING là lịch sử;không ghi đè evidence mới. ENV04 giữ byte. Rootbroker đã đóng;phương thức authlocal Thy xác nhận nằm private,không public hoặc mặc định yêu cầu GUI.

| Tài liệu mới | Vai trò | Trạng thái | Đọc khi | Ưu tiên |
|---|---|---|---|---|
| docs/G4A_RESULT.md | Bàn giao triển khai G4A và giới hạn | Hiện hành ưu tiên | GPT Web nghiệm thu | G4A_MYSQL_QA và source diff |
| docs/evidence/G4A_MYSQL_QA.json | Schema/MySQL tests/grants/guard/HTTP | Hiện hành | Nghiệm thu nền dữ liệu | Rollback có cờ riêng,không suy từ model test |
| docs/evidence/G4A_SOURCE_DIFF.json | Danh sách code G4A/SHA so checkpoint | Hiện hành | Review source chưa push | Checkpoint21c8842 |
| .agent/archive/g4a-auth01-result-before-implementation-20261017.md | Báo cáo AUTH01 trước triển khai | Lịch sử local | Truy vết | G4A_RESULT mới ưu tiên |
| docs/evidence/G4A_PROTECTED_VERIFICATION.json | Protected Git/ENV04/source/lint37 | Hiện hành theo thời điểm | Nghiệm thu | Readback thực tế |
| .agent/scripts/g4a_run.py, g4a_fixture.php | Runner/redaction/schema/guard QA | Local ignored | Tiếp tục G4A | Không public credential/private fixture |
| .agent/scripts/g4a_trigger_broker.py | RootRAM chỉ hai trigger schema riêng | Local ignored | Rollback/remigrate | Chờ input hợp lệ; không bypass |

| Tài liệu | Vai trò | Trạng thái | Đọc khi | Phụ thuộc / ưu tiên |
|---|---|---|---|---|
| AGENTS.md | Quy tắc riêng | Hiện hành | Đầu phiên | Thy mới nhất ưu tiên |
| context.md | Recovery/trạng thái | Hiện hành | Đầu phiên | Artifact/evidence thắng mô tả |
| .agent/PLAN.md | Giai đoạn | Hiện hành | Chọn task | G4A_IMPLEMENTATION_CHECKLIST ưu tiên trạng thái mới |
| .agent/G4A_IMPLEMENTATION_CHECKLIST.md | I01–I06 triển khai/QA | Hiện hành ưu tiên | Đầu phiên | AUTH02 verified;G2/ERD/sourceG1 |
| .agent/decisions/G4A_TRIGGER_ADMIN_DDL_20261017.md | Trigger DDL root RAM,account schema-only | Hiện hành | Migration/rollback | Không globalconfig hoặc rootfallback Laravel |
| docs/evidence/G4A_SQL_PREFLIGHT.json | SQLidentity/grants/provision mới | Hiện hành | Đầu phiên/G4A | Không credential |
| .agent/logs/g4a-implementation/ | Composer/migration/test/readback/provision | Local ignored | QA/handoff | Không public rawlogs/credential |
| .agent/decisions/AUTH02_LOCAL_LOGIN_20261017.md | Đăng nhập cục bộ một lần, session RAM | Hiện hành ưu tiên AUTH01 workflow | Trước auth | Thy mới nhất; ENV04 giữ khóa |
| .agent/AUTH02_CHECKLIST.md | L01–L05 và input đang chờ | Hiện hành | Đầu phiên AUTH02 | Không coi helper là SQL thành công |
| .agent/scripts/mysql_local_login.py, mysql_local_session.php | UI nhập credential và PDO session RAM | Local ignored | Dùng đăng nhập cục bộ | Không password argv/env/file/log |
| .agent/logs/auth02/ | Launch receipt, login result, SQL readback private | Local ignored | Kiểm user đã submit chưa | Không public raw grants/identity hoặc credential |
| .agent/decisions/ENV04_ACCEPTED_G4A_RESUMED_20261017.md | ENV04 nghiệm thu và SQL gate G4A | Hiện hành ưu tiên | Đầu phiên | Thy mới nhất; không sửa locked ENV04 |
| .agent/G4A_RESUME_CHECKLIST.md | S01–S07 và AUTH01 | Hiện hành | Tiếp tục G4A | SQL gate trước dependencies/DDL |
| docs/evidence/G4A_RESUME_VERIFICATION.json | Fresh runtime/hash/protected/ENV04 lock | Hiện hành theo thời điểm | Nghiệm thu preflight | Không SQL/Laravel runtime |
| docs/evidence/G4A_RESUME_AUTH_PREFLIGHT.json | Auth metadata và blocker chính xác | Hiện hành | Trước SQL | Không đọc credential/đoán password |
| .agent/logs/g4a-resume/ | Raw readback/protected/verification/packaging | Local | Truy vết | Không public raw logs |
| .agent/archive/g4a-startup-result-before-resume-20261017.md | G4A_RESULT cũ nguyên byte | Lịch sử local | Truy vết | Cũng có trong ZIP ENV04 đã nghiệm thu |
| .agent/decisions/ENV04_OWNER_SHUTDOWN_AUTHORIZED_20261017.md | Thy bổ sung native Windows owner shutdown | Hiện hành ưu tiên ENV04 | Trước owner/event | Không suy quyền SQL từOS |
| docs/evidence/ENV04_RUNTIME_READBACK.json | PID/ports/newlog/moduleDLL | Hiện hành theo thời điểm | Nghiệm thu runtimeENV04 | Không SQLmetadata |
| docs/evidence/ENV04_INSTALLATION_INTEGRITY.json |127file cũ trước/sau và171total | Hiện hành | Bảo toàn |44added ở FILE_READBACK |
| docs/evidence/ENV04_LOG_SUMMARY.json | Newstartup/shutdown đã lọc | Hiện hành | Kiểm component/ICU | Rawlog local |
| .agent/ENV04_CHECKLIST.md | Scope/checklist R01–R06 | LOCKED sau nghiệm thu | Truy vết ENV04 | Câu chờ nghiệm thu giữ theo thời điểm; quyết định mới ưu tiên |
| .agent/decisions/ENV04_CONDITIONAL_REPAIR_AUTHORIZED_20261017.md | Quyền44file có điều kiện, bổ sung owner mới ưu tiên | Hiện hành | Trước repair/auth | Thy mới nhất |
| docs/ENV04_REPAIR_RESULT.md | Kết quả44file/owner shutdown/startup/recovery | LOCKED sau nghiệm thu | Truy vết ENV04 | Không viết lại report/evidence đã khóa |
| docs/evidence/ENV04_PREFLIGHT.json |44hash/archive/path/process/config/backup | Hiện hành theo thời điểm | Trước repair | Phải recheck khi tiếp tục |
| docs/evidence/ENV04_FILE_READBACK.json |44file trước absent/sau hashactual | Hiện hành theo thời điểm | Audit copy | Exact-set/hash saucopy |
| docs/evidence/ENV04_ACTION_RECEIPTS.json |1shutdown/44copy/1start,0account/SQL | Hiện hành | Kiểm gate | Không có receipt thành công giả |
| docs/evidence/ENV04_VERIFICATION.json | Bảo toàn/runtime/Git/hồ sơ | Hiện hành theo thời điểm | Bàn giao | ENV04 scope; SQL/DB G4A chưa kiểm |
| .agent/logs/env04/, .agent/private/env04/before-owner/ | Raw preflight/owner shutdown/restore/startup/backup/ZIP receipt | Local, không public | Truy vết | Không backupdatadir |
| .agent/ENV03_CHECKLIST.md | Scope/checklist E01–E05 | Hiện hành | Đầu phiên ENV03 | Thy/ENV03 decision |
| .agent/decisions/ENV03_DIAGNOSTIC_AUTHORIZED_20261017.md | Quyền chẩn đoán, chưa quyền sửa | Hiện hành | Trước môi trường | Thy mới nhất |
| docs/ENV03_DIAGNOSTIC.md | Kết luận file thiếu, dependency, phương án/rollback | LOCKED chẩn đoán, ENV04 mới ưu tiên về thực thi | GPT Web nghiệm thu | Evidence/package thực tế |
| docs/evidence/ENV03_READBACK.json | Process/config/plugin path/giới hạn SQL | Hiện hành theo thời điểm | Đối chiếu server | Không chứng nhận DB |
| docs/evidence/ENV03_OFFICIAL_PACKAGE.json, ENV03_PACKAGE_INVENTORY.json | Oracle8.4.3/GPG/hash/inventory297 | Hiện hành | Kiểm nguồn file | Archived key hết hạn được ghi rõ |
| docs/evidence/ENV03_DEPENDENCIES.json | PE import tĩnh | Hiện hành | Kiểm dependency | Không thay loader test |
| docs/evidence/ENV03_REPAIR_MANIFEST.json | Whitelist1DLL+43ICU | LOCKED nguồn; đã áp dụng ở ENV04 | Truy vết44file | Không replay copy |
| docs/evidence/ENV03_VERIFICATION.json | Bảo toàn installation/source/Git và hồ sơ | Hiện hành theo thời điểm | Bàn giao | Không QA G4A runtime |
| .agent/logs/env03/, .agent/private/env03/ | Raw evidence/package/staging/GPG/ZIP receipt | Local, không public | Truy vết | Không cài đặt hoặc public archive/DLL |
| .agent/G4A_CHECKLIST.md | Scope/checklist G4A | Hiện hành | Trước tiếp tục runtime/DB | Thy/G4A decision |
| .agent/decisions/G4A_AUTHORIZED_20261017.md | Nghiệm thu G23 và quyền G4A | Hiện hành | Đầu phiên/trước DB | Thy mới nhất |
| docs/G4A_RESULT.md | G4A SQL preflight và AUTH01 | Hiện hành | Bàn giao/tiếp tục | G4A_RESUME_* ưu tiên; startup cũ trong archive/ZIP ENV04 |
| .agent/decisions/G4A_DIRECT_MYSQL_AUTHORIZED_20261017.md | Thy duyệt direct-start config/datadir hiện hữu | Hiện hành | Trước MySQL | Điều kiện HARD STOP của Thy |
| docs/evidence/G4A_MYSQL_READBACK.json | Một instance/version/TCP/component/quyền chưa biết | Hiện hành theo thời điểm | Đối chiếu server | Không chứng nhận DB/G4A đạt |
| docs/evidence/G4A_MYSQL_STARTUP.json | Thử GUI trước direct approval | Lịch sử | Truy vết | MYSQL_READBACK mới ưu tiên |
| docs/evidence/G4A_STARTUP_LOG_SUMMARY.json | Log startup đã lọc | Hiện hành theo thời điểm | Kiểm ENV03 | Raw log SHA giữ trong bản tóm tắt |
| docs/evidence/G4A_STARTUP_SOURCE_DIFF.json | Danh sách source diff/hồ sơ | Hiện hành | Kiểm scope | Không runtime change |
| docs/evidence/G4A_STARTUP_VERIFICATION.json | Kiểm hồ sơ/hash runtime/protected Git | Hiện hành theo thời điểm | Bàn giao startup | Không thay thế QA DB |
| .agent/logs/g4a-startup/ | Raw startup/validation/readback/diff/ZIP receipts | Local | Kiểm chứng | Không public raw logs |
| docs/evidence/G4A_PREFLIGHT_VERIFICATION.json | Đối chiếu runtime byte/Git/readback hồ sơ trước startup | Lịch sử | Nghiệm thu hồ sơ blocker | Không chứng minh G4A chạy thật |
| docs/evidence/G4A_PREFLIGHT.json | Binary/process/port/giới hạn trước startup | Lịch sử | Kiểm ENV01 | Không chứng minh server/DB đã chạy |
| .agent/logs/g4a-preflight/ | Git/command/diff/verification/ZIP receipts | Local | Truy vết | Không public raw logs |
| .agent/G23_CHECKLIST.md | Scope + P01–P06 | Checkpoint đã nghiệm thu | Truy vết G23 | Quyền G4A mới ưu tiên |
| .agent/rule.md | Báo cáo/ZIP | Hiện hành | Viết hồ sơ | Không bịa runtime |
| .agent/mistake.md | Lỗi cần tránh | Hiện hành | Clone/Composer/ổ mạng | Không thay evidence |
| .agent/decisions/G23_ACCEPTED_SCOPE_20261017.md | Thy duyệt G2/G3/GitHub | Lịch sử | Truy vết | G4A_AUTHORIZED là quyền mới |
| docs/G2_DECISIONS.md | DEC01–06/module/DELETE | Hiện hành | Port/design | Source G1 + Thy |
| docs/ERD_MYSQL.md | Schema4 bảng/3FK | Hiện hành, chưa DDL | G4 | G2 |
| docs/G3_ISOLATION_RESULT.md | Thay đổi/test/giới hạn | Hiện hành | Nghiệm thu | Evidence |
| docs/CONVERSION_MAPPING.md | OLD→NEW và bước còn lại | Hiện hành | Chuyển module | G2/ERD |
| docs/ARCHITECTURE.md | Hiện tại/đích | Hiện hành | Thiết kế | Không suy app đã chạy |
| docs/FUNCTIONS_USE_CASES.md | Rubric/phân công | Hiện hành, chức năng TODO | Lập kế hoạch | Thy/rubric gốc khi có |
| docs/diagrams/use-cases.puml | UML Admin/User | Hiện hành, chưa render | Báo cáo | G2 scope |
| docs/IMPLEMENTATION_PLAN.md | G1–G6 | Hiện hành | Sau nghiệm thu | PLAN/checklist là trạng thái |
| docs/evidence/ | Manifest/diff/call graph/tóm tắt kiểm đã lọc | Hiện hành | GPT Web đối chiếu | Raw evidence local |
| docs/PUBLICATION.md | Git checkpoint/readback/giới hạn | Hiện hành sau publication | Nghiệm thu GitHub | Remote receipt |
| .agent/scripts/verify_g23.py, .agent/scripts/test_g23_isolation.php | Kiểm tĩnh/probe | Hiện hành | Kiểm hồi quy không DB | Chưa boot/container thật |
| .agent/logs/g23/ | Snapshot/verification/stage/readback gốc | Local, không public | Kiểm chứng | Phạm vi snapshot rõ |
| .agent/archive/g1-source/ | 104 file nguyên byte | Local, bất biến | Đối chiếu port | G1/manifest102 |
| docs/BIEN_BAN_GIAI_DOAN_1.md | Biên bản G1 | Lịch sử đã nghiệm thu hồ sơ | Truy vết | G2/G3 là hiện hành |
| docs/AUDIT_KLCN.md, docs/REUSED_FILES.md | Audit/inventory G1 | Lịch sử/source tham khảo | Đối chiếu source | SOURCE_DIFF là trạng thái mới |
| docs/README_GUI_GPT_WEB.md | ZIP G1 | Lịch sử | Truy vết | README hiện hành ưu tiên |
| .agent/CLONE_CHECKLIST.md, .agent/decisions/CLONE_FIRST_20261017.md | Scope G1 | Lịch sử | Truy vết | Không áp giới hạn đã được Thy thay |
| .agent/logs/clone/, .agent/logs/phase1/, .agent/logs/packaging/ | Evidence checkpoint trước | Local/lịch sử | Truy vết | Không chạy lại helper G1 |
| .agent/archive/initial-scaffold-20261017/, .agent/archive/klcn-reference/ | Scaffold/draft cũ | Local/lịch sử, chưa duyệt thiết kế | Truy vết | Không dùng schema6 bảng |

Ưu tiên: Thy mới nhất → source/artifact/evidence → decision → context/PLAN → lịch sử. Helper clone/write/docs/package G1 cũ là lịch sử; không tái sinh source G3 từ chúng.
