# TÀI LIỆU ĐẶC TẢ KỸ THUẬT HỆ THỐNG
# QUẢN LÝ THIẾT BỊ DẠY HỌC CÓ ỨNG DỤNG AI
## Dự thi "Sáng tạo với AI trong giáo dục 2025-2026"

---

## Mục lục

1. [Tổng quan hệ thống](#1-tổng-quan-hệ-thống)
2. [Tech Stack](#2-tech-stack)
3. [Kiến trúc Database (11 bảng - Đã hoàn chỉnh)](#3-kiến-trúc-database)
4. [Đặc tả 4 Module chức năng](#4-đặc-tả-4-module-chức-năng)
5. [Thiết kế AI Integration](#5-thiết-kế-ai-integration)
6. [Chiến lược bảo mật](#6-chiến-lược-bảo-mật)
7. [Kế hoạch triển khai 5 tuần](#7-kế-hoạch-triển-khai-5-tuần)
8. [Changelog - Các thay đổi so với bản gốc](#8-changelog)

---

## 1. Tổng quan hệ thống

### 1.1 Bối cảnh
Hệ thống giải quyết "nỗi đau" thực tế tại các trường THPT: việc quản lý hàng ngàn thiết bị dạy học vẫn đang được thực hiện bằng sổ tay giấy, dẫn đến thất thoát, trùng lặp khi cho mượn, và tốn hàng chục giờ để tổng hợp báo cáo cuối kỳ.

### 1.2 Đối tượng sử dụng
| Vai trò | Chức năng chính |
|---------|----------------|
| **Admin** (Phó BGH / Cán bộ thiết bị) | Quản lý kho, phê duyệt mượn high_security, xuất báo cáo |
| **Teacher** (Giáo viên bộ môn) | Đặt lịch mượn (form hoặc AI), xem lịch, trả thiết bị |

### 1.3 Căn cứ pháp lý
- Thông tư 14/2020/TT-BGDĐT: Quy định về quản lý CSVC trường học
- Thông tư 37, 38, 39/2021: Danh mục thiết bị theo khối lớp 10, 11, 12
- Hướng dẫn 590 Quân khu 2: Quản lý thiết bị QPAN đặc thù

---

## 2. Tech Stack

### 2.1 Bảng tổng hợp

| Thành phần | Công nghệ | Lý do chọn |
|------------|-----------|-------------|
| **Backend** | PHP 8.2+ / Laravel 11 | RAD framework, Auth/ORM/Queue tích hợp sẵn |
| **Frontend** | Blade + Tailwind CSS + Alpine.js | TALL Stack, không cần build SPA riêng |
| **Database** | MySQL 8.0 (hoặc PostgreSQL 16) | RDBMS với ràng buộc FK chặt chẽ |
| **AI** | Google Gemini 1.5 Flash API | Free tier hào phóng, NLP tiếng Việt tốt |
| **Deployment** | Docker + Docker Compose + Nginx | Đóng gói 1 lệnh, triển khai hàng loạt |
| **Server** | VPS Ubuntu 22.04 | ~100-150k/tháng |

### 2.2 Kiến trúc code
- **Repository Pattern**: Tách logic nghiệp vụ khỏi data access
- **Service Layer**: `GeminiService` xử lý AI, `BookingService` xử lý mượn trả
- **Interface Pattern**: `LlmServiceInterface` cho phép swap AI provider

### 2.3 Đã loại bỏ (so với bản gốc)
- ~~Go/Golang microservice~~: Không cần thiết cho quy mô trường học. Laravel Queue + Jobs đủ xử lý async. Ghi nhận trong "Định hướng phát triển tương lai" nếu cần.

---

## 3. Kiến trúc Database

### 3.1 Sơ đồ quan hệ (11 bảng)

```
departments ──1:N──> users ──1:N──> teaching_plans
                       │                   │
                       │              (nullable FK)
                       │                   │
                       ├──1:N──> borrow_records <──1:1── ai_chat_logs
                       │              │
                       │         1:N  │
                       │              v
                       │       borrow_details
                       │              │
                       │         N:1  │
                       │              v
rooms ──1:N──> equipment_items <──N:1── equipments ──1:N──> inventory_logs
                                            │
                                       1:N  │
                                            v
                                     teaching_plans

+ notifications (Laravel polymorphic)
```

### 3.2 Chi tiết 11 bảng (ĐÃ SỬA & BỔ SUNG)

#### Nhóm 1: Nhân sự & Cơ sở vật chất

**Bảng `departments`** — Tổ chuyên môn
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| name | String | Tên tổ (Tổ Toán-Tin, Tổ Tự nhiên...) |
| description | String (nullable) | Mô tả |
| timestamps | | |

**Bảng `users`** — Tài khoản người dùng
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| department_id | FK → departments (nullable) | Nullable cho BGH |
| name | String | Họ tên |
| email | String (unique) | Email đăng nhập |
| phone | String (nullable) | SĐT liên hệ |
| password | String | Hashed password |
| role | Enum: admin, teacher | |
| is_active | Boolean | Tắt khi GV chuyển trường |
| timestamps | | |

**Bảng `rooms`** — Phòng học / Kho (Theo TT 14/2020)
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| name | String | Tên phòng |
| manager_id | FK → users (nullable) | Người quản lý |
| type | Enum: warehouse, lab | Kho chứa / Phòng TH |
| location | String (nullable) | Vị trí (Tầng, Dãy) |
| capacity | Integer (nullable) | Sức chứa |
| timestamps | | |

#### Nhóm 2: Danh mục & Kho tàng

**Bảng `equipments`** — Danh mục chung (Mẫu 01)
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| name | String | Tên thiết bị |
| base_code | String (unique) | Mã hiệu gốc (TBVL430) |
| unit | String | Đơn vị tính |
| price | Decimal(15,0) | Đơn giá VNĐ |
| origin | String (nullable) | Nước sản xuất |
| category_subject | String | Môn học |
| grade_level | String | Khối: "10", "11,12", "All" |
| is_digital | Boolean | true = Học liệu số |
| security_level | Enum: normal, high_security | Theo HD 590 |
| is_fixed_asset | Boolean | Gắn cố định tại phòng |
| **file_url** | String (nullable) | **MỚI** - Link tải (học liệu số) |
| **file_type** | String (nullable) | **MỚI** - Loại file (mp4, pdf...) |
| **file_size** | String (nullable) | **MỚI** - Dung lượng |
| description | Text (nullable) | Mô tả / Thông số KT |
| tags | Text (nullable) | Tags tìm kiếm (phục vụ AI) |
| timestamps | | |

**Bảng `equipment_items`** — Cá thể vật lý
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| equipment_id | FK → equipments | Thuộc danh mục nào |
| room_id | FK → rooms | Đang ở phòng nào |
| specific_code | String (unique) | Mã cá biệt (TBVL430.1/3) |
| status | Enum: available, borrowed, maintenance, broken, lost | |
| **year_acquired** | Year (nullable) | **MỚI** - Năm nhập kho (Mẫu 01) |
| notes | Text (nullable) | Ghi chú |
| timestamps | | |

**Bảng `inventory_logs`** — Sổ Tăng/Giảm (Mẫu 01)
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| equipment_id | FK → equipments | |
| **performed_by** | FK → users (nullable) | **MỚI** - Ai thực hiện |
| type | Enum: increase, decrease | Tăng/Giảm |
| quantity | Integer | Số lượng |
| reason | Text | Lý do |
| document_ref | String (nullable) | Số chứng từ |
| action_date | Date | Ngày thực hiện |
| timestamps | | |

#### Nhóm 3: Nghiệp vụ Mượn trả

**Bảng `teaching_plans`** — Kế hoạch SD thiết bị (Mẫu 03)
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| user_id | FK → users | Giáo viên đăng ký |
| equipment_id | FK → equipments | Thiết bị dự kiến |
| subject | String | Môn dạy |
| lesson_name | String | Tên bài dạy |
| period | Integer | Tiết PPCT |
| week | Integer | Tuần học |
| **planned_date** | Date | **MỚI** - Ngày cụ thể (cho Calendar) |
| **quantity_needed** | Integer | **MỚI** - Số lượng cần |
| notes | Text (nullable) | |
| timestamps | | |

**Bảng `borrow_records`** — Phiếu mượn (Mẫu 02)
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| user_id | FK → users | Giáo viên mượn |
| **teaching_plan_id** | FK → teaching_plans (nullable) | **MỚI** - Liên kết kế hoạch |
| lesson_name | String | Tên bài dạy |
| period | Integer | Tiết PPCT |
| class_name | String | Lớp dạy (10A1) |
| subject | String (nullable) | Môn dạy |
| borrow_date | DateTime | Ngày giờ mượn |
| expected_return_date | DateTime | Dự kiến trả |
| **actual_return_date** | DateTime (nullable) | **MỚI** - Trả thực tế |
| approval_status | Enum: auto_approved, pending, approved, rejected | |
| **approved_by** | FK → users (nullable) | **MỚI** - Ai duyệt |
| **approved_at** | DateTime (nullable) | **MỚI** - Duyệt lúc nào |
| **rejection_reason** | Text (nullable) | **MỚI** - Lý do từ chối |
| status | Enum: active, returned, overdue | |
| notes | Text (nullable) | |
| timestamps | | |

**Bảng `borrow_details`** — Chi tiết phiếu mượn
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| borrow_record_id | FK → borrow_records | |
| equipment_item_id | FK → equipment_items | Cá thể cụ thể |
| condition_before | String | Tình trạng lúc nhận |
| condition_after | String (nullable) | Tình trạng lúc trả |
| **damage_notes** | Text (nullable) | **MỚI** - Ghi chú hư hỏng |
| timestamps | | |
| **UNIQUE** | (borrow_record_id, equipment_item_id) | Tránh trùng lặp |

#### Nhóm 4: AI & Hệ thống (MỚI HOÀN TOÀN)

**Bảng `ai_chat_logs`** — Log tương tác AI (**BẢNG MỚI**)
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | PK | |
| user_id | FK → users | GV đã chat |
| user_message | Text | Câu nhập |
| ai_response | Text | Phản hồi thô (JSON) |
| parsed_result | Text (nullable) | Kết quả parse |
| status | Enum: success, fallback, error, rejected | |
| borrow_record_id | FK → borrow_records (nullable) | Phiếu mượn tạo ra |
| response_time_ms | Integer (nullable) | Thời gian phản hồi |
| timestamps | | |

**Bảng `notifications`** — Thông báo (**BẢNG MỚI**)
- Sử dụng cấu trúc Notification mặc định của Laravel
- Phục vụ thông báo phê duyệt cho Admin khi có phiếu mượn high_security

---

## 4. Đặc tả 4 Module chức năng

### Module 1: Quản trị Cốt lõi & Kho tàng (Admin)
- CRUD thiết bị (equipments + equipment_items)
- Quản lý phòng/kho (rooms)
- Import Excel hàng loạt (thư viện `maatwebsite/excel`)
- Phân loại an ninh (high_security → cần phê duyệt BGH)
- Quản lý Tăng/Giảm kho (inventory_logs)

### Module 2: Nghiệp vụ Mượn trả (Teacher)
- Calendar Booking (lịch tuần, click chọn tiết trống)
- Form mượn thủ công (LUÔN SẴN SÀNG, là fallback của AI)
- Đồng bộ kế hoạch giảng dạy (teaching_plans → borrow_records)
- Conflict Handling: tự động khóa thiết bị/phòng đã đặt trước
- Trả đồ + ghi nhận tình trạng (condition_before/after)

### Module 3: Trợ lý AI (Điểm nhấn sáng tạo)
- NLP Booking: Chat tiếng Việt → tạo phiếu mượn
- Smart Fallback: Gợi ý thiết bị thay thế khi hết hàng
- Anti-hallucination: Validate output AI với DB thực
- Fallback Logic: AI lỗi → tự chuyển form thủ công
- Prompt Injection Protection: System Prompt chặn mọi câu ngoài nghiệp vụ

### Module 4: Thống kê & Báo cáo (Admin)
- Xuất Sổ Thiết bị (Mẫu 01) → Excel
- Xuất Sổ Theo dõi sử dụng (Mẫu 02) → Excel
- Dashboard: biểu đồ thiết bị đang mượn, tỷ lệ hỏng, phiếu chờ duyệt

---

## 5. Thiết kế AI Integration

### 5.1 Luồng xử lý NLP Booking

```
GV nhập chat ──> GeminiService
                    │
                    ├── 1. Lấy dữ liệu kho từ DB
                    ├── 2. Tạo System Prompt (kèm danh sách kho)
                    ├── 3. Gọi Gemini API (temperature: 0.1)
                    ├── 4. Parse JSON response
                    ├── 5. Validate với DB (chống hallucination)
                    │       │
                    │       ├── equipment_id tồn tại? ✓ → Tiếp
                    │       └── equipment_id không tồn tại? → Tìm bằng tên → Fallback
                    │
                    ├── 6. Log vào ai_chat_logs
                    └── 7. Trả kết quả
                            │
                    ┌───────┴───────┐
                    ▼               ▼
              create_booking   need_more_info / suggest_alternative
              (Điền sẵn form)  (Hiện câu hỏi / gợi ý)
```

### 5.2 Fallback Chain (Thứ tự ưu tiên)

```
AI trả kết quả đúng ──> Điền sẵn form → GV xác nhận → Tạo phiếu
        │ (lỗi)
        v
AI trả JSON sai ──> Hiện thông báo "thử lại" + giữ form thủ công
        │ (lỗi)
        v
API timeout/lỗi mạng ──> Tự động chuyển sang form thủ công 100%
        │ (lỗi)
        v
Gemini bị rate limit ──> Queue retry (3 lần) → Nếu vẫn lỗi → Form thủ công
```

### 5.3 Cấu hình Gemini (config/services.php)

```php
'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
    'timeout' => env('GEMINI_TIMEOUT', 15),
],
```

---

## 6. Chiến lược bảo mật

### 6.1 Web Security (Laravel mặc định)
- CSRF Protection trên mọi form
- XSS: Blade auto-escape `{{ }}`
- SQL Injection: Eloquent ORM parameterized queries
- Password: bcrypt hash

### 6.2 AI Security
- System Prompt không được tiết lộ cho user
- Prompt Injection detection: từ khóa blacklist
- AI output LUÔN được validate với DB trước khi thực thi
- Temperature 0.1: giảm tối đa sáng tạo/hallucination
- responseMimeType: 'application/json': bắt buộc trả JSON

### 6.3 Phân quyền
- Middleware `role:admin` cho routes quản trị
- Middleware `role:teacher` cho routes mượn trả
- Policy: GV chỉ xem/sửa phiếu mượn của mình

---

## 7. Kế hoạch triển khai 5 tuần

### Tuần 1 (24/02 - 02/03): Nền tảng
- [ ] Setup Laravel + Docker Compose (PHP, Nginx, MySQL)
- [ ] Chạy 11 migration files
- [ ] Tạo Seeder với dữ liệu mẫu
- [ ] Import Excel (maatwebsite/excel)
- [ ] Auth: Login/Register, phân quyền admin/teacher

### Tuần 2 (03/03 - 09/03): Core CRUD
- [ ] CRUD thiết bị (equipments + equipment_items)
- [ ] CRUD phòng/kho (rooms)
- [ ] CRUD tổ chuyên môn (departments)
- [ ] Quản lý Tăng/Giảm kho (inventory_logs)
- [ ] Giao diện Admin Dashboard (layout Tailwind)

### Tuần 3 (10/03 - 16/03): Mượn trả + Calendar
- [ ] Form mượn thủ công (borrow_records + borrow_details)
- [ ] Calendar Booking (Alpine.js interactive)
- [ ] Conflict Handling (scope `conflictsWith`)
- [ ] Approval Workflow cho high_security
- [ ] Kế hoạch giảng dạy (teaching_plans)

### Tuần 4 (17/03 - 23/03): AI + Báo cáo
- [ ] Tích hợp Gemini API (GeminiService)
- [ ] System Prompt + NLP Booking UI (chat interface)
- [ ] Smart Fallback + Fallback Logic
- [ ] Xuất Excel Mẫu 01, 02
- [ ] Dashboard biểu đồ (Chart.js)

### Tuần 5 (24/03 - 31/03): Polish & Nộp bài
- [ ] UAT Testing (3-5 người dùng thử)
- [ ] Fix bug, tối ưu mobile responsive
- [ ] Viết Bản mô tả sản phẩm (10 trang A4)
- [ ] Quay Video demo (< 10 phút)
- [ ] Deploy lên VPS production
- [ ] Nộp hồ sơ dự thi

---

## 8. Changelog - Các thay đổi so với bản gốc

### Bổ sung mới (không có trong 4 tài liệu gốc)

| # | Thay đổi | Lý do |
|---|----------|-------|
| 1 | Thêm `teaching_plan_id` vào `borrow_records` | Liên kết phiếu mượn ↔ kế hoạch giảng dạy |
| 2 | Thêm `approved_by`, `approved_at`, `rejection_reason` vào `borrow_records` | Audit trail cho phê duyệt BGH |
| 3 | Thêm `planned_date` vào `teaching_plans` | Calendar Booking cần ngày cụ thể, không chỉ tuần |
| 4 | Thêm `year_acquired` vào `equipment_items` | Mẫu 01 nhà nước yêu cầu năm nhập kho |
| 5 | Thêm `file_url`, `file_type`, `file_size` vào `equipments` | Metadata cho học liệu số |
| 6 | Thêm bảng `ai_chat_logs` (hoàn toàn mới) | Log/debug AI, audit bảo mật |
| 7 | Thêm bảng `notifications` (hoàn toàn mới) | Thông báo phê duyệt cho Admin |
| 8 | Thêm `performed_by` vào `inventory_logs` | Truy vết ai đã thao tác Tăng/Giảm kho |
| 9 | Thêm `quantity_needed` vào `teaching_plans` | Biết cần mượn bao nhiêu thiết bị |
| 10 | Thêm `damage_notes` vào `borrow_details` | Ghi nhận chi tiết hư hỏng |
| 11 | Thêm `actual_return_date` vào `borrow_records` | Phân biệt ngày dự kiến vs ngày trả thực tế |
| 12 | Thêm `subject` vào `borrow_records` | Truy vấn báo cáo theo môn học |
| 13 | Thêm performance indexes (migration 11) | Tối ưu truy vấn Calendar, Conflict, tìm kiếm |

### Loại bỏ
| # | Loại bỏ | Lý do |
|---|---------|-------|
| 1 | Go/Golang microservice | Không cần cho quy mô trường học, Laravel Queue đủ |

---

*Tài liệu được tổng hợp và hoàn chỉnh ngày 23/02/2026*
*Phiên bản: 2.0 (đã sửa lỗi schema + bổ sung AI Service)*
