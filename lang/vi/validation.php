<?php

return [
    'required' => 'Vui lòng nhập :attribute.',
    'string' => ':Attribute phải là chuỗi ký tự.',
    'boolean' => ':Attribute phải là trạng thái hợp lệ.',
    'integer' => ':Attribute phải là số nguyên.',
    'numeric' => ':Attribute phải là số.',
    'decimal' => ':Attribute chỉ được có :decimal chữ số thập phân.',
    'min' => ['numeric' => ':Attribute phải lớn hơn hoặc bằng :min.', 'string' => ':Attribute phải có ít nhất :min ký tự.', 'array' => ':Attribute phải có ít nhất :min phần tử.'],
    'max' => ['numeric' => ':Attribute không được vượt quá :max.', 'string' => ':Attribute không được dài quá :max ký tự.', 'array' => ':Attribute không được có quá :max phần tử.'],
    'between' => ['numeric' => ':Attribute phải nằm trong khoảng :min đến :max.', 'string' => ':Attribute phải có từ :min đến :max ký tự.'],
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'unique' => ':Attribute đã được sử dụng.',
    'exists' => ':Attribute đã chọn không tồn tại.',
    'in' => ':Attribute đã chọn không hợp lệ.',
    'prohibited' => 'Không được cung cấp :attribute.',
    'uuid' => ':Attribute không hợp lệ. Hãy mở phiếu mới.',
    'date_format' => ':Attribute phải có định dạng :format.',
    'after_or_equal' => ':Attribute phải từ :date trở đi.',
    'attributes' => [
        'username' => 'tên đăng nhập', 'password' => 'mật khẩu', 'password_confirmation' => 'xác nhận mật khẩu',
        'code' => 'mã', 'name' => 'tên', 'parent_id' => 'danh mục cha', 'category_id' => 'danh mục', 'unit_id' => 'đơn vị tính',
        'active_ingredient' => 'hoạt chất', 'sale_price' => 'giá bán', 'tax_rate' => 'thuế', 'expiry_warning_days' => 'số ngày cảnh báo hạn',
        'is_active' => 'trạng thái hoạt động', 'role' => 'vai trò', 'q' => 'từ khóa', 'page' => 'số trang', 'per_page' => 'số dòng', 'active_only' => 'bộ lọc hoạt động',
        'product_id' => 'vật tư', 'quantity' => 'số lượng', 'note' => 'ghi chú', 'idempotency_key' => 'mã xác nhận', 'direction' => 'loại giao dịch', 'stock_status' => 'tình trạng tồn', 'from' => 'ngày bắt đầu', 'to' => 'ngày kết thúc',
    ],
];
