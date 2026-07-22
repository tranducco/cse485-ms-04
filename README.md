# cse485-ms-04
Phiếu bài tập 4
# MiniShop - Phiếu 04: PDO CRUD bảng Categories

## 📌 Giới thiệu
Dự án thực hành kết nối cơ sở dữ liệu MySQL bằng thư viện **PDO (PHP Data Objects)**. 
Hệ thống chuyển đổi việc lưu trữ danh mục từ mảng/biến tạm thời sang lưu trữ vĩnh viễn trên CSDL. Cung cấp đầy đủ tính năng CRUD (Thêm, Đọc, Sửa, Xóa).

## 📂 Hướng dẫn cài đặt CSDL
1. Bật MySQL trên XAMPP.
2. Truy cập `http://localhost/phpmyadmin/`.
3. Import file `schema.sql` đính kèm trong dự án, hoặc sao chép mã SQL chạy trực tiếp.
4. Lệnh kiểm tra dữ liệu thành công: `SELECT COUNT(*) FROM categories;` (Kết quả trả về >= 3).
5. Thông tin cấu hình (tại `config.php`):
   - Host: `127.0.0.1`
   - Database: `minishop_cse485`
   - User: `root`
   - Password: `(để trống)`

## 🚀 Tính năng nổi bật
- **An toàn dữ liệu:** Toàn bộ câu lệnh tương tác SQL đều sử dụng `prepare()` và `execute()` để chống lại rủi ro SQL Injection.
- **Xử lý ngoại lệ:** Ứng dụng `try/catch` để bắt lỗi trùng lặp `UNIQUE` constraint (mã lỗi 23000), không hiển thị trang trắng báo lỗi hệ thống.
- **Post/Redirect/Get (PRG):** Tránh submit trùng form khi người dùng nhấn F5.
- **Xóa bảo mật:** Nút xóa bắt buộc phải gửi dữ liệu qua phương thức POST và có cảnh báo Confirm bằng JavaScript.