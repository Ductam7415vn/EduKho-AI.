# 📚 HƯỚNG DẪN SỬ DỤNG HỆ THỐNG QUẢN LÝ THIẾT BỊ DẠY HỌC

## 🔐 TÀI KHOẢN ĐĂNG NHẬP

### Tài khoản Quản trị viên (Admin)
- **Email:** admin@truong.edu.vn
- **Mật khẩu:** password
- **Vai trò:** Cán bộ thiết bị - Có toàn quyền quản lý hệ thống

### Tài khoản cán bộ quản lý
| Họ tên | Email | Mật khẩu | Chức vụ |
|--------|-------|----------|----------|
| Dương Thanh Hội | thanhhoi.duong@truong.edu.vn | password | Hiệu trưởng |
| Châu Thị Ngọc Phượng | ngocphuong.chau@truong.edu.vn | password | Phó Hiệu trưởng |
| Thân Văn Tuấn | vantuan.than@truong.edu.vn | password | Phó Hiệu trưởng |

### Tài khoản Giáo viên (Mẫu)
Hệ thống hiện có **41 người dùng** được phân bổ trong 4 tổ chuyên môn:

#### Tổ KHTN (Khoa học tự nhiên) - 13 người
- **Tổ trưởng:** Nguyễn Thụy Cường (thuycuong.nguyen@truong.edu.vn)
- **Giáo viên:** Vũ Thu Thảo, Phạm Ngọc Guyên, Nguyễn Hải Hà, và các thành viên khác

#### Tổ T-A-TI-TC-QP (Toán-Anh-Tin-Thể chất-Quốc phòng) - 10 người  
- **Tổ trưởng:** Bùi Thị Hiệp (thihiep.bui@truong.edu.vn)
- **Giáo viên:** Nguyễn Kim Loan, Võ Hoàng Tiên, Phạm Quốc Thăng, và các thành viên khác

#### Tổ KHXH (Khoa học xã hội) - 11 người
- **Tổ trưởng:** Nguyễn Thị Hà (thiha.nguyen@truong.edu.vn)
- **Giáo viên:** Nguyễn Thị Thủy, Nguyễn Thị Lan, Đặng Thị Thúy, và các thành viên khác

#### Văn phòng - 7 người
- **Nhân viên văn phòng:** Đỗ Thị Mai, Lê Thị Quyên, Phạm Thị Thu, và các thành viên khác

**Lưu ý:** Tất cả tài khoản giáo viên đều có mật khẩu mặc định là `password`

---

## 📋 HƯỚNG DẪN SỬ DỤNG CÁC TÍNH NĂNG

### 1. 🔑 Xác thực và Bảo mật

#### Đăng nhập
1. Truy cập trang đăng nhập `/login`
2. Nhập email và mật khẩu
3. Nhấn "Đăng nhập"
4. **Happy Case:** Đăng nhập thành công → Chuyển đến Dashboard phù hợp với vai trò

#### Quên mật khẩu
1. Click "Quên mật khẩu?" tại trang đăng nhập
2. Nhập email đã đăng ký
3. Kiểm tra email và click link reset
4. Đặt mật khẩu mới
5. **Happy Case:** Đặt mật khẩu thành công → Đăng nhập bằng mật khẩu mới

#### Kích hoạt xác thực 2 yếu tố (2FA)
1. Vào Hồ sơ → Bảo mật → Xác thực 2 yếu tố
2. Click "Kích hoạt 2FA"
3. Quét mã QR bằng app Google Authenticator hoặc Authy
4. Nhập mã xác thực 6 số
5. Lưu mã khôi phục an toàn
6. **Happy Case:** 2FA kích hoạt thành công → Lần đăng nhập sau cần nhập mã OTP

### 2. 🎒 Quản lý Thiết bị

#### Xem danh sách thiết bị
- **Đường dẫn:** `/equipment`
- **Chức năng:** Xem, tìm kiếm, lọc thiết bị theo môn học, khối lớp

#### Thêm thiết bị mới (Chỉ Admin)
1. Vào Quản lý → Thiết bị → Thêm mới
2. Điền thông tin:
   - Tên thiết bị
   - Mã thiết bị
   - Môn học
   - Khối lớp
   - Đơn giá
   - Cấp độ bảo mật
   - **Hình ảnh:** Upload ảnh thiết bị (JPG, PNG, GIF - tối đa 2MB)
3. **Happy Case:** Lưu thành công → Thiết bị xuất hiện trong danh sách kèm hình ảnh

#### Cập nhật hình ảnh thiết bị
1. Vào chi tiết thiết bị → Click "Sửa"
2. Chọn "Chọn tập tin" ở mục Hình ảnh
3. Chọn ảnh mới (sẽ thay thế ảnh cũ)
4. **Happy Case:** Ảnh được cập nhật → Hiển thị trong danh sách và chi tiết

#### Quản lý kho
1. Vào Quản lý → Kho
2. Chọn "Nhập kho" hoặc "Xuất kho"
3. Điền số lượng và lý do
4. **Happy Case:** Cập nhật thành công → Số lượng tồn kho thay đổi → Ghi log

#### In mã QR
1. Xem chi tiết thiết bị
2. Click "In mã QR"
3. **Happy Case:** Tạo QR thành công → In nhãn → Dán lên thiết bị

### 3. 📝 Mượn trả Thiết bị

#### Tạo phiếu mượn
1. Vào Mượn trả → Tạo phiếu mượn
2. Chọn thiết bị cần mượn
3. Chọn ngày mượn và ngày trả dự kiến
4. Nhập mục đích sử dụng
5. Gửi yêu cầu
6. **Happy Case:** 
   - Thiết bị thường: Phê duyệt tự động → Nhận thiết bị
   - Thiết bị bảo mật cao: Chờ Admin duyệt → Nhận thông báo → Nhận thiết bị

#### Sử dụng mẫu mượn
1. Vào Mượn trả → Mẫu mượn
2. Chọn mẫu đã lưu
3. Điều chỉnh ngày mượn/trả
4. **Happy Case:** Tạo phiếu mượn nhanh từ mẫu → Tiết kiệm thời gian

#### Trả thiết bị
1. Admin vào Mượn trả → Danh sách mượn
2. Tìm phiếu mượn cần trả
3. Click "Xác nhận trả"
4. Kiểm tra tình trạng thiết bị
5. **Happy Case:** Cập nhật trạng thái → Thiết bị có thể mượn lại

#### Xem lịch mượn
1. Vào Mượn trả → Lịch mượn
2. Xem theo tuần/tháng
3. **Happy Case:** Thấy rõ lịch → Phát hiện xung đột → Lên kế hoạch tốt hơn

### 4. 🎯 Kế hoạch Giảng dạy

#### Tạo kế hoạch giảng dạy
1. Vào Giảng dạy → Kế hoạch → Thêm mới
2. Điền thông tin:
   - Tên bài giảng
   - Lớp
   - Ngày giờ
   - Thiết bị cần dùng
3. **Happy Case:** Lưu thành công → Nhận nhắc nhở trước giờ dạy

#### Đặt trước thiết bị
1. Vào Đặt trước → Tạo mới
2. Chọn thiết bị và ngày cần dùng
3. Gửi yêu cầu
4. **Happy Case:** Admin xác nhận → Chuyển thành phiếu mượn khi đến ngày

### 5. 👤 Quản lý Hồ sơ

#### Cập nhật thông tin cá nhân
1. Click avatar → Hồ sơ
2. Sửa thông tin cần thiết
3. **Happy Case:** Lưu thành công → Thông tin cập nhật

#### Đổi mật khẩu
1. Vào Hồ sơ → Đổi mật khẩu
2. Nhập mật khẩu cũ và mật khẩu mới
3. **Happy Case:** Đổi thành công → Đăng nhập bằng mật khẩu mới

#### Cài đặt thông báo
1. Vào Hồ sơ → Thông báo
2. Bật/tắt từng loại thông báo
3. **Happy Case:** Nhận đúng thông báo quan trọng

### 6. 📊 Báo cáo (Chỉ Admin)

#### Xuất báo cáo
1. Vào Báo cáo → Chọn loại báo cáo
2. Chọn khoảng thời gian
3. Click "Xuất Excel"
4. **Happy Case:** Tải file Excel với đầy đủ dữ liệu

#### Cấu hình báo cáo tự động
1. Vào Báo cáo → Báo cáo tự động
2. Tạo lịch gửi (hàng tuần/tháng)
3. Chọn người nhận
4. **Happy Case:** Nhận báo cáo qua email đúng lịch

### 7. 🤖 AI Trợ lý

#### Sử dụng AI Chat
1. Click icon chat ở góc phải
2. Hỏi về thiết bị, hướng dẫn sử dụng
3. **Happy Case:** AI gợi ý chính xác → Tìm được thiết bị phù hợp

### 8. 🔔 Thông báo

#### Xem thông báo
1. Click icon chuông
2. Xem danh sách thông báo
3. Click để xem chi tiết
4. **Happy Case:** Không bỏ lỡ thông tin quan trọng

### 9. 📤 Nhập/Xuất dữ liệu

#### Nhập Excel (Admin)
1. Vào Quản lý → Nhập dữ liệu
2. Tải template mẫu
3. Điền dữ liệu vào template
4. Upload file
5. **Happy Case:** Import thành công → Dữ liệu xuất hiện trong hệ thống

#### Xuất lịch iCal
1. Vào Mượn trả → Lịch → Xuất iCal
2. Copy link iCal
3. Thêm vào Google Calendar/Outlook
4. **Happy Case:** Lịch đồng bộ tự động → Nhận nhắc nhở

### 10. 🔍 Tìm kiếm

#### Tìm kiếm toàn cục
1. Sử dụng ô tìm kiếm trên thanh menu
2. Nhập từ khóa (tên thiết bị, mã, người mượn...)
3. **Happy Case:** Tìm thấy kết quả → Click để xem chi tiết

---

## 🚨 CÁC TÍNH NĂNG QUẢN TRỊ (ADMIN)

### Quản lý Phòng ban
- **Đường dẫn:** `/admin/departments`
- **Chức năng:** Thêm, sửa, xóa tổ chuyên môn

### Quản lý Kho/Phòng
- **Đường dẫn:** `/admin/rooms`
- **Chức năng:** Quản lý phòng học, phòng thí nghiệm, kho

### Lịch bảo trì
- **Đường dẫn:** `/admin/maintenance`
- **Chức năng:** Lên lịch bảo trì định kỳ cho thiết bị

### Chuyển kho
- **Đường dẫn:** `/admin/transfers`
- **Chức năng:** Chuyển thiết bị giữa các phòng/kho

### Báo cáo hư hỏng
- **Đường dẫn:** `/admin/damage-reports`
- **Chức năng:** Xử lý báo cáo thiết bị hư hỏng

### Kiểm toán
- **Đường dẫn:** `/admin/audit-reports`
- **Chức năng:** Xem lịch sử hoạt động, kiểm tra bất thường

### Phân quyền
- **Đường dẫn:** `/admin/users`
- **Chức năng:** Quản lý người dùng và phân quyền

---

## 🎯 MẸO SỬ DỤNG HIỆU QUẢ

1. **Lưu mẫu mượn** cho các thiết bị thường dùng
2. **Bật thông báo email** để không bỏ lỡ deadline trả thiết bị
3. **Sử dụng QR code** để kiểm tra nhanh thông tin thiết bị
4. **Xuất lịch iCal** để đồng bộ với lịch cá nhân
5. **Dùng AI Chat** khi cần tìm thiết bị phù hợp cho bài giảng

---

## 📞 HỖ TRỢ

- **Email hỗ trợ:** support@truong.edu.vn
- **Hotline:** 0123 456 789
- **Giờ làm việc:** Thứ 2 - Thứ 6, 7:30 - 17:00

---

## 🔄 CẬP NHẬT

- **Phiên bản:** 1.1
- **Ngày cập nhật:** 30/03/2026
- **Tính năng mới:** 
  - AI Chat, 2FA, Báo cáo tự động
  - Upload hình ảnh cho thiết bị
  - Dữ liệu 41 người dùng thực tế từ 4 tổ chuyên môn

---

*Chúc bạn sử dụng hệ thống hiệu quả! 🎉*