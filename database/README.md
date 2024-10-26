# Database Setup

## Thông tin
- Tên database: baitaplon
- Version: MySQL 8.0
- Character Set: utf8mb4
- Collation: utf8mb4_unicode_ci

## Cấu trúc database

1. Bảng `chucvu`:
   - Quản lý thông tin chức vụ của nhân viên và khách hàng
   - Khóa chính: `chucvuID` (VARCHAR(50))
   - Thông tin tên chức vụ

2. Bảng `nhanvien`:
   - Quản lý thông tin nhân viên
   - Thông tin cá nhân: họ tên, ngày sinh, giới tính, SDT, email, địa chỉ
   - Thông tin công việc: chức vụ, lương
   - Khóa ngoại liên kết với bảng `chucvu`

3. Bảng `khachhang`:
   - Quản lý thông tin khách hàng
   - Thông tin: họ tên, SDT, email
   - Khóa ngoại liên kết với bảng `chucvu`

4. Bảng `phim`:
   - Thông tin chi tiết về phim
   - Bao gồm: mã phim, tên phim, thể loại, thời lượng, đạo diễn
   - Thông tin thêm: ngày khởi chiếu, ngày kết thúc, đánh giá, URL hình ảnh

5. Bảng `nhacungcapphim`:
   - Quản lý thông tin nhà cung cấp phim
   - Thông tin: tên NCC, địa chỉ, SDT, email
   - Khóa ngoại liên kết với bảng `phim`

6. Bảng `phongchieu`:
   - Thông tin về phòng chiếu phim
   - Bao gồm: mã phòng, tên phòng, tổng số ghế

7. Bảng `ghe`:
   - Quản lý thông tin ghế trong phòng chiếu
   - Thông tin: mã ghế, hàng ghế
   - Khóa ngoại liên kết với bảng `phongchieu`

8. Bảng `suatchieu`:
   - Quản lý lịch chiếu phim
   - Thông tin: giờ chiếu, giá vé
   - Khóa ngoại liên kết với bảng `phim` và `phongchieu`

9. Bảng `ve`:
   - Quản lý thông tin vé đã bán
   - Thông tin: mã vé, giá vé, trạng thái, ngày mua, booking ID
   - Khóa ngoại liên kết với bảng `suatchieu`, `ghe`, và `khachhang`

10. Bảng `khuyenmai`:
    - Quản lý thông tin khuyến mãi
    - Thông tin: tên khuyến mãi, phần trăm giảm giá
    - Thời gian: ngày bắt đầu, ngày kết thúc

## Quan hệ giữa các bảng
- Nhân viên và khách hàng được phân quyền thông qua bảng `chucvu`
- Mỗi phim có thể có nhiều suất chiếu
- Mỗi phòng chiếu có nhiều ghế
- Mỗi vé liên kết với một suất chiếu, một ghế và một khách hàng
- Mỗi phim được cung cấp bởi một nhà cung cấp

## Hướng dẫn import
1. Tạo database mới:
```sql
CREATE DATABASE baitaplon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import structure và data:
```bash
mysql -u [username] -p baitaplon < db.sql
```
## Backup database: 
# Để backup database:
```bash
mysqldump -u [username] -p baitaplon > baitaplon_backup.sql
```