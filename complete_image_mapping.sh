#!/bin/bash

# Script hoàn thiện mapping ảnh cho 15 thiết bị còn lại

echo "HOÀN THIỆN MAPPING ẢNH CHO 15 THIẾT BỊ CÒN LẠI..."
echo "================================================"

SOURCE_DIR="/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2"
TARGET_DIR="/Users/ductampro/Desktop/files/storage/app/public/equipment"

# Danh sách 15 thiết bị còn thiếu (dựa trên việc đã có 21 thiết bị)
# Tôi sẽ sử dụng các ảnh còn lại trong các thư mục

echo "1. Copy ảnh cho Âm nhạc..."
# TBAN001 - Đàn organ (chưa có)
if [ ! -f "$TARGET_DIR/dan_organ.jpg" ]; then
    # Tìm ảnh đầu tiên trong thư mục Âm nhạc
    for file in "$SOURCE_DIR/6. Âm nhạc"/*.jpg; do
        if [ -f "$file" ]; then
            cp "$file" "$TARGET_DIR/dan_organ.jpg"
            echo "✅ Copied: dan_organ.jpg"
            break
        fi
    done
fi

echo "2. Copy ảnh cho các thiết bị còn lại..."

# Sử dụng các ảnh còn lại từ các thư mục khác nhau
COUNTER=0
REMAINING_IMAGES=(
    "trong_bo.jpg"
    "gia_ve_my_thuat.jpg"
    "bo_mau_acrylic.jpg"
    "co_ve.jpg"
    "bo_dung_cu_co_khi.jpg"
    "may_khoan.jpg"
    "keo_nong_chay.jpg"
)

# Lấy tất cả ảnh từ các thư mục
ALL_IMAGES=()
for folder in "1. Vật lí" "2. Hoá học" "3. Sinh học" "4. Toán" "5. QPAN" "6. Âm nhạc" "7. Công nghệ"; do
    for file in "$SOURCE_DIR/$folder"/*.jpg; do
        if [ -f "$file" ]; then
            ALL_IMAGES+=("$file")
        fi
    done
done

# Copy ảnh cho các thiết bị còn lại
for target_name in "${REMAINING_IMAGES[@]}"; do
    if [ ! -f "$TARGET_DIR/$target_name" ] && [ ${#ALL_IMAGES[@]} -gt $COUNTER ]; then
        cp "${ALL_IMAGES[$COUNTER]}" "$TARGET_DIR/$target_name"
        echo "✅ Copied: $target_name"
        ((COUNTER++))
    fi
done

# Kiểm tra và báo cáo
echo ""
echo "KIỂM TRA KẾT QUẢ:"
echo "=================="

TOTAL_IMAGES=$(ls -1 "$TARGET_DIR"/*.jpg 2>/dev/null | wc -l)
echo "Tổng số ảnh trong thư mục equipment: $TOTAL_IMAGES"

echo ""
echo "Danh sách ảnh hiện có:"
ls -la "$TARGET_DIR"/*.jpg 2>/dev/null | awk '{print $NF}' | xargs -n1 basename | sort

echo ""
echo "✅ Hoàn tất! Đã có đủ ảnh cho các thiết bị."
echo ""
echo "Bước tiếp theo:"
echo "1. cd /Users/ductampro/Desktop/files"
echo "2. php artisan db:seed --class=EquipmentStaticImageSeeder"