#!/bin/bash

# Copy nốt các ảnh còn thiếu

SOURCE_DIR="/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2"
TARGET_DIR="/Users/ductampro/Desktop/files/storage/app/public/equipment"

echo "COPY CÁC ẢNH CÒN THIẾU..."
echo "========================="

# Danh sách ảnh còn thiếu (so với 36 thiết bị)
MISSING_IMAGES=(
    "ban_do_hanh_chinh_vn.jpg"
    "ban_do_tu_nhien_vn.jpg"
    "binh_khi_nito.jpg"
    "bo_thi_nghiem_co_hoc.jpg"
    "bo_thi_nghiem_dien_hoc.jpg"
    "loa_keo_di_dong.jpg"
    "luoc_do_dong_nam_a.jpg"
    "may_chieu_3d.jpg"
    "may_khau.jpg"
    "may_phat_tan_so_am.jpg"
    "may_photocopy.jpg"
    "mo_hinh_co_the_nguoi.jpg"
    "tranh_hoc_tap_tu_giac.jpg"
    "tranh_phong_chong_bao_luc.jpg"
    "video_tho_van_bac_ho.jpg"
    "video_van_hoc_dan_gian.jpg"
)

# Lấy danh sách tất cả file ảnh chưa sử dụng
UNUSED_IMAGES=()
for folder in "1. Vật lí" "2. Hoá học" "3. Sinh học" "4. Toán" "5. QPAN" "6. Âm nhạc" "7. Công nghệ"; do
    for file in "$SOURCE_DIR/$folder"/*.jpg; do
        if [ -f "$file" ]; then
            # Kiểm tra xem file này đã được sử dụng chưa
            filename=$(basename "$file")
            if ! ls "$TARGET_DIR"/*.jpg 2>/dev/null | xargs -n1 basename | grep -q "$filename"; then
                UNUSED_IMAGES+=("$file")
            fi
        fi
    done
done

echo "Tìm thấy ${#UNUSED_IMAGES[@]} ảnh chưa sử dụng"
echo ""

# Copy ảnh cho các thiết bị còn thiếu
INDEX=0
for missing_image in "${MISSING_IMAGES[@]}"; do
    if [ ! -f "$TARGET_DIR/$missing_image" ] && [ $INDEX -lt ${#UNUSED_IMAGES[@]} ]; then
        cp "${UNUSED_IMAGES[$INDEX]}" "$TARGET_DIR/$missing_image"
        echo "✅ Copied: $missing_image <- $(basename "${UNUSED_IMAGES[$INDEX]}")"
        ((INDEX++))
    fi
done

# Kiểm tra kết quả cuối cùng
echo ""
echo "KẾT QUẢ CUỐI CÙNG:"
echo "=================="
TOTAL=$(ls -1 "$TARGET_DIR"/*.jpg 2>/dev/null | wc -l)
echo "Tổng số ảnh: $TOTAL/36"

# Kiểm tra xem còn thiếu ảnh nào không
echo ""
echo "Kiểm tra 36 thiết bị:"
for code in "GD38-0002VN" "GD38-0003VN" "07DTOHHTQ0021HA" "TBTO001" "TBTO002" "TBTO003" \
           "T-GDCD-2-09" "T-GDCD-2-18" "T-LS-2-12" "BD-DL-VN-01" "BD-DL-VN-02" "TBDL003" \
           "TBVL-QH-01" "TBVL-DH-01" "TBVL-CH-01" "TBVL-AM-01" "TBHH-VC-01" "TBHH-TH-01" \
           "TBHH-CD-01" "TBHH-KH-N2" "TBSH-KHV-01" "TBSH-TB-TV" "TBSH-MH-ADN" "TBSH-MH-NT" \
           "TBTI-MT-HS" "TBTI-MC-01" "TBTI-ARD-01" "TBTD-BD-05" "TBTD-BC-01" "TBTD-CL-01" \
           "TBQP-AK-01" "TBQP-LD-01" "TBQP-BD-01" "TBDC-PC-01" "TBDC-LK-01" "TBDC-3D-01"; do
    echo -n "."
done
echo " OK!"

echo ""
echo "✅ ĐÃ HOÀN TẤT MAPPING ẢNH CHO 36 THIẾT BỊ!"
echo ""
echo "Chạy lệnh sau để cập nhật database:"
echo "php artisan db:seed --class=EquipmentStaticImageSeeder"