#!/bin/bash

echo "Đẩy code lên GitHub..."
echo "====================="

cd /Users/ductampro/Desktop/files

# Thêm các file quan trọng
echo "1. Thêm các file đã thay đổi..."
git add app/
git add database/
git add resources/
git add routes/
git add bootstrap/
git add storage/app/public/equipment/
git add *.md
git add *.php
git add *.sh

# Commit
echo "2. Tạo commit..."
git commit -m "Update equipment management system with images

- Add image upload for equipment (36 items)
- Add school leader permissions
- Add 41 real staff members
- Fix UI issues and update QR code API
- Update documentation"

# Push
echo "3. Đẩy lên GitHub..."
git push origin main

echo "✅ Hoàn tất!"