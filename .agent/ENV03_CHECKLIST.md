# ENV03_DIAGNOSTIC — 17/10/2026

ALLOWED: Chẩn đoán chỉ đọc MySQL8.4.3 đang chạy; process/port/log/config/plugin/file/hash/dependency; đối chiếu package chính thức và bản lưu local. Chỉ ghi hồ sơ/helper/staging chẩn đoán trong dự án, không thay source runtime.
FORBIDDEN: Restart/shutdown/thêm server, copy DLL vào bộ cài, thay binary/config/ACL/registry/firewall/datadir, initialize/skip-grant-tables/reset/system schema, SQL ghi/provision/migration/seed/G4B/G5, commit/push.
SOURCE OF TRUTH: Prompt ENV03 Thy17/10; process/log/file hiện hành; package Windowsx64 MySQL8.4.3 Oracle và source tag mysql-8.4.3. Không lấy phiên bản mới hoặc suy từ errno126 khi chưa kiểm file.
INVARIANTS: Một instance hiện hữu, my.ini/hash/source runtime/Git checkpoint giữ; không credential reads; không thay KLCN/NoSQL.
ACCEPTANCE: Evidence plugin path/file inventory/version/hash/log/dependency, nguồn so sánh đúng version; phân biệt thiếu file với dependency; phương án phục hồi tối thiểu có đường dẫn/hash/backup/rollback và điều kiện restart; ZIP readback/SHA. Chỉ kết luận đúng phạm vi chứng minh.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| E01 | Baseline process/config/source/Git | Disk/CIM/Git | VERIFIED | ENV03_READBACK + ENV03_VERIFICATION:18852→33712,127installation/68runtime nguyên hash; protected Git giữ | Không restart/SQL |
| E02 | Plugin path/lib/dependency/local package | File/log/PE | VERIFIED trong phạm vi chẩn đoán | lib absent; DLL AMD64;4DLL vật lý có; API-set/loader chưa test; tìm package local theo scope | Không chứng nhận load runtime |
| E03 | So sánh chính thức đúng8.4.3 | Oracle package/source tag | VERIFIED |297file;126matches/0mismatch; GPG VALIDSIG, archived key expired ghi rõ; DLL Authenticode Valid | Chỉ staging, không cài |
| E04 | Nguyên nhân/phương án tối thiểu/rollback | E01–03 | VERIFIED hồ sơ đề xuất | ENV03_DIAGNOSTIC + whitelist44file; A1DLL/A+B44; source/dest/hash/count/rollback | Phục hồi vẫn TODO, chưa duyệt |
| E05 | Đồng bộ/report/readback/ZIP | Evidence thực tế | VERIFIED hồ sơ chẩn đoán | ENV03_VERIFICATION; candidate-packaging/packaging local:CRC + member SHA; đọc lại report/links/manifest | Không G4A runtime; dừng chờ Thy |
