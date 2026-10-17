# Bản đồ tài liệu G2/G3

| Tài liệu | Vai trò | Trạng thái | Đọc khi | Phụ thuộc / ưu tiên |
|---|---|---|---|---|
| AGENTS.md | Quy tắc riêng | Hiện hành | Đầu phiên | Thy mới nhất ưu tiên |
| context.md | Recovery/trạng thái | Hiện hành | Đầu phiên | Artifact/evidence thắng mô tả |
| .agent/PLAN.md | Giai đoạn | Hiện hành | Chọn task | G23_CHECKLIST |
| .agent/G23_CHECKLIST.md | Scope + P01–P06 | Hiện hành | Trước kiểm/push/handoff | Thy/G1/source |
| .agent/rule.md | Báo cáo/ZIP | Hiện hành | Viết hồ sơ | Không bịa runtime |
| .agent/mistake.md | Lỗi cần tránh | Hiện hành | Clone/Composer/ổ mạng | Không thay evidence |
| .agent/decisions/G23_ACCEPTED_SCOPE_20261017.md | Thy duyệt G2/G3/GitHub | Hiện hành | Trước sửa/push | Thay hạn chế G1 theo scope mới |
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
