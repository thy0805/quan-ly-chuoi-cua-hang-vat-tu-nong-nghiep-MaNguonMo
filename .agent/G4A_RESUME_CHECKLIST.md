# G4A tiếp tục sau nghiệm thu ENV04

Scope contract: [quyết định hiện hành](decisions/ENV04_ACCEPTED_G4A_RESUMED_20261017.md). Không thay checklist ENV04 đã khóa.

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| S01 | Ghi nghiệm thu ENV04, khóa artifact | Thy/ZIP ENV04 | VERIFIED; ENV04 LOCKED | ZIP66manifest/CRC/SHA +11artifact giữbyte | Không replay helper |
| S02 | Runtime MySQL,68source và protectedGit | Baseline G4A/ENV04 | VERIFIED đúng phạm vi | G4A_RESUME_VERIFICATION;171installation/68runtime/Git giữ | Không restart; chưa SQLmetadata |
| S03 | Tìm phương thức xác thực hợp lệ hiện có | Môi trường quản trị hiện hành | VERIFIED kiểm metadata; AUTH01 còn mở | G4A_RESUME_AUTH_PREFLIGHT;0authattempt/0SQL | Không kết luận thiếu account/profile; không đọc password |
| S04 | SQL identity,quyền,DB riêng | Phiên hợp lệ sau S03 | TODO — BLOCKED AUTH01 | Chưa truy vấn | Thy đăng nhập account hiện có tại máy |
| S05 | Dependency,guard,migration/model/seeder | G2/ERD/lock,sau S04 | TODO | Chưa áp dụng | Không mở G4B/G5 |
| S06 | QA MySQL thật và schema readback | S05/fixture riêng | TODO | Chưa chạy | Không thay bằng SQLite |
| S07 | Kết quả,recovery,ZIP/readback | Evidence mới | VERIFIED hồ sơ blocker | Readback Markdown/link; candidate/final ZIP CRC/exact-set/memberSHA; packaging receipt local | Dừng AUTH01; G4A runtime chưa VERIFIED |
