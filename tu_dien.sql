-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 17 Juillet 2015 à 06:56
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `tu_dien`
--


-- --------------------------------------------------------

--
-- Structure de la table `han_tu`
--

CREATE TABLE IF NOT EXISTS `han_tu` (
  `MAHT` int(11) NOT NULL AUTO_INCREMENT,
  `TU` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `BO` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `BO_HUAN` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `BO_AM` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `SONET` tinyint(4) NOT NULL,
  `JLPT` varchar(2) NOT NULL,
  `BO_THANH_PHAN` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `NGHIA` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `GIAI_NGHIA` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `NV_GUI` int(11) NOT NULL,
  `NGAY_CAP_NHAT` datetime DEFAULT NULL,
  `KICH_HOAT` bit(1) NOT NULL,
  PRIMARY KEY (`MAHT`),
  UNIQUE KEY `u_1` (`TU`),
  KEY `NV_GUI` (`NV_GUI`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `nguphap_vd`
--

CREATE TABLE IF NOT EXISTS `nguphap_vd` (
  `MAVD` int(11) NOT NULL AUTO_INCREMENT,
  `MANP` int(11) NOT NULL,
  `NHAT` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `VIET` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`MAVD`),
  KEY `MANP` (`MANP`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `ngu_phap`
--

CREATE TABLE IF NOT EXISTS `ngu_phap` (
  `MANP` int(11) NOT NULL AUTO_INCREMENT,
  `TIENG_NHAT` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Y_NGHIA` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `CAU_TRUC` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MIEU_TA` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `CHU_Y` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `JLPT` varchar(2) NOT NULL,
  `NV_GUI` int(11) NOT NULL,
  `NGAY_CAP_NHAT` datetime NOT NULL,
  `KICH_HOAT` bit(1) NOT NULL,
  PRIMARY KEY (`MANP`),
  UNIQUE KEY `u_1` (`TIENG_NHAT`),
  KEY `fk_nguphap_nv` (`NV_GUI`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `nhan_vien`
--

CREATE TABLE IF NOT EXISTS `nhan_vien` (
  `MANV` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã Nhân Viên',
  `HOTEN` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Họ tên nv',
  `GIOITINH` bit(1) NOT NULL COMMENT 'Giới tính. True là nam',
  `DIENTHOAI` varchar(15) NOT NULL COMMENT 'Số điện thoại',
  `DIACHI` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Địa Chỉ',
  `EMAIL` varchar(100) NOT NULL COMMENT 'địa chỉ email. Dùng để đăng nhập',
  `MATKHAU` varchar(50) NOT NULL COMMENT 'mật khẩu, dùng để đăng nhập',
  `LAADMIN` bit(1) NOT NULL COMMENT 'True là admin',
  `KHOA` bit(1) NOT NULL COMMENT 'True là khóa',
  PRIMARY KEY (`MANV`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `nhan_vien`
--

INSERT INTO `nhan_vien` (`MANV`, `HOTEN`, `GIOITINH`, `DIENTHOAI`, `DIACHI`, `EMAIL`, `MATKHAU`, `LAADMIN`, `KHOA`) VALUES
(1, 'Trần Văn Thỏa', b'1', '0168 653 9737', 'Đồng Nai', 'thoa.tranvan@uni.dntu.edu.vn', 'e10adc3949ba59abbe56e057f20f883e', b'0', b'0'),
(2, 'admin', b'1', '0123456778', '', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', b'1', b'0'),
(3, 'Nguyễn Duy Quang', b'1', '0165 411 0188', 'Đồng Nai', 'quang.nguyenduy@uni.dntu.edu.vn', 'e10adc3949ba59abbe56e057f20f883e', b'0', b'0');

-- --------------------------------------------------------

--
-- Structure de la table `tuvung_nghia`
--

CREATE TABLE IF NOT EXISTS `tuvung_nghia` (
  `MANGHIA` int(11) NOT NULL AUTO_INCREMENT,
  `MATU` int(11) NOT NULL,
  `NGHIA_TIENGVIET` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nghĩa tiếng việt, dùng khi dịch từ tiếng nhật sang tiếng việt',
  `NGHIA_TIENGNHAT` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nghĩa tiếng nhật, dùng khi dịch từ tiếng việt sang tiếng nhật',
  PRIMARY KEY (`MANGHIA`),
  KEY `MATU` (`MATU`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `tuvung_vidu_nghia`
--

CREATE TABLE IF NOT EXISTS `tuvung_vidu_nghia` (
  `MA` int(11) NOT NULL AUTO_INCREMENT,
  `MANGHIA` int(11) NOT NULL,
  `VIDU_TV` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ví dụ tiếng việt',
  `VIDU_TN` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ví dụ tiếng nhật',
  PRIMARY KEY (`MA`),
  KEY `MANGHIA` (`MANGHIA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `tu_vung`
--

CREATE TABLE IF NOT EXISTS `tu_vung` (
  `MATU` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã từ vựng',
  `TIENG_ROMANJI` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nghĩa romanji',
  `HAN_TU` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nghĩa hiragana',
  `AUDIO` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `GHI_CHU` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ghi chú',
  `NGHIA_HANTU` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nghĩa hán tự',
  `NGAY_CAP_NHAT` datetime NOT NULL COMMENT 'Thời gian cập nhật lần cuối. Dùng để đồng bộ dữ liệu trên server và client',
  `NV_THEM` int(11) NOT NULL,
  `KICH_HOAT` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`MATU`),
  KEY `NV_THEM` (`NV_THEM`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `han_tu`
--
ALTER TABLE `han_tu`
  ADD CONSTRAINT `han_tu_ibfk_1` FOREIGN KEY (`NV_GUI`) REFERENCES `nhan_vien` (`MANV`);

--
-- Contraintes pour la table `nguphap_vd`
--
ALTER TABLE `nguphap_vd`
  ADD CONSTRAINT `nguphap_vd_ibfk_1` FOREIGN KEY (`MANP`) REFERENCES `ngu_phap` (`MANP`);

--
-- Contraintes pour la table `ngu_phap`
--
ALTER TABLE `ngu_phap`
  ADD CONSTRAINT `fk_nguphap_nv` FOREIGN KEY (`NV_GUI`) REFERENCES `nhan_vien` (`MANV`);

--
-- Contraintes pour la table `tuvung_nghia`
--
ALTER TABLE `tuvung_nghia`
  ADD CONSTRAINT `tuvung_nghia_ibfk_1` FOREIGN KEY (`MATU`) REFERENCES `tu_vung` (`MATU`);

--
-- Contraintes pour la table `tuvung_vidu_nghia`
--
ALTER TABLE `tuvung_vidu_nghia`
  ADD CONSTRAINT `tuvung_vidu_nghia_ibfk_1` FOREIGN KEY (`MANGHIA`) REFERENCES `tuvung_nghia` (`MANGHIA`);

--
-- Contraintes pour la table `tu_vung`
--
ALTER TABLE `tu_vung`
  ADD CONSTRAINT `tu_vung_ibfk_1` FOREIGN KEY (`NV_THEM`) REFERENCES `nhan_vien` (`MANV`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
