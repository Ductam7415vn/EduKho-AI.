-- SQL cập nhật ảnh cho thiết bị

UPDATE equipment SET image = 'equipment/video_van_hoc_dan_gian.jpg' WHERE base_code = 'GD38-0002VN';
UPDATE equipment SET image = 'equipment/video_tho_van_bac_ho.jpg' WHERE base_code = 'GD38-0003VN';
UPDATE equipment SET image = 'equipment/bo_hinh_hoc_truc_quan.jpg' WHERE base_code = '07DTOHHTQ0021HA';
UPDATE equipment SET image = 'equipment/thuoc_thang_chia_vach.jpg' WHERE base_code = 'TBTO001';
UPDATE equipment SET image = 'equipment/compa_bang.jpg' WHERE base_code = 'TBTO002';
UPDATE equipment SET image = 'equipment/eke_bang.jpg' WHERE base_code = 'TBTO003';
UPDATE equipment SET image = 'equipment/tranh_hoc_tap_tu_giac.jpg' WHERE base_code = 'T-GDCD-2-09';
UPDATE equipment SET image = 'equipment/tranh_phong_chong_bao_luc.jpg' WHERE base_code = 'T-GDCD-2-18';
UPDATE equipment SET image = 'equipment/luoc_do_dong_nam_a.jpg' WHERE base_code = 'T-LS-2-12';
UPDATE equipment SET image = 'equipment/ban_do_tu_nhien_vn.jpg' WHERE base_code = 'BD-DL-VN-01';
UPDATE equipment SET image = 'equipment/ban_do_hanh_chinh_vn.jpg' WHERE base_code = 'BD-DL-VN-02';
UPDATE equipment SET image = 'equipment/la_ban_thuc_dia.jpg' WHERE base_code = 'TBDL003';
UPDATE equipment SET image = 'equipment/bo_thi_nghiem_quang_hoc.jpg' WHERE base_code = 'TBVL-QH-01';
UPDATE equipment SET image = 'equipment/bo_thi_nghiem_dien_hoc.jpg' WHERE base_code = 'TBVL-DH-01';
UPDATE equipment SET image = 'equipment/bo_thi_nghiem_co_hoc.jpg' WHERE base_code = 'TBVL-CH-01';
UPDATE equipment SET image = 'equipment/may_phat_tan_so_am.jpg' WHERE base_code = 'TBVL-AM-01';
UPDATE equipment SET image = 'equipment/bo_thi_nghiem_hoa_vo_co.jpg' WHERE base_code = 'TBHH-VC-01';
UPDATE equipment SET image = 'equipment/tu_hut_hoa_chat.jpg' WHERE base_code = 'TBHH-TH-01';
UPDATE equipment SET image = 'equipment/can_dien_tu_phan_tich.jpg' WHERE base_code = 'TBHH-CD-01';
UPDATE equipment SET image = 'equipment/binh_khi_nito.jpg' WHERE base_code = 'TBHH-KH-N2';
UPDATE equipment SET image = 'equipment/kinh_hien_vi_2_mat.jpg' WHERE base_code = 'TBSH-KHV-01';
UPDATE equipment SET image = 'equipment/bo_tieu_ban_thuc_vat.jpg' WHERE base_code = 'TBSH-TB-TV';
UPDATE equipment SET image = 'equipment/mo_hinh_adn.jpg' WHERE base_code = 'TBSH-MH-ADN';
UPDATE equipment SET image = 'equipment/mo_hinh_co_the_nguoi.jpg' WHERE base_code = 'TBSH-MH-NT';
UPDATE equipment SET image = 'equipment/may_tinh_hoc_sinh.jpg' WHERE base_code = 'TBTI-MT-HS';
UPDATE equipment SET image = 'equipment/may_chieu.jpg' WHERE base_code = 'TBTI-MC-01';
UPDATE equipment SET image = 'equipment/bo_kit_arduino.jpg' WHERE base_code = 'TBTI-ARD-01';
UPDATE equipment SET image = 'equipment/bong_da_so_5.jpg' WHERE base_code = 'TBTD-BD-05';
UPDATE equipment SET image = 'equipment/bong_chuyen.jpg' WHERE base_code = 'TBTD-BC-01';
UPDATE equipment SET image = 'equipment/luoi_cau_long.jpg' WHERE base_code = 'TBTD-CL-01';
UPDATE equipment SET image = 'equipment/sung_ak_mo_hinh.jpg' WHERE base_code = 'TBQP-AK-01';
UPDATE equipment SET image = 'equipment/luu_dan_tap.jpg' WHERE base_code = 'TBQP-LD-01';
UPDATE equipment SET image = 'equipment/ban_do_dia_hinh_quan_su.jpg' WHERE base_code = 'TBQP-BD-01';
UPDATE equipment SET image = 'equipment/may_photocopy.jpg' WHERE base_code = 'TBDC-PC-01';
UPDATE equipment SET image = 'equipment/loa_keo_di_dong.jpg' WHERE base_code = 'TBDC-LK-01';
UPDATE equipment SET image = 'equipment/may_chieu_3d.jpg' WHERE base_code = 'TBDC-3D-01';

-- Kiểm tra kết quả
SELECT COUNT(*) as total_equipment FROM equipment;
SELECT COUNT(*) as equipment_with_images FROM equipment WHERE image IS NOT NULL AND image != '';