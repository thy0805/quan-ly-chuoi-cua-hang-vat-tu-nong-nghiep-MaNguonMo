# Khóa và công bố C3 — 21/10/2026

## Scope contract

ALLOWED: 36 file C3 đúng ZIP đã duyệt; sáu tài liệu kèm ZIP; checklist/decision/biên bản khóa, danh sách Visual/Print PENDING; cập nhật phần trạng thái của chín tài liệu điều phối. Chỉ stage phần C3, commit riêng và push main đúng origin.

FORBIDDEN: sửa mã ứng dụng đã nghiệm thu, dependencies, cấu hình, database; migration/seed; ghi KLCN/NoSQL/Word/Rose; sửa ZIP/evidence đã khóa; đưa G7/audit cũ vào commit; reset/clean/stash/force push; mở C4/G7.

SOURCE OF TRUTH: yêu cầu Thy attachment cce85618-0805-4db4-850e-68b87f378b1d; baseline C2 2d5d323e5fa629508a83aa5f542081343a0f7902; ZIP C3 SHA-256 549bd1622801fdbe1c053de0f67cca47f97b074602703f4034a884041236588e; source/evidence thực tế.

INVARIANTS: source C3 khớp đủ 36 file đã duyệt; giữ source C1/C2 ngoài bảy điểm tích hợp C3; giữ nguyên G7/audit và accepted artifact; C1/C2/C3 chức năng LOCKED; C2/C3 Browser/Print PENDING; không ghi database.

ACCEPTANCE: manifest/CRC/hash/readback đạt; toàn bộ diff được rà và staged whitelist/secret/integrity đạt; remote baseline khớp trước push; local/remote SHA và tree/file list khớp sau push; bảo toàn trước/sau; ZIP evidence nhỏ được đọc lại.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| P1 | Baseline, index, remote và bảo toàn đầu lượt | Git / ZIP C3 | VERIFIED | .agent/logs/c3-lock/before.json | Không sửa source |
| P2 | Đối chiếu 36 source / 42 payload source và toàn bộ diff | ZIP / C3_VERIFICATION | VERIFIED | before.json; accepted-source.diff reverse check | Không chạy lại test ghi dữ liệu |
| P3 | Decision khóa, trạng thái hiện hành, Visual/Print checklist | Quyền Thy mới | VERIFIED | Decision khóa / C3_FUNCTIONAL_ACCEPTED / Visual checklist | Tài liệu DRAFT cũ là lịch sử |
| P4 | Stage whitelist, kiểm từng blob và secret | Source đã duyệt | APPLIED_UNVERIFIED | stage-review.json sau readback | Loại G7/audit |
| P5 | Commit, push và readback main | Đúng origin | TODO | — | Không force |
| P6 | Bảo toàn cuối, biên bản và ZIP evidence | Snapshot / Git / manifest | TODO | — | Dừng phát triển |
