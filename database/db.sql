CREATE DATABASE baitaplon;
USE baitaplon;
CREATE TABLE `chucvu` (
	`chucvuID` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`tenChucVu` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`chucvuID`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `ghe` (
	`ghe_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`phong_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`hang_ghe` CHAR(1) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `Index 2` (`ghe_id`) USING BTREE,
	INDEX `Index 3` (`phong_id`) USING BTREE,
	CONSTRAINT `FK_ghe_phongchieu` FOREIGN KEY (`phong_id`) REFERENCES `phongchieu` (`phong_id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=442
;
CREATE TABLE `khachhang` (
	`KhachHangID` INT(11) NOT NULL AUTO_INCREMENT,
	`ho_ten` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`sdt` VARCHAR(15) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`mat_khau` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`chucvuID` VARCHAR(50) NULL DEFAULT '1' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`KhachHangID`) USING BTREE,
	UNIQUE INDEX `email` (`email`) USING BTREE,
	INDEX `Index 3` (`chucvuID`) USING BTREE,
	CONSTRAINT `FK_khachhang_chucvu` FOREIGN KEY (`chucvuID`) REFERENCES `chucvu` (`chucvuID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;

CREATE TABLE `khuyenmai` (
	`km_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ten_khuyen_mai` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`giam_gia` DECIMAL(5,2) NULL DEFAULT NULL,
	`ngay_bat_dau` DATE NULL DEFAULT NULL,
	`ngay_ket_thuc` DATE NULL DEFAULT NULL,
	PRIMARY KEY (`km_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `nhacungcapphim` (
	`ncc_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ten_nha_cung_cap` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`dia_chi` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`sdt` VARCHAR(15) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`phim_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`ncc_id`) USING BTREE,
	INDEX `phim_id` (`phim_id`) USING BTREE,
	CONSTRAINT `nhacungcapphim_ibfk_1` FOREIGN KEY (`phim_id`) REFERENCES `phim` (`phim_id`) ON UPDATE RESTRICT ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `nhanvien` (
	`NhanVienID` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ho_ten` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ngay_sinh` DATE NULL DEFAULT NULL,
	`gioi_tinh` ENUM('nam','nu') NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`sdt` VARCHAR(15) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`dia_chi` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`chucvuID` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`mat_khau` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`luong` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`NhanVienID`) USING BTREE,
	UNIQUE INDEX `email` (`email`) USING BTREE,
	INDEX `chucvuID` (`chucvuID`) USING BTREE,
	CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`chucvuID`) REFERENCES `chucvu` (`chucvuID`) ON UPDATE RESTRICT ON DELETE NO ACTION
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `phim` (
	`phim_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ten_phim` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`the_loai` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`thoi_luong` INT(11) NULL DEFAULT NULL,
	`dao_dien` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`ngay_khoi_chieu` DATE NULL DEFAULT NULL,
	`ngay_ket_thuc` DATE NULL DEFAULT NULL,
	`danh_gia` DECIMAL(2,1) NULL DEFAULT NULL,
	`img_url` VARCHAR(1000) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`phim_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `phongchieu` (
	`phong_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ten_phong` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`tong_ghe` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`phong_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `suatchieu` (
	`suat_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`phim_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`phong_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`gio_chieu` DATETIME NULL DEFAULT NULL,
	`gia` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`suat_id`) USING BTREE,
	INDEX `phim_id` (`phim_id`) USING BTREE,
	INDEX `Index 3` (`phong_id`) USING BTREE,
	CONSTRAINT `FK_suatchieu_phongchieu` FOREIGN KEY (`phong_id`) REFERENCES `phongchieu` (`phong_id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `suatchieu_ibfk_1` FOREIGN KEY (`phim_id`) REFERENCES `phim` (`phim_id`) ON UPDATE RESTRICT ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `ve` (
	`ve_id` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`suat_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`ghe_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`gia_ve` DECIMAL(10,2) NULL DEFAULT NULL,
	`trang_thai` ENUM('da_dat','trong','huy') NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`ngay_mua` DATETIME NULL DEFAULT NULL,
	`KhachHangID` INT(11) NOT NULL AUTO_INCREMENT,
	`booking_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`ve_id`) USING BTREE,
	INDEX `suat_id` (`suat_id`) USING BTREE,
	INDEX `Index 4` (`KhachHangID`) USING BTREE,
	INDEX `ghe_id` (`ghe_id`) USING BTREE,
	CONSTRAINT `FK_ve_ghe` FOREIGN KEY (`ghe_id`) REFERENCES `ghe` (`ghe_id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `FK_ve_khachhang` FOREIGN KEY (`KhachHangID`) REFERENCES `khachhang` (`KhachHangID`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `ve_ibfk_1` FOREIGN KEY (`suat_id`) REFERENCES `suatchieu` (`suat_id`) ON UPDATE RESTRICT ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;



