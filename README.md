# Quản Lý Rạp Chiếu Phim

## Mô tả
Hệ thống quản lý rạp chiếu phim với các chức năng quản lý phim, lịch chiếu, phòng chiếu và bán vé. Phần mềm giúp tự động hóa quy trình quản lý và vận hành rạp chiếu phim một cách hiệu quả.

## Tính năng
- Quản lý phim (thêm, sửa, xóa thông tin phim)
- Quản lý lịch chiếu (sắp xếp lịch chiếu theo phòng và thời gian)
- Quản lý phòng chiếu (thông tin phòng, số ghế, trạng thái)
- Bán vé (đặt vé, chọn ghế, thanh toán)
- Quản lý nhân viên
- Báo cáo thống kê
- Quản lý khách hàng thành viên

## Công nghệ sử dụng
- PHP 8.0
- MySQL 8.0
- HTML5, CSS3, JavaScript
- Bootstrap 5
- jQuery

## Yêu cầu hệ thống
- PHP >= 8.0
- MySQL >= 8.0
- Apache/Nginx
- Web browser hiện đại (Chrome, Firefox, Safari, Edge)

## Cài đặt

1. Clone dự án:
```bash
git clone https://github.com/minhvuongle2004/quanlyrapchieuphim.git
cd quanlyrapchieuphim
``` 
2. Import database:
- Tạo database mới với tên 'baitaplon'
- Import file SQL từ thư mục database/baitaplon.sql
```bash
mysql -u root -p baitaplon < database/db.sql
```

3. Cấu hình môi trường:
- Copy file .env.example thành file .env
```bash
cp .env.example .env
```
- Cập nhật thông tin kết nối database trong file .env

4. Cấp quyền thư mục (với Linux/Mac):
```bash
chmod -R 777 storage/
```
## Hướng dẫn sử dụng

1. Đăng nhập hệ thống:

- Tài khoản admin mặc định: vuong@gmail.com
- Mật khẩu: 123
2. Các chức năng chính:

- Menu Quản lý phim: Thêm/sửa/xóa thông tin phim
- Menu Lịch chiếu: Quản lý suất chiếu
- Menu Phòng chiếu: Cập nhật thông tin phòng
- Menu Bán vé: Thực hiện giao dịch bán vé
- Menu Báo cáo: Xem thống kê doanh thu



## Tác giả

- Lê Minh Vương
- Email: [vuong8aqhqlna@gmail.com]
- GitHub: [vuongleminh2004]