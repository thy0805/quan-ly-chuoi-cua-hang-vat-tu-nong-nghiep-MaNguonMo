# AUTH02 — local login

| ID | Hạng mục | Nguồn chuẩn | Trạng thái | Evidence | Ghi chú |
|---|---|---|---|---|---|
| L01 | Verify client/driver/management tool | Laragon hiện hữu | VERIFIED | mysql.exe8.4.3/SHA/AuthenticodeValid; HeidiSQL có; PHP8.3.30 pdo_mysql | Không DLL/ICU scan |
| L02 | Helper nhập credential và PDO session trong RAM | AUTH02 decision | VERIFIED phần chuẩn bị | PHP lint/Pythonparse; Python6544/HWND9831566/PHP15508; launch receipt | Thy đã nhập; authenticated một lần |
| L03 | Thy nhập trực tiếp, một lần authentication | Input local | VERIFIED | login-result authenticated;1attempt | Không lưu rootpassword |
| L04 | SQL identity/grants/database đích | Phiên hợp lệ sau L03 | VERIFIED | G4A_SQL_PREFLIGHT;root@localhost;4readonlyqueries;target absent | AUTH01 đã giải quyết |
| L05 | Account ứng dụng/quyền tối thiểu và G4A | G2/ERD/G4A,sau L04 | VERIFIED DB/account;G4A IN_PROGRESS | DB mới;runtimeCRUD,migratorDDL+CRUD schema-only | G4A_IMPLEMENTATION_CHECKLIST là bước tiếp |
