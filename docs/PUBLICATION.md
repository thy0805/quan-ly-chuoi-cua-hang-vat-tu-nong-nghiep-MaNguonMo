# Publication G2/G3 — hồ sơ kiểm trước xuất bản

Repo duy nhất: https://github.com/thy0805/quan-ly-chuoi-cua-hang-vat-tu-nong-nghiep-MaNguonMo, branch main. Git root đã xác minh đúng thư mục Mã nguồn mở; remote được kiểm trống bằng ls-remote và GitHub API, Public, có quyền push. Không ghi Git/index/config repo cha.

G1 source checkpoint: `87b605b26cda9b8e3210beef807efb130a273a74` (102 source và2 mẫu/ignore). G2/G3 commit sẽ tách riêng. Trạng thái xuất bản hiện tại: IN_PROGRESS, chưa dùng tài liệu này như bằng chứng remote readback.

G2 VERIFIED đặc tả; G3 VERIFIED tĩnh/biên, lint26/probe38/process12/Composer3. Chưa boot Laravel/container thật, chưa MySQL/migration/seed/Auth/CRUD/Blade/Bootstrap. HTTP503 và DB-deny chủ động giữ điểm dừng. G4 TODO.

Protected-source before/after đã đối chiếu HEAD/status/hash/Git metadata: KLCN319 file/11 dòng dirty Mobile cũ; NoSQL1030 file/clean; parent2969 tracked/5343 dòng dirty cũ (chỉ loại subtree dự án mới). Tất cả giữ. Không kiểm nội dung ignored source hoặc DB. Bản lọc evidence/PROTECTED_SOURCE.json.

Trước commit/push: từng staged path phải qua whitelist, secret pattern scan cả G1 history/current, kiểm .env mẫu trống secret, SQL chỉ schema không development rows, size<2MB, mode không symlink/submodule, không raw logs/private/archive/dependency. Kết quả và danh mục nằm local; bản tóm tắt phù hợp ở docs/evidence sau hoàn tất gate. Chỉ khi đạt mới push không force và đối chiếu remote SHA/tree/blob.

Thy/GPT Web đọc README → G2_DECISIONS → G3_ISOLATION_RESULT → ERD_MYSQL; source G1 đọc tại commit G1. Không đồng nhất commit/push với nghiệm thu runtime hoặc quyền mở G4.
