# Publication G2/G3 — đã kiểm nguồn trên GitHub

Repo: https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo

Branch: `main`. G1 source checkpoint: `87b605b26cda9b8e3210beef807efb130a273a74`.

**G2/G3 source checkpoint đã push và readback: `c73608680a9e94d6679e6cd791e2373aca560cb1`.** Commit ghi biên bản này là mốc tài liệu tiếp theo; source không đổi. SHA HEAD cuối và ZIP SHA-256 được bàn giao trực tiếp cho Thy, receipt cuối giữ ở `.agent/logs/g23/publication-final.json` trên máy.

## Kết quả publication nguồn

- Remote được kiểm trống trước push; Git root đúng dự án mới, origin duy nhất đúng URL repo môn học. Không force, không đổi lịch sử và không stage/index/config repo cha.
- Tại source checkpoint:102 file, gồm39 source/cấu hình,1 probe kiểm thử,34 tài liệu/evidence và28 file đánh dấu thư mục rỗng. Commit biên bản tiếp theo bổ sung một JSON receipt, không bổ sung chức năng.
- Remote SHA = local SHA; fetch lại commit, diff rỗng. GitHub API recursive tree đối chiếu toàn bộ102 đường dẫn/blob SHA/mode với local, khớp; working tree clean. Source extraction riêng từ Git chạy lại probe38 đạt, không vendor hoặc DB.
- Secret scan/whitelist kiểm102 file staged hiện tại và104 file G1 history;0 phát hiện theo bộ mẫu. Mẫu .env không credential; SQL G1 chỉ DDL, không dữ liệu development; không symlink/submodule hoặc file>2MB. Pattern scan không chứng minh mọi dạng secret tuyệt đối; đã rà thêm nguồn/fixture synthetic và phạm vi từng file.
- Không public archive/scaffold/private/raw log/dependency/cache/build/dữ liệu DB. Trong storage chỉ có .gitignore giữ thư mục rỗng. Manifest nguồn đã bỏ đường dẫn máy/share; evidence public là tóm tắt, lệnh/exit code, không raw output.
- G1 giữ trong lịch sử;102 source khớp hash trước sửa, archive104 nguyên byte local giữ. Git có thể chuẩn hóa newline; không thay archive bằng suy luận từ Git blob.

## Trạng thái giai đoạn và protected source

G1 ACCEPTED về hồ sơ clone/audit. G2 VERIFIED đặc tả DEC01–06/ERD4 bảng/3FK. G3 VERIFIED tĩnh/biên với lint26,probe38,process12,Composer3. HTTP503/DB-deny chủ động. Chưa boot Laravel/container thật; chưa MySQL/DDL/migration/seed/tài khoản/Auth/CRUD/Blade/Bootstrap hoặc browser QA. Mermaid/PlantUML chưa render. G4 TODO.

Snapshot KLCN/NoSQL/parent trước/sau giữ HEAD/status/hash/Git metadata trong phạm vi kiểm: KLCN319 file và11 dirty Mobile cũ; NoSQL1030 file clean; parent2969 tracked/5343 dòng dirty cũ sau loại subtree mới. Không hash ignored originals hoặc probe DB. Xem evidence/PROTECTED_SOURCE.json; raw before/after giữ local.

## Hồ sơ nghiệm thu và điểm dừng

Đọc README → G2_DECISIONS → G3_ISOLATION_RESULT → ERD_MYSQL → CONVERSION_MAPPING; evidence/G1_MANIFEST, SOURCE_DIFF, CALL_GRAPH, G23_VERIFICATION, SECURITY_SUMMARY và PUBLICATION_SOURCE. Source legacy đọc ở commit G1; không gọi module cũ như chức năng mới.

Đã kiểm preview ZIP32 entry, CRC và từng byte khớp payload/workspace. ZIP giao cuối `MNM_NHOM6_G2_G3_20261017.zip` gồm hồ sơ/evidence đã lọc, BAT_DAU_DOC, manifestSHA và readback HEAD cuối; không nén source/dependency. Receipt ZIP giữ local.

Dừng chờ Thy/GPT Web nghiệm thu; không mở G4, gỡ guard hoặc chạy DB sau publication.
