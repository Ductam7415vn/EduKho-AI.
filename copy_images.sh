#!/bin/bash

# Script to copy images from source folders to equipment folder with proper naming

SOURCE_DIR="/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2"
TARGET_DIR="/Users/ductampro/Desktop/files/storage/app/public/equipment"

# Create target directory if not exists
mkdir -p "$TARGET_DIR"

echo "Starting image copy process..."

# 1. Vật lý
echo "Copying Vật lý images..."
# Find and copy physics equipment images
if [ -f "$SOURCE_DIR/1. Vật lí/"*"quang học"*.jpg ]; then
    cp "$SOURCE_DIR/1. Vật lí/"*"quang học"*.jpg "$TARGET_DIR/bo_thi_nghiem_quang_hoc.jpg" 2>/dev/null
fi
if [ -f "$SOURCE_DIR/1. Vật lí/"*"điện học"*.jpg ]; then
    cp "$SOURCE_DIR/1. Vật lí/"*"điện học"*.jpg "$TARGET_DIR/bo_thi_nghiem_dien_hoc.jpg" 2>/dev/null
fi
if [ -f "$SOURCE_DIR/1. Vật lí/"*"cơ học"*.jpg ]; then
    cp "$SOURCE_DIR/1. Vật lí/"*"cơ học"*.jpg "$TARGET_DIR/bo_thi_nghiem_co_hoc.jpg" 2>/dev/null
fi

# 2. Hóa học
echo "Copying Hóa học images..."
# Copy first chemistry image found for each equipment
for file in "$SOURCE_DIR/2. Hoá học"/*.jpg; do
    if [[ ! -f "$TARGET_DIR/tu_hut_hoa_chat.jpg" ]]; then
        cp "$file" "$TARGET_DIR/tu_hut_hoa_chat.jpg"
        break
    fi
done

# 3. Sinh học
echo "Copying Sinh học images..."
for file in "$SOURCE_DIR/3. Sinh học"/*.jpg; do
    filename=$(basename "$file")
    if [[ $filename == *"kính hiển vi"* ]] && [[ ! -f "$TARGET_DIR/kinh_hien_vi_2_mat.jpg" ]]; then
        cp "$file" "$TARGET_DIR/kinh_hien_vi_2_mat.jpg"
    elif [[ $filename == *"mô hình"* ]] && [[ ! -f "$TARGET_DIR/mo_hinh_co_the_nguoi.jpg" ]]; then
        cp "$file" "$TARGET_DIR/mo_hinh_co_the_nguoi.jpg"
    fi
done

# 4. Toán học
echo "Copying Toán học images..."
# Copy math equipment based on filename
for file in "$SOURCE_DIR/4. Toán"/*.jpg; do
    filename=$(basename "$file")
    
    if [[ $filename == *"hình học trực quan"* ]] && [[ ! -f "$TARGET_DIR/bo_hinh_hoc_truc_quan.jpg" ]]; then
        cp "$file" "$TARGET_DIR/bo_hinh_hoc_truc_quan.jpg"
    elif [[ $filename == *"thiết bị để vẽ"* ]] && [[ ! -f "$TARGET_DIR/compa_bang.jpg" ]]; then
        cp "$file" "$TARGET_DIR/compa_bang.jpg"
    elif [[ $filename == *"thống kê"* ]] && [[ ! -f "$TARGET_DIR/thuoc_thang_chia_vach.jpg" ]]; then
        cp "$file" "$TARGET_DIR/thuoc_thang_chia_vach.jpg"
    elif [[ $filename == *"Giác kế"* ]] && [[ ! -f "$TARGET_DIR/eke_bang.jpg" ]]; then
        cp "$file" "$TARGET_DIR/eke_bang.jpg"
    fi
done

# 5. QPAN
echo "Copying QPAN images..."
# Copy first 3 QPAN images to equipment
count=0
for file in "$SOURCE_DIR/5. QPAN"/*.jpg; do
    case $count in
        0) cp "$file" "$TARGET_DIR/sung_ak_mo_hinh.jpg" ;;
        1) cp "$file" "$TARGET_DIR/luu_dan_tap.jpg" ;;
        2) cp "$file" "$TARGET_DIR/ban_do_dia_hinh_quan_su.jpg" ;;
    esac
    ((count++))
    if [ $count -ge 3 ]; then break; fi
done

# 6. Âm nhạc
echo "Copying Âm nhạc images..."
for file in "$SOURCE_DIR/6. Âm nhạc"/*.jpg; do
    if [[ ! -f "$TARGET_DIR/dan_organ.jpg" ]]; then
        cp "$file" "$TARGET_DIR/dan_organ.jpg"
        break
    fi
done

# 7. Công nghệ
echo "Copying Công nghệ images..."
for file in "$SOURCE_DIR/7. Công nghệ"/*.jpg; do
    if [[ ! -f "$TARGET_DIR/may_khau.jpg" ]]; then
        cp "$file" "$TARGET_DIR/may_khau.jpg"
        break
    fi
done

# Create placeholder images for equipment not found in source
echo "Creating placeholder images for missing equipment..."

# List of all equipment that need images
declare -A equipment_list=(
    ["video_van_hoc_dan_gian.jpg"]="Ngữ văn - Video văn học"
    ["video_tho_van_bac_ho.jpg"]="Ngữ văn - Thơ Bác Hồ"
    ["tranh_hoc_tap_tu_giac.jpg"]="GDCD - Học tập tự giác"
    ["tranh_phong_chong_bao_luc.jpg"]="GDCD - Phòng chống bạo lực"
    ["luoc_do_dong_nam_a.jpg"]="Lịch sử - Đông Nam Á"
    ["ban_do_tu_nhien_vn.jpg"]="Địa lý - Bản đồ tự nhiên VN"
    ["ban_do_hanh_chinh_vn.jpg"]="Địa lý - Bản đồ hành chính VN"
    ["la_ban_thuc_dia.jpg"]="Địa lý - La bàn"
    ["may_phat_tan_so_am.jpg"]="Vật lý - Máy phát âm"
    ["bo_thi_nghiem_hoa_vo_co.jpg"]="Hóa học - Thí nghiệm vô cơ"
    ["can_dien_tu_phan_tich.jpg"]="Hóa học - Cân điện tử"
    ["binh_khi_nito.jpg"]="Hóa học - Bình khí"
    ["bo_tieu_ban_thuc_vat.jpg"]="Sinh học - Tiêu bản thực vật"
    ["mo_hinh_adn.jpg"]="Sinh học - Mô hình ADN"
    ["may_tinh_hoc_sinh.jpg"]="Tin học - Máy tính"
    ["may_chieu.jpg"]="Tin học - Máy chiếu"
    ["bo_kit_arduino.jpg"]="Tin học - Arduino"
    ["bong_da_so_5.jpg"]="Thể dục - Bóng đá"
    ["bong_chuyen.jpg"]="Thể dục - Bóng chuyền"
    ["luoi_cau_long.jpg"]="Thể dục - Lưới cầu lông"
    ["may_photocopy.jpg"]="Dùng chung - Photocopy"
    ["loa_keo_di_dong.jpg"]="Dùng chung - Loa kéo"
    ["may_chieu_3d.jpg"]="Dùng chung - Máy chiếu 3D"
)

# Check which images are missing and report
echo ""
echo "Image copy summary:"
echo "==================="
copied=0
missing=0

for image in "${!equipment_list[@]}"; do
    if [ -f "$TARGET_DIR/$image" ]; then
        echo "✅ Copied: $image"
        ((copied++))
    else
        echo "❌ Missing: $image - ${equipment_list[$image]}"
        ((missing++))
    fi
done

echo ""
echo "Total: $copied copied, $missing missing"
echo "Done! Images have been copied to: $TARGET_DIR"