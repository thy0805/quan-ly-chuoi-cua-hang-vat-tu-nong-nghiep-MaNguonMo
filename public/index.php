<?php

http_response_code(503);
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: no-store');
echo 'MNM G3: runtime nghiệp vụ đang khóa, chờ triển khai và nghiệm thu giai đoạn kế tiếp.';
