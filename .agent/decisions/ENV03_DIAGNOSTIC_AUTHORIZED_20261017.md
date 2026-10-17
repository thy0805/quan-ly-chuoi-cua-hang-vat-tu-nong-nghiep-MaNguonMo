# ENV03 — quyền chẩn đoán chỉ đọc

Nguồn: prompt Thy gửi17/10/2026 trong attachment9c24a981-c380-4105-b7a7-df539520d5fe. Thy/GPT Web xác nhận ZIP startup trước khớp45/45 manifest/CRC/SHA; đây là nghiệm thu hồ sơ, không phải G4A runtime.

ALLOWED: Đọc process/port/config không secret/log/file/PE/hash; đối chiếu đúng Oracle8.4.3 Windowsx64; staging package chính thức và ghi hồ sơ/helper trong project. Source runtime không sửa.
FORBIDDEN: Sửa bộ cài/config/datadir/ACL/registry/firewall/tài khoản; copy DLL vào Laragon; restart/shutdown/thêm instance; initialize/skip-grant-tables/reset/system-schema; SQL/provision/migrate/seed; G4B/G5; commit/push.
SOURCE OF TRUTH: Prompt mới nhất → file/log/process/package thực tế → source mysql-8.4.3 → ENV03_DIAGNOSTIC/evidence.
INVARIANTS: Giữ server hiện hữu, checkpointG1/G23 và ba repo/database độc lập; không đọc/dò password.
ACCEPTANCE: Phân biệt file thiếu/dependency/path; whitelist khôi phục chính xác version/hash/source/dest/count, rollback và shutdown hợp lệ; kiểm bảo toàn/ZIP; dừng chờ duyệt sửa riêng.

Kết quả đọc tại docs/ENV03_DIAGNOSTIC.md. Quyền này không cho phép thực hiện phương án A/A+B dù đã có file staging. G4A chưa hoàn tất.
