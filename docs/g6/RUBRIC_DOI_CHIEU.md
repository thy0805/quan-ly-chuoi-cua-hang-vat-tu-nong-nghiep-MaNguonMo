# Đối chiếu tiêu chí môn học — G6 DRAFT

Rubric dựa mô tả Thy, chưa phải xác nhận điểm/phiếu chấm chính thức. Source/test/UI G5 đã nghiệm thu; tài liệu G6 vẫn chờ duyệt. Các test dưới thuộc lần hồi quy31/674 trước push; browser/evidence ảnh là checkpoint G5, không giả chạy lại browser trong G6.

| ID | Tiêu chí | Code thực | Test thực | Giao diện/readback | Trạng thái và giới hạn |
|---|---|---|---|---|---|
| R01 | Laravel/MySQL riêng | [composer.json](../../composer.json) | [test_isolation_rejects_unapproved_database_host_port_account_and_commands](../../tests/Feature/G4AMysqlTest.php) | [Evidence](../evidence/G6_MYSQL_SCHEMA.json) | G5 LOCKED; Metadata/identity thực, sourceLaravel không Next/Flutter |
| R02 | CRUD ≥2bảng có quan hệ | [app/Http/Controllers/CatalogController.php](../../app/Http/Controllers/CatalogController.php) | [test_admin_catalog_crud_and_restrict_over_http](../../tests/Feature/G4BHttpTest.php) | [Evidence](../evidence/g5-screenshots/products-1366.jpg) | G5 LOCKED; Category–Product và Unit–Product; C/U/D kiểm rollback |
| R03 | Đăng nhập/đăng xuất | [app/Http/Controllers/SessionController.php](../../app/Http/Controllers/SessionController.php) | [test_session_fixation_logout_and_old_cookie_replay](../../tests/Feature/G4BHttpTest.php) | [Evidence](../evidence/g5-screenshots/login-1366.jpg) | G5 LOCKED; LaravelAuth/session/CSRF, không lộ hash |
| R04 | Admin/User + middleware | [app/Http/Middleware/RequireAdmin.php](../../app/Http/Middleware/RequireAdmin.php) | [test_user_read_access_and_server_side_write_denial](../../tests/Feature/G4BHttpTest.php) | [Evidence](../evidence/g5-screenshots/user-forbidden-320.jpg) | G5 LOCKED; 403server; active middleware kiểm user fresh |
| R05 | Tìm kiếm/lọc | [app/Http/Controllers/CatalogController.php](../../app/Http/Controllers/CatalogController.php) | [test_search_unicode_wildcards_and_stable_pagination](../../tests/Feature/G4BHttpTest.php) | [Evidence](../evidence/g5-screenshots/search-320.jpg) | G5 LOCKED; Binding, Unicode và filter hiện hữu |
| R06 | Phân trang | [app/Http/Requests/CatalogListRequest.php](../../app/Http/Requests/CatalogListRequest.php) | [test_filter_pagination_empty_state_and_escaped_delete_label](../../tests/Feature/G5PresentationTest.php) | [Evidence](../evidence/g5-screenshots/pagination-1366.jpg) | G5 LOCKED; per_page1..50/giữ query, UI Trang sau |
| R07 | Validation biểu mẫu | [app/Http/Requests/CatalogWriteRequest.php](../../app/Http/Requests/CatalogWriteRequest.php) | [test_html_validation_old_input_success_and_real_csrf_are_retained](../../tests/Feature/G5PresentationTest.php) | [Evidence](../evidence/g5-screenshots/validation-768.jpg) | G5 LOCKED; Rule server/old-input/errorvi/CSRF |
| R08 | Eloquent relations/ORM | [app/Models/Product.php](../../app/Models/Product.php) | [test_synthetic_seed_models_relations_casts_and_password_hashes](../../tests/Feature/G4AMysqlTest.php) | [Evidence](../evidence/g5-screenshots/detail-320.jpg) | G5 LOCKED; belongsTo/hasMany/casts, không float tiền |
| R09 | Laravel Migration | [database/migrations/2026_10_17_000004_create_products_table.php](../../database/migrations/2026_10_17_000004_create_products_table.php) | [test_schema_matches_signed_keys_relations_checks_and_generated_admin](../../tests/Feature/G4AMysqlTest.php) | [Evidence](../evidence/G6_MYSQL_READBACK.json) | G5 LOCKED; Migration đã có/ápG4A; G6 không chạy lại |
| R10 | Laravel Seeder | [database/seeders/DatabaseSeeder.php](../../database/seeders/DatabaseSeeder.php) | [test_synthetic_seed_models_relations_casts_and_password_hashes](../../tests/Feature/G4AMysqlTest.php) | [Evidence](../evidence/G5_FIXTURE_INTEGRITY.json) | G5 LOCKED; Demo tổng hợp3/5/3/4; guard schema rỗng |
| R11 | Blade/Bootstrap | [resources/views/layouts/app.blade.php](../../resources/views/layouts/app.blade.php) | [test_all_resource_html_screens_preserve_forms_csrf_and_relationship_labels](../../tests/Feature/G5PresentationTest.php) | [Evidence](../evidence/g5-screenshots/product-create-1366.jpg) | G5 LOCKED; Assets offline5.3.8/MIT, HTML thật |
| R12 | Dashboard thật | [app/Http/Controllers/DashboardController.php](../../app/Http/Controllers/DashboardController.php) | [test_dashboard_reads_real_counts_and_does_not_write_business_data](../../tests/Feature/G5PresentationTest.php) | [Evidence](../evidence/g5-screenshots/dashboard-1366.jpg) | G5 LOCKED; SELECT Eloquent; User không thấy thống kê account |

| ID | Tiêu chí tài liệu/điểm cộng | Nguồn | Trạng thái |
|---|---|---|---|
| R13 | ERD thực/schema/quan hệ | [ERD](ERD_MYSQL_THUC_TE.md), [SVG](diagrams/erd.svg), [metadata](../evidence/G6_MYSQL_SCHEMA.json) | G6 DRAFT; technical render QA theo G6_VERIFICATION, chưa nghiệm thu |
| R14 | UML Use Case/mô tả chức năng | [Use Case](UML_USE_CASE.md), [SVG](diagrams/use-cases.svg), [đề tài](DE_TAI_CHUC_NANG.md) | G6 DRAFT;2actor/9UC/14association từ route |
| R15 | Responsive | [BrowserQA](../evidence/G5_BROWSER_QA.json), [mobile](../evidence/g5-screenshots/user-products-320.jpg) | G5 LOCKED trong mẫu1366/768/320; chưa mọi thiết bị |
| R16 | Phân công/demo/startup | [Nhóm6](DE_TAI_CHUC_NANG.md), [demo](DEMO_KHOI_DONG.md), [startup probe](../evidence/G6_STARTUP_CHECK.json) | Đề xuất phân công/kịch bản; chưa demo thầy hoặc xác nhận đóng góp |
| R17 | Upload ảnh/AJAX | Chưa triển khai | TODO; không tính điểm cộng đã đạt hoặc tự mở scope |

Khoảng trống nghiệm thu: phiếu chấm gốc; nhóm xác nhận phân công; tài liệu nộp cuối/định dạng nếu giảng viên yêu cầu; buổi demo thật; kiểm nâng cao ngoài ma trận. Không thay code để khớp tiêu chí chưa được cung cấp.
