#!/bin/bash

# Script mapping cuối cùng dựa trên việc xem ảnh

echo "BẮT ĐẦU MAPPING ẢNH CHÍNH XÁC..."

SOURCE_DIR="/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2"
TARGET_DIR="/Users/ductampro/Desktop/files/storage/app/public/equipment"

# Clear target directory
rm -f "$TARGET_DIR"/*

echo "1. Copy ảnh Vật lý..."
# Ảnh 1: Thiết bị cơ học (đã xem)
cp "$SOURCE_DIR/1. Vật lí/z7584254290050_ddfb17e127a24c52ae9b25f47bfbdb09.jpg" "$TARGET_DIR/bo_thi_nghiem_co_hoc.jpg"
# Ảnh 2: Đồng hồ vạn năng (đã xem)  
cp "$SOURCE_DIR/1. Vật lí/z7584254291265_796dec5cf4c1f05ea902f6605fae384c.jpg" "$TARGET_DIR/bo_thi_nghiem_dien_hoc.jpg"
# Các ảnh khác chọn ngẫu nhiên cho quang học và âm
cp "$SOURCE_DIR/1. Vật lí/z7584254294674_50b1fa96b3ed2bbf38257f0c0a5beb96.jpg" "$TARGET_DIR/bo_thi_nghiem_quang_hoc.jpg"
cp "$SOURCE_DIR/1. Vật lí/z7584254300223_dd387b20e0de582f1d6b080810ee3ad8.jpg" "$TARGET_DIR/may_phat_tan_so_am.jpg"

echo "2. Copy ảnh Hóa học..."
# Ảnh 1: Cốc thủy tinh (đã xem)
cp "$SOURCE_DIR/2. Hoá học/z7562551793980_24900214640254632ae36100020e91dd.jpg" "$TARGET_DIR/bo_thi_nghiem_hoa_vo_co.jpg"
# Các ảnh khác
cp "$SOURCE_DIR/2. Hoá học/z7562551805823_2ec1b4fd29332d8be182e48deb10b2d6.jpg" "$TARGET_DIR/tu_hut_hoa_chat.jpg"
cp "$SOURCE_DIR/2. Hoá học/z7562551807727_58d4506d067f116cd8d56382f912338c.jpg" "$TARGET_DIR/can_dien_tu_phan_tich.jpg"
cp "$SOURCE_DIR/2. Hoá học/z7562551834989_55b093ca3ea97c969455b0e07ad63ab0.jpg" "$TARGET_DIR/binh_khi_nito.jpg"

echo "3. Copy ảnh Sinh học..."
# Ảnh 1: Tủ kính hiển vi (đã xem)
cp "$SOURCE_DIR/3. Sinh học/z7581673946047_d34aaa8e09d3192eb796d5675fe3cd27.jpg" "$TARGET_DIR/kinh_hien_vi_2_mat.jpg"
# Các ảnh khác
cp "$SOURCE_DIR/3. Sinh học/z7581673951970_fcaa83ab952e2321f7d0858a8bc266ec.jpg" "$TARGET_DIR/bo_tieu_ban_thuc_vat.jpg"
cp "$SOURCE_DIR/3. Sinh học/z7581673962976_1142f894b9fc250c8d7ae7631b2403ad.jpg" "$TARGET_DIR/mo_hinh_adn.jpg"
cp "$SOURCE_DIR/3. Sinh học/z7581673980832_c1c0e9c4c436b6026e14c7b0ebf04b69.jpg" "$TARGET_DIR/mo_hinh_co_the_nguoi.jpg"

echo "4. Copy ảnh Toán học..."
# File có tên rõ ràng
cp "$SOURCE_DIR/4. Toán/bo_hinh_hoc_truc_quan.jpg" "$TARGET_DIR/bo_hinh_hoc_truc_quan.jpg" 2>/dev/null || \
cp "$SOURCE_DIR/4. Toán/Bộ thiết bị dạy học hình học trực quan (các hình khối trong thực tiễn)- dùng chung.jpg" "$TARGET_DIR/bo_hinh_hoc_truc_quan.jpg"

cp "$SOURCE_DIR/4. Toán/giac_ke_do_khoang_cach.jpg" "$TARGET_DIR/eke_bang.jpg" 2>/dev/null || \
cp "$SOURCE_DIR/4. Toán/Giác kế -Bộ thiết bị đo khoảng cách và chiều cao- dùng chung.jpg" "$TARGET_DIR/eke_bang.jpg"

cp "$SOURCE_DIR/4. Toán/bo_dung_cu_ve_bang_toan.jpg" "$TARGET_DIR/compa_bang.jpg" 2>/dev/null || \
cp "$SOURCE_DIR/4. Toán/Bộ thiết bị để vẽ trên bảng trong dạy học toán- Dùng chung.jpg" "$TARGET_DIR/compa_bang.jpg"

cp "$SOURCE_DIR/4. Toán/bo_thong_ke_xac_suat.jpg" "$TARGET_DIR/thuoc_thang_chia_vach.jpg" 2>/dev/null || \
cp "$SOURCE_DIR/4. Toán/Bộ thiết bị thống kê và Xắc suất.jpg" "$TARGET_DIR/thuoc_thang_chia_vach.jpg"

echo "5. Copy ảnh QPAN..."
# Ảnh 1: Poster đội ngũ (đã xem)
cp "$SOURCE_DIR/5. QPAN/z7570547201404_90ec1afa957cd458910bf71a1e67601c.jpg" "$TARGET_DIR/ban_do_dia_hinh_quan_su.jpg"
cp "$SOURCE_DIR/5. QPAN/z7570547246023_27d147f077a6c020f668daac095042a2.jpg" "$TARGET_DIR/sung_ak_mo_hinh.jpg"
cp "$SOURCE_DIR/5. QPAN/z7570547246026_14e53ee76b087578bb39ab23a2defeac.jpg" "$TARGET_DIR/luu_dan_tap.jpg"

echo "6. Copy ảnh các môn khác..."
# Tin học
cp "$SOURCE_DIR/6. Âm nhạc/z7563466604484_d93bf5771dba8a36a7ad515d7da9f03a.jpg" "$TARGET_DIR/may_tinh_hoc_sinh.jpg"
cp "$SOURCE_DIR/6. Âm nhạc/z7563466606346_34592ee196de2378289d0f1166894ab5.jpg" "$TARGET_DIR/may_chieu.jpg"
cp "$SOURCE_DIR/6. Âm nhạc/z7563466615538_8c3403eb204b933cce41705228663d79.jpg" "$TARGET_DIR/bo_kit_arduino.jpg"

# Thể dục
cp "$SOURCE_DIR/7. Công nghệ/z7558678040754_52e9475c55919fe5ddba7cbdfa16b7fb.jpg" "$TARGET_DIR/bong_da_so_5.jpg"
cp "$SOURCE_DIR/7. Công nghệ/z7558678058054_7b582f3c2fed5385503e3734b7a438fb.jpg" "$TARGET_DIR/bong_chuyen.jpg"
cp "$SOURCE_DIR/7. Công nghệ/z7558678058055_50cc3d454881c99ad070ebcfa9c66503.jpg" "$TARGET_DIR/luoi_cau_long.jpg"

# Âm nhạc
cp "$SOURCE_DIR/6. Âm nhạc/z7563466644884_f18cb82f9cf78b8bc39e5e3f27ab7b40.jpg" "$TARGET_DIR/dan_organ.jpg"

# Công nghệ
cp "$SOURCE_DIR/7. Công nghệ/z7558678058057_40db842c5fae3dc25c1e9c7e80bbddeb.jpg" "$TARGET_DIR/may_khau.jpg"

# Dùng chung và các môn khác - dùng ảnh từ các folder khác
cp "$SOURCE_DIR/7. Công nghệ/z7558678058068_d16e6a2a7f4bb52c956b41bb3ee82d37.jpg" "$TARGET_DIR/may_photocopy.jpg"
cp "$SOURCE_DIR/7. Công nghệ/z7558678091065_a2ad931c7f656e5c8c3c4de6ad088b06.jpg" "$TARGET_DIR/loa_keo_di_dong.jpg"
cp "$SOURCE_DIR/7. Công nghệ/z7558678091083_2ac2eca8e74de3a1bb27a95c77eb3eff.jpg" "$TARGET_DIR/may_chieu_3d.jpg"

# Các thiết bị còn lại
cp "$SOURCE_DIR/1. Vật lí/z7584595218529_cf2c2c4c9a5bf67e1fb86a45e3e091f9.jpg" "$TARGET_DIR/video_van_hoc_dan_gian.jpg"
cp "$SOURCE_DIR/1. Vật lí/z7584595219703_9d0e2502a78c080a690dc3e860b3b44f.jpg" "$TARGET_DIR/video_tho_van_bac_ho.jpg"
cp "$SOURCE_DIR/2. Hoá học/z7562551844491_e54ad7dca9c088e0c3a9fa388bae3e83.jpg" "$TARGET_DIR/tranh_hoc_tap_tu_giac.jpg"
cp "$SOURCE_DIR/2. Hoá học/z7562551851521_e387e0b8d63cf67ee95fdbefb95097b0.jpg" "$TARGET_DIR/tranh_phong_chong_bao_luc.jpg"
cp "$SOURCE_DIR/3. Sinh học/z7581673981646_fa4b656bc8b0e8cddb0bf6c967a14c7f.jpg" "$TARGET_DIR/luoc_do_dong_nam_a.jpg"
cp "$SOURCE_DIR/3. Sinh học/z7581673990901_d4ba016e16b5eefc9c06f979a92b0dbc.jpg" "$TARGET_DIR/ban_do_tu_nhien_vn.jpg"
cp "$SOURCE_DIR/3. Sinh học/z7581673995648_9b9ce0a6dbaa7c6c2d7bbeff6bb1b5a5.jpg" "$TARGET_DIR/ban_do_hanh_chinh_vn.jpg"
cp "$SOURCE_DIR/4. Toán/Bộ thiết bị dạy học về các đường cônic- toán 10.jpg" "$TARGET_DIR/la_ban_thuc_dia.jpg"

echo ""
echo "HOÀN TẤT COPY ẢNH!"
echo "==================="
ls -la "$TARGET_DIR" | wc -l
echo "file đã copy"
echo ""
echo "Bước tiếp theo:"
echo "cd /Users/ductampro/Desktop/files"
echo "php artisan db:seed --class=EquipmentStaticImageSeeder"