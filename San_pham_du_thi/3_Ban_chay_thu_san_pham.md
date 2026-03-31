# BẢN CHẠY THỬ SẢN PHẨM

## EDUEQUIP PRO - HỆ THỐNG QUẢN LÝ THIẾT BỊ DẠY HỌC THÔNG MINH

### I. THÔNG TIN TRUY CẬP

**Website demo:** https://demo.eduequip.edu.vn

**Tài khoản test:**

| Vai trò | Email | Mật khẩu | Quyền hạn |
|---------|-------|----------|-----------|
| Admin | admin@truong.edu.vn | password | Toàn quyền hệ thống |
| Cán bộ thiết bị | canbo@truong.edu.vn | password | Quản lý kho, phê duyệt |
| Giáo viên Vật lý | gv.vatly@truong.edu.vn | password | Mượn trả thiết bị |
| Giáo viên QPAN | gv.qpan@truong.edu.vn | password | Mượn thiết bị an ninh cao |

### II. KỊCH BẢN CHẠY THỬ

#### KỊCH BẢN 1: TÌM KIẾM THIẾT BỊ VỚI AI CHATBOT

**Mục tiêu:** Trải nghiệm tính năng AI tìm kiếm thiết bị bằng ngôn ngữ tự nhiên

**Các bước thực hiện:**

1. **Đăng nhập** với tài khoản giáo viên Vật lý
2. Click vào biểu tượng **AI Chat** góc phải màn hình
3. **Gõ câu hỏi** trong khung chat:

**Test case 1 - Tìm theo nội dung bài học:**
```
"Tôi cần thiết bị để dạy bài phản xạ toàn phần lớp 11"
```

**Kết quả mong đợi:**
```
🤖 AI: Tôi tìm thấy các thiết bị phù hợp cho bài phản xạ toàn phần:

1. 🔬 Bộ thí nghiệm phản xạ toàn phần (TBVL-203)
   - Còn lại: 2/3 bộ
   - Vị trí: Phòng TH Vật lý
   
2. 🔦 Nguồn laser đỏ (TBVL-105)  
   - Còn lại: 5/6 cái
   - Vị trí: Kho Tổng

3. 🪞 Gương bán mạ (TBVL-207)
   - Còn lại: 4/4 cái  
   - Vị trí: Phòng TH Vật lý

Bạn muốn mượn thiết bị nào?
```

**Test case 2 - Hỏi cách sử dụng:**
```
"Làm sao để sử dụng bộ thí nghiệm phản xạ toàn phần?"
```

**Kết quả mong đợi:**
```
🤖 AI: Hướng dẫn sử dụng Bộ thí nghiệm phản xạ toàn phần:

1. Lắp đặt:
   - Đặt gương bán mạ nghiêng 45°
   - Điều chỉnh nguồn laser vuông góc với gương

2. Thí nghiệm:
   - Bật nguồn laser
   - Quan sát hiện tượng phản xạ
   - Thay đổi góc tới để demo phản xạ toàn phần

📄 Xem hướng dẫn chi tiết (PDF)
🎥 Video hướng dẫn (3 phút)
```

**Test case 3 - Tìm thiết bị thay thế:**
```
"Bộ thí nghiệm phản xạ đã hết, có thiết bị nào thay thế không?"
```

#### KỊCH BẢN 2: QUY TRÌNH MƯỢN THIẾT BỊ THÔNG THƯỜNG

**Mục tiêu:** Test quy trình mượn trả tự động cho thiết bị thông thường

**Các bước:**

1. Từ kết quả chat AI, click **"Mượn ngay"** bên cạnh thiết bị
2. Hoặc vào menu **Thiết bị > Danh mục** > Chọn thiết bị
3. Click nút **"Tạo phiếu mượn"**
4. Điền thông tin:
   - Ngày mượn: Hôm nay
   - Ngày trả dự kiến: 3 ngày sau  
   - Mục đích: "Dạy bài phản xạ toàn phần lớp 11A1"
   - Tiết dạy: Tiết 3-4
5. Click **"Gửi yêu cầu"**

**Kết quả mong đợi:**
- ✅ Phiếu mượn được duyệt tự động (thiết bị thông thường)
- 📧 Nhận email xác nhận  
- 📱 Thông báo trên hệ thống
- 🎫 Mã QR phiếu mượn để xuất trình khi lấy thiết bị

#### KỊCH BẢN 3: MƯỢN THIẾT BỊ AN NINH CAO

**Mục tiêu:** Test quy trình phê duyệt đa cấp cho thiết bị nhạy cảm

**Các bước:**

1. **Đăng nhập** tài khoản giáo viên QPAN
2. Vào **Thiết bị > Danh mục > QPAN**
3. Chọn **"Súng AK-47 mô hình"** (thiết bị an ninh cao)
4. Click **"Tạo phiếu mượn"**
5. Điền thông tin chi tiết:
   - Lý do: "Dạy bài 15: Sử dụng vũ khí bộ binh"
   - Cam kết: ✓ Tick các cam kết bảo quản
6. **Gửi yêu cầu**

**Kết quả:**
- ⏳ Phiếu mượn ở trạng thái "Chờ phê duyệt"
- 📨 Admin nhận thông báo cần duyệt

**Tiếp theo - Với tài khoản Admin:**
1. Đăng nhập tài khoản Admin
2. Vào **Quản lý > Phê duyệt**
3. Xem chi tiết yêu cầu
4. Click **"Phê duyệt"** hoặc **"Từ chối"**

#### KỊCH BẢN 4: TRẢ THIẾT BỊ VÀ BÁO CÁO HƯ HỎNG

**Mục tiêu:** Test quy trình trả và xử lý thiết bị hư hỏng

**Các bước:**

1. Vào **Mượn trả > Đang mượn**
2. Chọn phiếu mượn cần trả
3. Click **"Trả thiết bị"**
4. Đánh giá tình trạng:
   - Thiết bị 1: ✅ Tốt
   - Thiết bị 2: ⚠️ Hư hỏng nhẹ
5. Với thiết bị hư hỏng:
   - Mô tả: "Đèn laser yếu, cần thay pin"
   - Upload ảnh chụp
6. **Xác nhận trả**

**Kết quả:**
- Thiết bị tốt → Sẵn sàng cho mượn tiếp
- Thiết bị hỏng → Chuyển sang "Cần bảo trì"
- 📊 Cập nhật thống kê

#### KỊCH BẢN 5: KIỂM KÊ NHANH VỚI QR CODE

**Mục tiêu:** Test tính năng kiểm kê bằng quét mã QR

**Các bước:**

1. Đăng nhập tài khoản Cán bộ thiết bị
2. Vào **Quản lý > Kiểm kê**
3. Click **"Bắt đầu kiểm kê"**
4. Chọn phòng: "Phòng TH Vật lý"
5. **Quét QR code** trên thiết bị (hoặc click vào mã demo)
6. Xác nhận tình trạng từng thiết bị
7. **Hoàn tất kiểm kê**

**Kết quả:**
- 📊 Báo cáo kiểm kê tự động
- 🚨 Cảnh báo thiết bị thiếu/thừa
- 📈 Cập nhật dashboard

#### KỊCH BẢN 6: XEM BÁO CÁO THỐNG KÊ

**Mục tiêu:** Test các loại báo cáo hệ thống

**Với tài khoản Admin:**

1. Vào **Báo cáo > Tổng quan**
   - Xem dashboard với biểu đồ
   - Thống kê theo thời gian thực

2. **Báo cáo MAU01** - Danh mục thiết bị:
   - Click "Xuất MAU01"  
   - Chọn định dạng Excel/PDF
   - Download file

3. **Báo cáo MAU02** - Theo dõi mượn trả:
   - Lọc theo tháng
   - Xem chi tiết từng giao dịch
   - Export Excel

4. **Báo cáo tùy chỉnh:**
   - Top thiết bị được mượn nhiều
   - Giáo viên mượn nhiều nhất
   - Thiết bị ít sử dụng

### III. TÍNH NĂNG ĐẶC BIỆT CẦN TEST

#### 3.1. Nhập liệu hàng loạt

1. Vào **Quản lý > Import Excel**
2. Download file mẫu
3. Upload file Excel có sẵn (100+ thiết bị)
4. Xem kết quả import

#### 3.2. Thông báo real-time

1. Mở 2 tab với 2 tài khoản khác nhau
2. Tab 1 (GV): Tạo phiếu mượn
3. Tab 2 (Admin): Xem thông báo xuất hiện ngay

#### 3.3. Chế độ Fullscreen chống gian lận

1. Vào **Kiểm tra > Bài test**
2. Click "Làm bài"
3. Hệ thống tự động fullscreen
4. Thử click chuột phải → Bị chặn

### IV. CHECKLIST ĐÁNH GIÁ

| STT | Tính năng | Hoạt động | Ghi chú |
|-----|-----------|-----------|---------|
| 1 | AI Chatbot | ☐ |  |
| 2 | Tìm kiếm thông minh | ☐ |  |
| 3 | Mượn thiết bị thường | ☐ |  |
| 4 | Mượn thiết bị an ninh | ☐ |  |
| 5 | Phê duyệt đa cấp | ☐ |  |
| 6 | Trả thiết bị | ☐ |  |
| 7 | Báo hư hỏng | ☐ |  |
| 8 | Kiểm kê QR | ☐ |  |
| 9 | Xuất báo cáo | ☐ |  |
| 10 | Import Excel | ☐ |  |
| 11 | Thông báo realtime | ☐ |  |
| 12 | Responsive mobile | ☐ |  |

### V. LƯU Ý KHI TEST

1. **Trình duyệt khuyến nghị:** Chrome, Firefox, Safari mới nhất
2. **Test trên mobile:** Quét QR code để test trên điện thoại
3. **Dữ liệu test:** Thoải mái tạo/sửa/xóa - hệ thống tự reset mỗi 24h
4. **Báo lỗi:** Gửi về support@eduequip.edu.vn

### VI. KẾT LUẬN

Bản demo này thể hiện đầy đủ các tính năng chính của EduEquip Pro. Qua việc chạy thử các kịch bản trên, người dùng có thể:

- Trải nghiệm sức mạnh của AI trong việc hỗ trợ tìm kiếm
- Hiểu rõ quy trình mượn trả được số hóa
- Thấy được tính bảo mật với thiết bị nhạy cảm
- Đánh giá hiệu quả của việc quản lý bằng QR code
- Xem xét các báo cáo hữu ích cho quản lý

Hệ thống sẵn sàng triển khai thực tế với đầy đủ tính năng đã demo.

---

**Hỗ trợ kỹ thuật:**
- Hotline: 1900-xxxx
- Email: support@eduequip.edu.vn
- Chat: Góc phải màn hình