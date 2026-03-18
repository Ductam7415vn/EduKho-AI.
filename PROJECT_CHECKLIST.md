# Checklist Danh Gia He Thong

Cap nhat: 2026-03-13

Quy uoc:
- `[x]` Da thay co trong code va route/logic da ton tai
- `[ ]` Can kiem thu them hoac can bo sung de san sang van hanh tot hon

## 1. Tai khoan va bao mat

- [x] Dang nhap
- [x] Dang xuat
- [x] Quen mat khau
- [x] Dat lai mat khau
- [x] Xac thuc 2 lop (2FA)
- [x] Chinh sua ho so ca nhan
- [x] Doi mat khau
- [x] Cai dat thong bao ca nhan
- [ ] Kiem thu day du luong 2FA khi dang nhap va khi vo hieu hoa
- [ ] Kiem thu gioi han thu dang nhap sai va reset password

## 2. Dashboard va tim kiem

- [x] Dashboard cho admin
- [x] Dashboard cho giao vien
- [x] Tim kiem thiet bi
- [x] Tim kiem phieu muon
- [ ] Kiem thu hieu nang tim kiem voi du lieu lon

## 3. Quan ly thiet bi

- [x] Danh sach thiet bi
- [x] Xem chi tiet thiet bi
- [x] Xem lich su thiet bi
- [x] QR code thiet bi
- [x] Trang in QR
- [x] CRUD thiet bi cho admin
- [x] CRUD phong/kho
- [x] CRUD nguoi dung
- [x] CRUD to bo mon
- [ ] Kiem thu day du file hoc lieu so neu co su dung `file_url`
- [ ] Kiem thu quyen truy cap giua admin va teacher

## 4. Muon tra thiet bi

- [x] Tao phieu muon thu cong
- [x] Kiem tra so luong kha dung truoc khi muon
- [x] Kiem tra xung dot lich muon
- [x] Tra thiet bi
- [x] Cap nhat tinh trang thiet bi khi tra
- [x] In phieu muon PDF
- [x] Xem danh sach phieu muon
- [x] Xem chi tiet phieu muon
- [x] Lich muon / calendar
- [x] Mau phieu muon
- [ ] Kiem thu luong muon nhieu thiet bi trong cung thoi diem
- [ ] Kiem thu truong hop qua han, mat, hong, bao tri sau khi tra
- [ ] Kiem thu race condition khi hai nguoi dat cung mot thiet bi

## 5. Ke hoach giang day va dat truoc

- [x] Quan ly ke hoach giang day
- [x] Dat truoc thiet bi
- [x] Huy dat truoc
- [x] Xac nhan dat truoc
- [x] Chuyen dat truoc thanh phieu muon
- [ ] Kiem thu rule chuyen doi dat truoc vao dung ngay
- [ ] Kiem thu xung dot giua dat truoc va muon truc tiep

## 6. Phe duyet va thong bao

- [x] Phe duyet phieu muon thiet bi nhay cam
- [x] Tu choi phieu muon
- [x] Phe duyet hang loat
- [x] Tu choi hang loat
- [x] Thong bao trong he thong
- [x] Gui thong bao khi cho phe duyet
- [x] Gui thong bao khi phe duyet / tu choi
- [ ] Kiem thu gui thong bao email neu co cau hinh mail that
- [ ] Kiem thu chinh xac viec tra lai trang thai thiet bi khi reject

## 7. AI tro ly

- [x] Trang chat AI
- [x] API chat AI
- [x] Goi Gemini API
- [x] Tao system prompt theo du lieu kho thuc te
- [x] Parse intent tu hoi thoai tieng Viet
- [x] Prefill form muon tu ket qua AI
- [x] Fallback ve form thu cong khi AI loi
- [x] Luu lich su chat AI
- [ ] Kiem thu voi API key Gemini that tren moi truong thuc te
- [ ] Kiem thu chat voi cac cau hoi mo ho, thieu thong tin, prompt injection
- [ ] Kiem thu do on dinh khi Gemini timeout, rate limit, tra sai JSON

## 8. Quan tri kho va van hanh

- [x] Tang kho / giam kho
- [x] Import CSV thiet bi
- [x] Bao tri thiet bi
- [x] Bao hong / xu ly hu hong
- [x] Chuyen thiet bi giua cac phong
- [x] Nhat ky hoat dong
- [ ] Kiem thu import CSV loi, file rong, file lon
- [ ] Kiem thu logic giam kho khi thiet bi dang borrowed / maintenance
- [ ] Kiem thu tinh toan ma `specific_code` khi import va tang kho nhieu lan

## 9. Bao cao va xuat du lieu

- [x] Bao cao tong quan
- [x] Bao cao danh sach thiet bi
- [x] Bao cao theo doi muon tra
- [x] Audit report ton kho
- [x] Audit report muon tra
- [x] Audit report bao tri
- [x] Audit report hoat dong
- [x] Export Excel
- [x] Export CSV
- [x] Export iCal
- [x] Bao cao dinh ky
- [ ] Kiem thu export tren du lieu lon
- [ ] Kiem thu tinh tuong thich giua SQLite test va MySQL production
- [ ] Ra soat cac cau query phu thuoc MySQL trong bao cao

## 10. API va lich tac vu

- [x] API equipment
- [x] API borrow
- [x] API AI
- [x] API user info
- [x] Lich tac vu kiem tra qua han
- [x] Lich tac vu nhac tra
- [x] Lich tac vu canh bao sap het
- [x] Lich tac vu don log AI
- [ ] Kiem thu API voi Sanctum token that
- [ ] Kiem thu cac lenh schedule khi deploy tren server

## 11. Chat luong va san sang production

- [x] App bootstrap thanh cong
- [x] Route list hoat dong
- [x] Smoke test co the chay
- [ ] Bo test hien tai con qua mong, can them feature test cho cac luong chinh
- [ ] Can test end-to-end cho muon tra, phe duyet, reservation, AI
- [ ] Can theo doi log loi va canh bao production ro rang hon
- [ ] Can on dinh moi truong local, tranh phu thuoc file iCloud dataless
- [ ] Can co checklist deploy, backup, restore, seed du lieu mau

## 12. Uu tien cao nhat nen lam tiep

- [ ] Viet test cho luong tao phieu muon
- [ ] Viet test cho luong phe duyet / tu choi phieu muon
- [ ] Viet test cho luong AI chat -> prefill -> redirect form muon
- [ ] Test thuc te bang tai khoan admin va teacher
- [ ] Test import/export voi du lieu thuc
- [ ] Ra soat query bao cao de tranh loi khac he quan tri CSDL
