# ENV04 — phục hồi có điều kiện A+B, 17/10/2026

ALLOWED: Thy duyệt đúng44 file/3837376 byte trong ENV03_REPAIR_MANIFEST từ Oracle8.4.3; preflight/hash/log/manifest/backup config; chỉ sau shutdown hoàn chỉnh bằng phiên quản trị hoặc process-owner Windows hợp lệ theo decision bổ sung mới tạo thư mục/chép file missing, một lần startup bằng config/datadir hiện hữu và kiểm chứng. Ghi hồ sơ/helper/ZIP trong project.
FORBIDDEN: Ghi đè file có sẵn, mở rộng whitelist, thay bin/my.ini/datadir/ACL/registry/firewall, đọc/dò/giải mã/reset password, initialize/skip-grant-tables, force kill, database/migration/seed/Laravel/G4B/G5/commit/push. KLCN/NoSQL/Mobile/Word/Rose giữ nguyên.
SOURCE OF TRUTH: Prompt ENV04 attachment494169c9-6c9f-4f2a-9d2d-843f18458b97 → bytes/process/log hiện hành → ENV03 manifest/package đã nghiệm thu. Bổ sung của Thy: chưa có tài khoản, nhờ tạo và ghi bàn giao; việc tạo cần phiên hiện hữu có quyền phù hợp, không cho phép bypass xác thực.
INVARIANTS: Whitelist44 đường dẫn/hash, 1DLL+43ICU,3837376byte;127file cũ/my.ini/binary giữ;68file runtime/Git checkpoint/source bảo vệ giữ. Không coi bản sao datadir đang chạy là backup nhất quán.
ACCEPTANCE: Preflight đạt, shutdown hợp lệ/log hoàn chỉnh/port đóng/PID thoát; exact-set44hash tại đích;127file trước giữ; startup/process/version/TCP/log component+ICU đạt, protected verification, ZIP/readback. Thiếu cả cách SQL hợp lệ lẫn shutdown owner hợp lệ thì STOP trước chép; chỉ xác minh hồ sơ/preflight, không ENV04 VERIFIED.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| R01 | Quyền ENV04 và bổ sung owner | Thy/decisions | VERIFIED | ENV04_OWNER_SHUTDOWN_AUTHORIZED | Không account không cần thiết |
| R02 | Fresh preflight/archive/staging/path/backup | Disk/manifest | VERIFIED | preflight-owner.json;44hash/bytes,127files,backup private |66ancestor paths |
| R03 | Shutdown an toàn | Source8.4.3/OSowner/receipt | VERIFIED | owner-shutdown + shutdown-readback:2exit0,logcomplete,Windowsnoports |1event,0force/SQL |
| R04 | Phục hồi44file | Manifest/restore | VERIFIED | restore.json:44hash/exactset,3837376byte,127oldgiữ,171total |5dirs/0overwrite |
| R05 | Startup/load/ICU | New startup/module/log | VERIFIED | startup.json:10032→4968,8.4.3,DLLloaded,ICUmissingabsent |SQLmeta/regexp chưa test |
| R06 | Integrity/protected/hồ sơ/ZIP | Final verification | VERIFIED đúngscope | ENV04_VERIFICATION + candidate/packaging CRC/memberSHA;127old/44added/171total/68runtime/Git | ENV04 chờ Thy nghiệm thu; không G4A |
