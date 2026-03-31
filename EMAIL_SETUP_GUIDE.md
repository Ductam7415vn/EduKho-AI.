# 📧 HƯỚNG DẪN CẤU HÌNH EMAIL CHO HỆ THỐNG

## 🎯 PHƯƠNG ÁN 1: MAILTRAP (Development/Testing)

### Bước 1: Đăng ký Mailtrap
1. Truy cập https://mailtrap.io
2. Click "Sign Up" - Đăng ký miễn phí
3. Xác nhận email

### Bước 2: Lấy thông tin SMTP
1. Đăng nhập Mailtrap
2. Vào "Email Testing" → "Inboxes"
3. Click vào "My Inbox"
4. Chọn tab "SMTP Settings"
5. Chọn "Laravel 9+" trong dropdown

### Bước 3: Cấu hình .env
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username_here
MAIL_PASSWORD=your_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@truong.edu.vn"
MAIL_FROM_NAME="Hệ thống Quản lý Thiết bị"
```

### Bước 4: Clear cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Bước 5: Test gửi email
```bash
# Test bằng tinker
php artisan tinker
>>> Mail::raw('Test email', function($message) {
...     $message->to('test@example.com')->subject('Test');
... });
```

---

## 🚀 PHƯƠNG ÁN 2: GMAIL (Production nhỏ)

### Bước 1: Tạo App Password
1. Đăng nhập Gmail
2. Vào https://myaccount.google.com/security
3. Bật "2-Step Verification" nếu chưa bật
4. Click "App passwords"
5. Chọn "Mail" và "Other (Custom name)"
6. Đặt tên: "Equipment Management System"
7. Copy mật khẩu 16 ký tự

### Bước 2: Cấu hình .env
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your.email@gmail.com
MAIL_PASSWORD=your_16_char_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your.email@gmail.com"
MAIL_FROM_NAME="Hệ thống Quản lý Thiết bị"
```

⚠️ **Lưu ý Gmail**: 
- Giới hạn 500 email/ngày
- Có thể bị chặn nếu gửi quá nhiều
- Chỉ dùng cho hệ thống nhỏ

---

## 🏢 PHƯƠNG ÁN 3: SMTP TỔ CHỨC (Production)

### Bước 1: Liên hệ IT
Yêu cầu thông tin:
- SMTP server address
- Port (thường là 587 hoặc 465)
- Username (email account)
- Password
- Encryption (TLS/SSL)

### Bước 2: Cấu hình .env
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.truonghoc.edu.vn
MAIL_PORT=587
MAIL_USERNAME=hethong@truonghoc.edu.vn
MAIL_PASSWORD=password_from_IT
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@truonghoc.edu.vn"
MAIL_FROM_NAME="Hệ thống Quản lý Thiết bị"
```

---

## 💼 PHƯƠNG ÁN 4: DỊCH VỤ CHUYÊN NGHIỆP (Production lớn)

### SendGrid (Khuyên dùng)
1. Đăng ký: https://sendgrid.com
2. Free tier: 100 email/ngày
3. Cấu hình:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
```

### Amazon SES
1. Cần tài khoản AWS
2. Rất rẻ: $0.10/1000 emails
3. Cấu hình phức tạp hơn

### Mailgun
1. Đăng ký: https://mailgun.com
2. Free: 5000 email/tháng (3 tháng đầu)
3. Cấu hình tương tự SendGrid

---

## 🧪 KIỂM TRA SAU KHI CẤU HÌNH

### 1. Test Password Reset
```bash
# 1. Vào trang quên mật khẩu
http://localhost/forgot-password

# 2. Nhập email: admin@truong.edu.vn

# 3. Check email tại:
- Mailtrap: https://mailtrap.io/inboxes
- Gmail: Hộp thư đến
- Hoặc logs: storage/logs/laravel.log
```

### 2. Test Notifications
```bash
# Tạo phiếu mượn để test thông báo
# Admin sẽ nhận email "Pending Approval"
# User sẽ nhận email khi được duyệt/từ chối
```

### 3. Test Command
```bash
# Test gửi nhắc nhở trả thiết bị
php artisan reminders:send-return

# Test cảnh báo quá hạn
php artisan check:overdue-borrows
```

---

## 🔧 XỬ LÝ LỖI THƯỜNG GẶP

### Lỗi: "Connection could not be established"
```bash
# Kiểm tra kết nối
telnet smtp.mailtrap.io 2525

# Nếu bị firewall chặn, thử port khác
MAIL_PORT=587
# hoặc
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### Lỗi: "Authentication failed"
- Kiểm tra lại username/password
- Gmail: Dùng App Password, không phải mật khẩu thường
- Mailtrap: Copy chính xác từ dashboard

### Lỗi: Email không đến
1. Check spam folder
2. Kiểm tra logs:
```bash
tail -f storage/logs/laravel.log
```
3. Test với Mailtrap trước

---

## 📝 CHECKLIST TRIỂN KHAI

- [ ] Chọn phương án phù hợp
- [ ] Đăng ký dịch vụ (nếu cần)
- [ ] Cấu hình .env
- [ ] Clear cache
- [ ] Test password reset
- [ ] Test notifications
- [ ] Test scheduled commands
- [ ] Cấu hình SPF/DKIM (production)
- [ ] Monitor email logs

---

## 🎯 GỢI Ý

### Môi trường Development
→ Dùng **Mailtrap** (an toàn, dễ test)

### Hệ thống nhỏ (<100 users)
→ Dùng **Gmail** với App Password

### Hệ thống trường học
→ Dùng **SMTP của trường** (nếu có)

### Hệ thống lớn/chuyên nghiệp
→ Dùng **SendGrid** hoặc **Amazon SES**

---

## 📞 HỖ TRỢ

Nếu gặp vấn đề:
1. Check Laravel docs: https://laravel.com/docs/mail
2. Check service docs (Mailtrap, SendGrid, etc.)
3. Post error logs để được hỗ trợ

---

*Cập nhật: 29/03/2026*