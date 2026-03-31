<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RealStaffSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy các tổ chuyên môn
        $departments = Department::pluck('id', 'name');

        // Danh sách nhân sự thực tế
        $staffData = [
            // KHTN
            ['stt' => 1, 'department' => 'KHTN', 'name' => 'LƯƠNG VIỆT ĐỨC', 'dob' => '2/1/1984', 'career_start' => '1/9/2006', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Hóa học', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => 'Cao cấp', 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B1', 'ethnic_lang' => 'Mông', 'position' => 'Hiệu trưởng', 'rank' => 'Hạng 2/THPT', 'phone' => '091 5962015', 'email' => 'Lvduc.gv08@tuyenquang.edu.vn', 'role' => 'admin'],
            ['stt' => 2, 'department' => 'KHTN', 'name' => 'TRỊNH XUÂN BẢO', 'dob' => '6/3/1982', 'career_start' => '1/9/2005', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Vật lí', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => 'Trung cấp', 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B1', 'ethnic_lang' => 'Mông', 'position' => 'Phó Hiệu trưởng', 'rank' => 'Hạng 2/THPT', 'phone' => '936836382', 'email' => 'txbao0382@tuyenquang.edu.vn', 'role' => 'admin'],
            ['stt' => 4, 'department' => 'KHTN', 'name' => 'NGUYỄN CHÍ THANH', 'dob' => '22/3/1983', 'career_start' => '1/9/2005', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'KTCN', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => 'Trung cấp', 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh A2', 'ethnic_lang' => 'Mông', 'position' => 'TTCM', 'rank' => 'Hạng 2/THPT', 'phone' => '0988 509 518', 'email' => 'ncthanh0383@tuyenquang.edu.vn'],
            ['stt' => 5, 'department' => 'KHTN', 'name' => 'BÙI THỊ BÍCH PHƯỢNG', 'dob' => '7/3/1986', 'career_start' => '10/2/2012', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Hóa học', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'TPCM', 'rank' => 'Hạng 3/THPT', 'phone' => '385070386', 'email' => 'btbichphuong86@gmail.com'],
            ['stt' => 6, 'department' => 'KHTN', 'name' => 'NGÔ VÂN ANH', 'dob' => '9/6/1996', 'career_start' => '2/1/2019', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Vật lí', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'TPCM', 'rank' => 'Hạng 3/THPT', 'phone' => '0349 320 868', 'email' => 'nvanh0696@tuyenquang.edu.vn'],
            ['stt' => 7, 'department' => 'KHTN', 'name' => 'HOÀNG THỊ DẦN', 'dob' => '20/12/1986', 'career_start' => '1/12/2010', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Vật lí', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'TKHĐ', 'rank' => 'Hạng 3/THPT', 'phone' => '0989 046 098', 'email' => 'htdan1286@tuyenquang.edu.vn'],
            ['stt' => 8, 'department' => 'KHTN', 'name' => 'BÙI THỊ NGỌC BÍCH', 'dob' => '3/1/1996', 'career_start' => '2/1/2019', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Hóa học', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh C', 'ethnic_lang' => null, 'position' => 'CT HLHTN', 'rank' => 'Hạng 3/THPT', 'phone' => '388500805', 'email' => 'btnbich0196@tuyenquang.edu.vn'],
            ['stt' => 9, 'department' => 'KHTN', 'name' => 'LƯƠNG VĂN QUANG', 'dob' => '12/2/1972', 'career_start' => '1/9/1996', 'gender' => 'Nam', 'ethnicity' => 'Nùng', 'specialization' => 'Hóa học', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0372 843 877', 'email' => 'lvquang0273@tuyenquang.edu.vn'],
            ['stt' => 10, 'department' => 'KHTN', 'name' => 'NGUYỄN TRÀ MY', 'dob' => '5/2/1983', 'career_start' => '1/9/2003', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Sinh học', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => 'Trung cấp', 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B1', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 2/THCS', 'phone' => '986611000', 'email' => 'ntmy0283@tuyenquang.edu.vn'],
            ['stt' => 11, 'department' => 'KHTN', 'name' => 'NGUYỄN VĂN LIÊM', 'dob' => '16/12/1980', 'career_start' => '5/1/2005', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'KTNN', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0988 796 155', 'email' => 'nvliem1280@tuyenquang.edu.vn'],
            
            // T-A-TI-TC-QP
            ['stt' => 3, 'department' => 'T-A-TI-TC-QP', 'name' => 'NGUYỄN THỊ VÂN ANH', 'dob' => '23/7/1977', 'career_start' => '13/9/1999', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Tiếng Anh', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => 'Trung cấp', 'it_cert' => 'Tin học CB', 'language_cert' => 'Cử nhân', 'ethnic_lang' => null, 'position' => 'Phó Hiệu trưởng', 'rank' => 'Hạng 2/THPT', 'phone' => '974578444', 'email' => 'ntvanh.gv08@tuyenquang.edu.vn', 'role' => 'admin'],
            ['stt' => 12, 'department' => 'T-A-TI-TC-QP', 'name' => 'VÕ VĂN VIỆT', 'dob' => '11/2/1982', 'career_start' => '1/9/2003', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Toán học', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B1', 'ethnic_lang' => 'Dao', 'position' => 'TTCM', 'rank' => 'Hạng 2/THPT', 'phone' => '912211009', 'email' => 'vvviet0282@tuyenquang.edu.vn'],
            ['stt' => 13, 'department' => 'T-A-TI-TC-QP', 'name' => 'DƯƠNG PHI ĐIỆP', 'dob' => '15/2/1978', 'career_start' => '30/8/2002', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Thể dục', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => 'Mông', 'position' => 'TPCM', 'rank' => 'Hạng 3/THPT', 'phone' => '0973 707 494', 'email' => 'dpdiep0278@tuyenquang.edu.vn'],
            ['stt' => 14, 'department' => 'T-A-TI-TC-QP', 'name' => 'NGUYỄN THỊ HỒNG HUỆ', 'dob' => '4/4/1995', 'career_start' => '10/4/2019', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Ngoại ngữ', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'IC3', 'language_cert' => 'Trung B', 'ethnic_lang' => null, 'position' => 'TPCM', 'rank' => 'Hạng 3/THPT', 'phone' => '0983 627 066', 'email' => 'nthhue0495@tuyenquang.edu.vn'],
            ['stt' => 15, 'department' => 'T-A-TI-TC-QP', 'name' => 'HOÀNG VĂN BỘ', 'dob' => '16/6/1984', 'career_start' => '10/2/2012', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Toán học', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => 'Mông', 'position' => 'TTrND', 'rank' => 'Hạng 3/THPT', 'phone' => '0384 298 514', 'email' => 'hvbo0684@tuyenquang.edu.vn'],
            ['stt' => 16, 'department' => 'T-A-TI-TC-QP', 'name' => 'HOÀNG THỊ KIM LIÊN', 'dob' => '21/4/1982', 'career_start' => '1/9/2003', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Toán học', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'CĐ Tin', 'language_cert' => 'Anh B', 'ethnic_lang' => 'Dao', 'position' => null, 'rank' => 'Hạng 2/THPT', 'phone' => '348165789', 'email' => 'htklien0482@tuyenquang.edu.vn'],
            ['stt' => 17, 'department' => 'T-A-TI-TC-QP', 'name' => 'LƯƠNG THỊ THANH NHÀN', 'dob' => '10/3/1977', 'career_start' => '6/3/1997', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Toán - Tin ứng dụng', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Đại học', 'language_cert' => 'Anh B', 'ethnic_lang' => 'Mông', 'position' => 'CTCĐ', 'rank' => 'Hạng 2/THCS', 'phone' => '0973 789 775', 'email' => 'lttnhan0377@tuyenquang.edu.vn'],
            ['stt' => 18, 'department' => 'T-A-TI-TC-QP', 'name' => 'VŨ THÁI LÂM', 'dob' => '7/10/1976', 'career_start' => '10/4/2019', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Ngoại ngữ', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Trung B', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0984 741 729', 'email' => 'vtlam1076@tuyenquang.edu.vn'],
            ['stt' => 19, 'department' => 'T-A-TI-TC-QP', 'name' => 'NGUYỄN THỊ THU HÀ', 'dob' => '14/12/1982', 'career_start' => '10/2/2012', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Tin học', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Đại học', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'TTrQS', 'rank' => 'Hạng 3/THCS', 'phone' => '0988 387 308', 'email' => 'nttha@tuyenquang.edu.vn'],
            ['stt' => 20, 'department' => 'T-A-TI-TC-QP', 'name' => 'NGUYỄN ANH TẤN', 'dob' => '10/1/1982', 'career_start' => '28/1/2008', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'Thể dục', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => 'Mông', 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0973 474 582', 'email' => 'natan0182@tuyenquang.edu.vn'],
            ['stt' => 21, 'department' => 'T-A-TI-TC-QP', 'name' => 'VÕ THỊ THU TRANG', 'dob' => '8/9/1994', 'career_start' => '25/09/2023', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'GDQP-AN', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B2', 'ethnic_lang' => null, 'position' => 'PBTĐTN', 'rank' => 'Hạng 3/THPT', 'phone' => '914535926', 'email' => 'vothutrang0809@gmail.com'],
            ['stt' => 22, 'department' => 'T-A-TI-TC-QP', 'name' => 'NGUYỄN KHÁNH LINH', 'dob' => '17/01/2000', 'career_start' => '1/8/2024', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Tiếng Anh', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh C1', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0384 953 126', 'email' => 'k.linh.nguyen171@gmail.com'],
            ['stt' => 23, 'department' => 'T-A-TI-TC-QP', 'name' => 'VƯƠNG THỊ LÝ', 'dob' => '21/03/1986', 'career_start' => '1/8/2024', 'gender' => 'Nữ', 'ethnicity' => 'Nùng', 'specialization' => 'Tin học', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Đại học', 'language_cert' => 'Anh B2', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0966 269 013', 'email' => 'vuonghoangly86@gmail.com'],
            
            // KHXH
            ['stt' => 24, 'department' => 'KHXH', 'name' => 'NGUYỄN THỊ NGỌC LAN', 'dob' => '29/8/1978', 'career_start' => '14/10/2003', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Ngữ văn', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => null, 'position' => 'TTCM', 'rank' => 'Hạng 3/THPT', 'phone' => '0344 823 119', 'email' => 'ntnlan0878@tuyenquang.edu.vn'],
            ['stt' => 25, 'department' => 'KHXH', 'name' => 'NGUYỄN THỊ DUYÊN THẮM', 'dob' => '25/6/1982', 'career_start' => '1/9/2005', 'gender' => 'Nữ', 'ethnicity' => 'Nùng', 'specialization' => 'Địa lí', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'TPCM', 'rank' => 'Hạng 3/THPT', 'phone' => '0342 688 644', 'email' => 'ntdtham0682@tuyenquang.edu.vn'],
            ['stt' => 26, 'department' => 'KHXH', 'name' => 'LÝ HẢI VÂN', 'dob' => '10/7/1987', 'career_start' => '10/2/2012', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Âm nhạc', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'BTĐTN', 'rank' => 'Hạng 3/THCS', 'phone' => '823033669', 'email' => 'lhvan0787@tuyenquang.edu.vn'],
            ['stt' => 27, 'department' => 'KHXH', 'name' => 'NGUYỄN THỊ THU THỦY', 'dob' => '17/03/1989', 'career_start' => '2/1/2019', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Ngữ văn', 'degree' => 'Thạc sĩ', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B1', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0354 714 574', 'email' => 'nttthuy0389@tuyenquang.edu.vn'],
            ['stt' => 28, 'department' => 'KHXH', 'name' => 'NGUYỄN THỊ KIM THÚY', 'dob' => '13/06/1972', 'career_start' => '25/10/1993', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Ngữ văn', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học cơ bản', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'Thủ quỹ', 'rank' => 'Hạng 3/THCS', 'phone' => '0385 729 758', 'email' => 'ntkthuy0672@tuyenquang.edu.vn'],
            ['stt' => 29, 'department' => 'KHXH', 'name' => 'NGUYỄN THỊ THÙY DUNG', 'dob' => '20/5/1995', 'career_start' => '2/1/2019', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Ngữ văn', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0978 539 571', 'email' => 'nttdung0595@tuyenquang.edu.vn'],
            ['stt' => 30, 'department' => 'KHXH', 'name' => 'VŨ THỊ THU GIANG', 'dob' => '29/10/1979', 'career_start' => '1/12/2003', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Ngữ văn', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '388985122', 'email' => 'vttgiang.gv08@tuyenquang.edu.vn'],
            ['stt' => 31, 'department' => 'KHXH', 'name' => 'ĐÀM HỒNG DẬU', 'dob' => '10/2/1981', 'career_start' => '15/11/2004', 'gender' => 'Nam', 'ethnicity' => 'Cao Lan', 'specialization' => 'Địa lí', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THCS', 'phone' => '0962 163 180', 'email' => 'dhdau0281@tuyenquang.edu.vn'],
            ['stt' => 32, 'department' => 'KHXH', 'name' => 'SÁI ĐỨC TRỌNG', 'dob' => '20/3/1981', 'career_start' => '1/9/2004', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'GDCD', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh A2', 'ethnic_lang' => 'Mông', 'position' => 'PCTCĐ', 'rank' => 'Hạng 3/THPT', 'phone' => '0369 757 353', 'email' => 'sdtrong0381@tuyenquang.edu.vn'],
            ['stt' => 33, 'department' => 'KHXH', 'name' => 'TRẦN TIẾN MẠNH', 'dob' => '18/06/2001', 'career_start' => '1/10/2023', 'gender' => 'Nam', 'ethnicity' => 'Nùng', 'specialization' => 'Lịch sử', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh A2', 'ethnic_lang' => null, 'position' => 'PBTĐTN', 'rank' => 'Hạng 3/THPT', 'phone' => '379469807', 'email' => 'manhbdbp@gmail.com'],
            ['stt' => 34, 'department' => 'KHXH', 'name' => 'TRỊNH THỊ THƯƠNG', 'dob' => '10/9/1995', 'career_start' => '2/1/2019', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Lịch sử', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '0989 995 615', 'email' => 'ttthuong0995@tuyenquang.edu.vn'],
            ['stt' => 35, 'department' => 'KHXH', 'name' => 'LƯƠNG THỊ MINH DẬU', 'dob' => '2/3/1981', 'career_start' => '5/10/2004', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Mĩ thuật', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học B', 'language_cert' => 'Anh B', 'ethnic_lang' => 'Mông', 'position' => null, 'rank' => 'Hạng 2/THCS', 'phone' => '0987 668 559', 'email' => 'ltmdau0381@tuyenquang.edu.vn'],
            ['stt' => 36, 'department' => 'KHXH', 'name' => 'NGUYỄN HOÀI NAM', 'dob' => '13/12/2002', 'career_start' => '14/10/2024', 'gender' => 'Nam', 'ethnicity' => 'Kinh', 'specialization' => 'GDCD', 'degree' => 'Đại học', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => null, 'position' => null, 'rank' => 'Hạng 3/THPT', 'phone' => '946132002', 'email' => 'nguyenhoainamtq2002@gmail.com'],
            
            // VĂN PHÒNG
            ['stt' => 37, 'department' => 'VĂN PHÒNG', 'name' => 'TRẦN THỊ THẢO', 'dob' => '9/3/1991', 'career_start' => '10/2/2012', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Thư viện', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học B', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => 'TT VP', 'rank' => 'Thư viện viên hạng IV', 'phone' => '0332 590 446', 'email' => 'ttthao0391@tuyenquang.edu.vn'],
            ['stt' => 38, 'department' => 'VĂN PHÒNG', 'name' => 'PHẠM THỊ THANH THÙY', 'dob' => '7/6/1997', 'career_start' => '1/10/2023', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Y tế', 'degree' => 'Trung cấp', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => 'Tin học CB', 'language_cert' => 'Anh A2', 'ethnic_lang' => 'Mông', 'position' => null, 'rank' => 'Y sĩ hạng IV', 'phone' => '969692066', 'email' => 'Phamthithanhthuysd@gmail.com'],
            ['stt' => 39, 'department' => 'VĂN PHÒNG', 'name' => 'NGUYỄN THỊ HÀ', 'dob' => '26/6/1990', 'career_start' => '1/10/2012', 'gender' => 'Nữ', 'ethnicity' => 'Kinh', 'specialization' => 'Kế toán', 'degree' => 'Đại học', 'is_party_member' => true, 'political_theory' => null, 'it_cert' => 'Tin học B', 'language_cert' => 'Anh B', 'ethnic_lang' => null, 'position' => null, 'rank' => 'Kế toán viên TC', 'phone' => '862801236', 'email' => 'Haktptthkx@gmail.com'],
            ['stt' => 40, 'department' => 'VĂN PHÒNG', 'name' => 'TRƯƠNG THỊ LỤA', 'dob' => '12/6/1978', 'career_start' => '1/12/2010', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Cấp dưỡng', 'degree' => 'Sơ cấp', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => null, 'position' => null, 'rank' => null, 'phone' => '0978 803 098', 'email' => 'truonglua@tuyenquang.edu.vn'],
            ['stt' => 41, 'department' => 'VĂN PHÒNG', 'name' => 'TRIỆU THỊ MẠNH', 'dob' => '9/3/1972', 'career_start' => '1/12/2010', 'gender' => 'Nữ', 'ethnicity' => 'Tày', 'specialization' => 'Cấp dưỡng', 'degree' => 'Sơ cấp', 'is_party_member' => false, 'political_theory' => null, 'it_cert' => null, 'language_cert' => null, 'ethnic_lang' => null, 'position' => null, 'rank' => null, 'phone' => '0915 537 509', 'email' => 'trieumanh@tuyenquang.edu.vn'],
        ];

        // Tạo từng người dùng
        foreach ($staffData as $staff) {
            // Parse dates
            $dob = $this->parseDate($staff['dob']);
            $careerStart = $this->parseDate($staff['career_start']);
            
            // Xác định vai trò
            $role = $staff['role'] ?? 'teacher';
            
            User::create([
                'name' => $staff['name'],
                'email' => strtolower($staff['email']),
                'password' => Hash::make('password'),
                'role' => $role,
                'department_id' => $departments[$staff['department']] ?? null,
                'email_verified_at' => now(),
                'date_of_birth' => $dob,
                'career_start_date' => $careerStart,
                'gender' => $staff['gender'],
                'ethnicity' => $staff['ethnicity'],
                'specialization' => $staff['specialization'],
                'degree' => $staff['degree'],
                'is_party_member' => $staff['is_party_member'],
                'political_theory_cert' => $staff['political_theory'] ?? null,
                'position' => $staff['position'] ?? null,
                'it_certificate' => $staff['it_cert'] ?? null,
                'language_certificate' => $staff['language_cert'] ?? null,
                'ethnic_language_cert' => $staff['ethnic_lang'] ?? null,
                'civil_servant_rank' => $staff['rank'] ?? null,
                'phone' => $staff['phone'],
                'notes' => null,
            ]);
        }

        $this->command->info('✅ Đã tạo ' . count($staffData) . ' nhân sự với thông tin chi tiết từ dữ liệu thực tế');
    }

    private function parseDate($dateStr)
    {
        if (!$dateStr) return null;
        
        // Parse date format d/m/yyyy
        $parts = explode('/', $dateStr);
        if (count($parts) === 3) {
            $day = intval($parts[0]);
            $month = intval($parts[1]);
            $year = intval($parts[2]);
            
            // Xử lý năm 2 chữ số
            if ($year < 100) {
                $year = $year > 50 ? 1900 + $year : 2000 + $year;
            }
            
            return Carbon::create($year, $month, $day);
        }
        
        return null;
    }
}