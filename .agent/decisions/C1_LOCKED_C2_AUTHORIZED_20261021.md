# C1 ACCEPTED/LOCKED; C2 được giao — 21/10/2026

Nguồn quyền: Thy gửi attachment699ddd40-1a18-45c2-90c1-50287a7e691c. C1 được nghiệm thu trong phạm vi development và responsive QA:44/44tests,828assertions,4concurrency cases;15ảnh1366/768/320 đã được Thy/GPT Web kiểm. Database nghiệp vụ chưa migrate và cảm ứng điện thoại thật là giới hạn riêng.

Cho phép whitelist/stage/commit/push riêng C1 trên main đúng origin Mã nguồn mở nếu HEAD/remote không diverge. Không force; không commit G7/audit chưa nghiệm thu. Giữ bằng chứng C1 và G1–G6; C2 chỉ kế thừa cơ chế kho, mọi sửa integration phải được nêu/test hồi quy.

Cho phép triển khai trọn C2 draft nhiều dòng, tiền chính xác, nháp/xác nhận/in; source_type=sale, source_id từng dòng; khóa product thứ tự ID ổn định; transaction toàn hóa đơn; chống xác nhận lặp. User là nhân viên tạo/sửa/xác nhận/xem/in phiếu của mình; Admin xem toàn bộ và lập/xác nhận phiếu của mình. Không sửa/xóa phiếu đã xác nhận, không chuyển ownership, không cấp quyền CRUD quản trị cho User. Mọi ghi kho vẫn qua InventoryService; quyền ghi kho tay C1 vẫn Admin.

Chỉ ghi/migrate/fixture trên schema test mnm_inventory_test_20261021 đã có, runtime profile/account giữ tách biệt; database nghiệp vụ chỉ đọc. Không C3/G7/Word/Rose/KLCN/NoSQL; không commit/push C2 trước nghiệm thu. Browser không callable trong phiên hiện tại: QA visual C2 PENDING nếu chưa có công cụ thật.
