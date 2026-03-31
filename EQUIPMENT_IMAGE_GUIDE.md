# HƯỚNG DẪN THÊM ẢNH CHO THIẾT BỊ

## 1. Chuẩn bị ảnh

### Đường dẫn lưu ảnh:
```
/Users/ductampro/Desktop/files/storage/app/public/equipment/
```

### Quy tắc đặt tên file ảnh:
Đặt tên file theo mã thiết bị hoặc tên mô tả. Ví dụ:
- `bo_thi_nghiem_co_hoc.jpg` cho thiết bị TBVL001
- `may_do_luc.jpg` cho thiết bị TBVL002
- `kinh_hien_vi.jpg` cho thiết bị TBHH003

### Danh sách thiết bị và tên file ảnh gợi ý:

#### Vật lý (TBVL)
| Mã thiết bị | Tên thiết bị | Tên file ảnh gợi ý |
|-------------|--------------|-------------------|
| TBVL001 | Bộ thí nghiệm cơ học | bo_thi_nghiem_co_hoc.jpg |
| TBVL002 | Máy đo lực | may_do_luc.jpg |
| TBVL003 | Cảm biến chuyển động | cam_bien_chuyen_dong.jpg |
| TBVL004 | Bộ thí nghiệm điện | bo_thi_nghiem_dien.jpg |
| TBVL005 | Dao động ký | dao_dong_ky.jpg |
| TBVL006 | Máy phát âm tần | may_phat_am_tan.jpg |
| TBVL007 | Bộ thí nghiệm quang học | bo_thi_nghiem_quang_hoc.jpg |
| TBVL008 | Máy đo điện đa năng | may_do_dien_da_nang.jpg |

#### Hóa học (TBHH)
| Mã thiết bị | Tên thiết bị | Tên file ảnh gợi ý |
|-------------|--------------|-------------------|
| TBHH001 | Bộ dụng cụ hóa chất | bo_dung_cu_hoa_chat.jpg |
| TBHH002 | Cân điện tử | can_dien_tu.jpg |
| TBHH003 | Kính hiển vi | kinh_hien_vi_hoa_hoc.jpg |
| TBHH004 | Máy đo pH | may_do_ph.jpg |
| TBHH005 | Tủ hút hóa chất | tu_hut_hoa_chat.jpg |

#### Sinh học (TBSH)
| Mã thiết bị | Tên thiết bị | Tên file ảnh gợi ý |
|-------------|--------------|-------------------|
| TBSH001 | Kính hiển vi sinh học | kinh_hien_vi_sinh_hoc.jpg |
| TBSH002 | Mô hình cơ thể người | mo_hinh_co_the_nguoi.jpg |
| TBSH003 | Mô hình ADN | mo_hinh_adn.jpg |
| TBSH004 | Bộ tiêu bản | bo_tieu_ban.jpg |

#### Toán học (TBTOAN)
| Mã thiết bị | Tên thiết bị | Tên file ảnh gợi ý |
|-------------|--------------|-------------------|
| TBTOAN001 | Bộ hình học không gian | bo_hinh_hoc_khong_gian.jpg |
| TBTOAN002 | Thước kẻ gỗ | thuoc_ke_go.jpg |
| TBTOAN003 | Compa | compa.jpg |
| TBTOAN004 | Máy tính Casio | may_tinh_casio.jpg |

#### Tin học (TBTIN)
| Mã thiết bị | Tên thiết bị | Tên file ảnh gợi ý |
|-------------|--------------|-------------------|
| TBTIN001 | Phòng máy tính | phong_may_tinh.jpg |
| TBTIN002 | Máy chiếu | may_chieu.jpg |
| TBTIN003 | Màn hình tương tác | man_hinh_tuong_tac.jpg |
| TBTIN004 | Bộ kit Arduino | bo_kit_arduino.jpg |

#### Thể dục (TBTD)
| Mã thiết bị | Tên thiết bị | Tên file ảnh gợi ý |
|-------------|--------------|-------------------|
| TBTD001 | Bóng đá | bong_da.jpg |
| TBTD002 | Bóng chuyền | bong_chuyen.jpg |
| TBTD003 | Vợt cầu lông | vot_cau_long.jpg |
| TBTD004 | Bàn bóng bàn | ban_bong_ban.jpg |

## 2. Copy ảnh vào thư mục

```bash
# Ví dụ copy một ảnh
cp /path/to/your/image.jpg /Users/ductampro/Desktop/files/storage/app/public/equipment/bo_thi_nghiem_co_hoc.jpg

# Copy nhiều ảnh cùng lúc
cp /path/to/your/images/*.jpg /Users/ductampro/Desktop/files/storage/app/public/equipment/
```

## 3. Kiểm tra storage link

```bash
# Di chuyển vào thư mục dự án
cd /Users/ductampro/Desktop/files

# Tạo symbolic link (nếu chưa có)
php artisan storage:link
```

## 4. Chạy seeder để cập nhật database

```bash
php artisan db:seed --class=EquipmentStaticImageSeeder
```

## 5. Tùy chỉnh danh sách ảnh (nếu cần)

Mở file `/database/seeders/EquipmentStaticImageSeeder.php` và sửa array `$equipmentImages` theo tên file ảnh thực tế của bạn:

```php
private $equipmentImages = [
    'TBVL001' => 'equipment/ten_file_cua_ban.jpg',
    'TBVL002' => 'equipment/ten_file_khac.jpg',
    // ...
];
```

## Lưu ý:
- Định dạng ảnh hỗ trợ: JPG, JPEG, PNG, GIF
- Kích thước khuyến nghị: 800x600 pixels
- Dung lượng tối đa: 2MB/ảnh
- Tên file không dấu, không khoảng trắng (dùng _ thay thế)