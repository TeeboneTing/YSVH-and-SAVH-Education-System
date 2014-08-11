-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-08-08: 15:09:53
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `edudbs`
--
CREATE DATABASE IF NOT EXISTS `edudbs` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `edudbs`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `EPID` varchar(10) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`EPID`, `level`) VALUES
('A123456789', 5),


-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TOPIC` text NOT NULL,
  `TOPIC_TYPE` int(11) NOT NULL,
  `GROUP_TOPIC_ID` int(11) NOT NULL,
  `GROUP_ORDER` int(11) NOT NULL,
  `SPEAKER_ID` int(11) NOT NULL,
  `PLACE` text NOT NULL,
  `DATEFROM` varchar(10) NOT NULL,
  `DATETO` varchar(10) NOT NULL,
  `TIMEFROM` varchar(5) NOT NULL,
  `TIMETO` varchar(5) NOT NULL,
  `CREDIT` int(11) NOT NULL,
  `CLASS_TYPE` int(11) NOT NULL,
  `COURSE_TYPE` int(11) NOT NULL,
  `UPLIMIT` int(11) NOT NULL,
  `INTRODUCTION` text NOT NULL,
  `NOTE` text NOT NULL,
  `MANAGERID` varchar(10) NOT NULL COMMENT '課程管理者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `course`
--

-- --------------------------------------------------------

--
-- Table structure for table `course_type`
--

CREATE TABLE IF NOT EXISTS `course_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` text NOT NULL,
  `GROUP` varchar(10) NOT NULL,
  `YSVH_EPID` varchar(10) DEFAULT NULL,
  `SAVH_EPID` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Dumping data for table `course_type`
--

INSERT INTO `course_type` (`id`, `type_name`, `GROUP`, `YSVH_EPID`, `SAVH_EPID`) VALUES
(33, '病人安全', '醫療專業', 'G221610514', ''),
(34, '感染管制(含 TB 1小時)', '醫療專業', 'G220394691', ''),
(35, '危機處理(緊急災害)', '醫療專業', 'G220109745', ''),
(36, '病人權利及隱私', '醫療專業', 'G221627760', ''),
(37, '全人醫療', '醫療專業', 'G221082127', ''),
(38, '醫療倫理(醫學/醫事/護理)', '醫療專業', 'G221082127', ''),
(39, '病歷寫作', '醫療專業', 'G221082127', ''),
(40, '醫療品質', '醫療專業', 'G221610514', ''),
(41, '醫療法規(醫學/醫事/護理)', '醫療專業', 'G221082127', ''),
(42, '實證醫學', '醫療專業', 'G221082127', ''),
(43, '急救訓練(BLS)', '醫療專業', 'G221627760', ''),
(44, '服務禮儀訓練', '服務', 'G121389790', ''),
(45, '醫病溝通', '服務', 'G221554155', ''),
(46, '醫療機構設置標準', '相關法令', 'G221177229', ''),
(47, '醫療法、緊急醫療救護法', '相關法令', 'G221177229', ''),
(48, '各職類醫事人員法', '相關法令', '', ''),
(49, '傳染病防制法', '相關法令', 'G220394691', ''),
(50, '精障保障法', '相關法令', 'G121194255', ''),
(51, '管制藥條例、藥害救濟、藥物不良', '相關法令', 'E221179552', ''),
(52, '環境保護法(環境教育)', '相關法令', 'G220109745', ''),
(53, '勞動基準法、勞工法、勞工設施規則、勞工健康保護規則、勞工安全衛生組織', '相關法令', 'G220109745', ''),
(54, '個資法', '相關法令', 'G221662741', ''),
(55, '放射科', '相關法令', 'G120078770', ''),
(56, '醫療院所辦理轉診作業須知', '相關法令', 'G120479062', ''),
(57, '安寧緩和醫療', '國家政策', 'G221627760', ''),
(58, '家暴及性侵', '國家政策', 'G121389790', ''),
(59, '個人資料保護', '國家政策', 'G221662741', ''),
(60, '資訊安全訓練', '國家政策', 'G221662741', ''),
(61, '行政中立', '國家政策', 'G221660569', ''),
(62, '內部控制', '國家政策', 'G220504759', ''),
(63, '電子病歷、健保政策', '國家政策', 'G220604585', ''),
(64, '全民國防教育', '國家政策', 'G221660569', ''),
(65, '性別主流化(含性別工作平等法)', '國家政策', 'G221660569', ''),
(66, '公務廉政倫理', '國家政策', 'C101145369', ''),
(67, '自殺防治', '國家政策', 'G121194255', ''),
(68, '器官捐贈(含器官移植條例)', '國家政策', 'G221554155', ''),
(69, '課程設計', '教學研究', 'G221082127', ''),
(70, '教學技巧', '教學研究', 'G221082127', ''),
(71, '評估技巧', '教學研究', 'G221082127', ''),
(72, '教材製作', '教學研究', 'G221082127', ''),
(73, '跨領域團隊合作照護訓練', '教學研究', 'G221082127', ''),
(74, '研究技巧', '教學研究', 'G221082127', '');

-- --------------------------------------------------------

--
-- Table structure for table `elearning`
--

CREATE TABLE IF NOT EXISTS `elearning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EPID` varchar(10) NOT NULL,
  `BRANCHNO` int(11) NOT NULL,
  `TOPIC` text NOT NULL,
  `DATE` varchar(10) NOT NULL,
  `TIMEFROM` varchar(5) NOT NULL,
  `TIMETO` varchar(5) NOT NULL,
  `CREDIT` int(11) NOT NULL,
  `COURSE_TYPE` int(11) NOT NULL,
  `DOC_LOCATION` text NOT NULL,
  `AUTH_STATUS` int(11) NOT NULL,
  `AUTH_COMMENT` text NOT NULL,
  `SEEN` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `elearning`
--

-- --------------------------------------------------------

--
-- Table structure for table `extracurricular`
--

CREATE TABLE IF NOT EXISTS `extracurricular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EPID` varchar(10) NOT NULL,
  `BRANCHNO` int(11) NOT NULL,
  `DATE` varchar(10) NOT NULL,
  `HOST` text NOT NULL,
  `PLACE` text NOT NULL,
  `TOPIC` text NOT NULL,
  `selfassess` int(11) NOT NULL,
  `learned` text NOT NULL,
  `expsuggest` text NOT NULL,
  `leave_type` int(11) NOT NULL,
  `course_type` int(11) NOT NULL,
  `DOC_LOCATION` text NOT NULL,
  `AUTH_STATUS` int(11) NOT NULL,
  `AUTH_COMMENT` text NOT NULL,
  `SEEN` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='院外研習心得報告' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `extracurricular`
--

-- --------------------------------------------------------

--
-- Table structure for table `ftctl_depart`
--

CREATE TABLE IF NOT EXISTS `ftctl_depart` (
  `DPNO` varchar(8) NOT NULL DEFAULT '',
  `DPNAME` varchar(30) NOT NULL DEFAULT '',
  `DPLEADER` varchar(10) NOT NULL DEFAULT '',
  `UPDPNO` varchar(8) NOT NULL DEFAULT '',
  `DPREGISTER` varchar(10) NOT NULL DEFAULT '',
  `EMAIL` varchar(50) NOT NULL DEFAULT '',
  `TEAM` char(1) NOT NULL DEFAULT '',
  `BRANCHNO` int(11) NOT NULL,
  KEY `A` (`DPNO`),
  KEY `B` (`DPNAME`),
  KEY `C` (`UPDPNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ftctl_depart`
--

INSERT INTO `ftctl_depart` (`DPNO`, `DPNAME`, `DPLEADER`, `UPDPNO`, `DPREGISTER`, `EMAIL`, `TEAM`, `BRANCHNO`) VALUES
('040060', '營養科', 'Y00251', '040000', '', '', '', 0),
('110010', '病理檢驗科', 'Y00659', '', 'Y00068', '', '', 0),
('900000', '測試部門', '05001', '', '9412007', '', '', 0),
('040020', '醫療費用組', 'Y00251', '040000', '', '', '', 0),
('020000', '副院長室', 'Y00872', '010000', '', '', '', 0),
('010000', '院長室', 'Y00791', '', '', '', '', 0),
('030000', '秘書室', 'Y00524', '030000', '', '', '', 0),
('030040', '駕駛班', 'Y00524', '030000', 'Y00022', '', '', 0),
('030030', '工務組', 'Y00524', '030000', 'Y00470', '', '', 0),
('040000', '醫務企管室', 'Y00251', '040000', '', '', '', 0),
('050000', '主計室', 'Y00627', '050000', '', '', '', 0),
('060000', '政風室', 'Y00686', '', 'Y00312', '', '', 0),
('070000', '人事室', 'Y00921', '', '', '', '', 0),
('150015', '牙科', 'Y00092', '', '', '', '', 0),
('110000', '內科部', 'Y00053', '', 'Y00056', '', '', 0),
('030100', '清潔班', 'Y00524', '030000', '', '', '', 0),
('150000', '外科部', 'Y00092', '', 'Y00085', '', '', 0),
('290120', '加護病房', 'Y00132', '290000', 'Y00134', '', '', 0),
('290000', '護理部', 'Y00194', '290000', '', '', '', 0),
('040040', '病歷管理組', 'Y00251', '040000', '', '', '', 0),
('030080', '出納組', 'Y00524', '030000', 'Y00285', '', '', 0),
('290150', '員山鄉門診部', 'Y00372', '290000', '', '', '', 0),
('030070', '採購組', 'Y00524', '030000', 'Y00577', '', '', 0),
('250000', '藥劑科', 'Y00126', '250000', 'Y00117', '', '', 0),
('210000', '婦產科', 'Y00092', '', '', '', '', 0),
('040030', '醫療事務組', 'Y00251', '040000', '', '', '', 0),
('200000', '精神部', 'Y00578', '', 'Y00578', '', '', 0),
('030010', '院辦組', 'Y00524', '030000', 'Y00278', '', '', 0),
('290100', '開刀房', 'Y00086', '290000', '', '', '', 0),
('290110', '急門診', 'Y00234', '290000', '', '', '', 0),
('290020', '2病房', 'Y00149', '290000', '', '', '', 0),
('290030', '3病房', 'Y00162', '290000', 'Y00161', '', '', 0),
('290040', '5病房', 'Y00148', '290000', '', '', '', 0),
('290050', '6病房', 'Y00139', '290000', 'Y00165', '', '', 0),
('290060', '7病房', 'Y00247', '290000', 'Y00186', '', '', 0),
('290070', '8病房', 'Y00236', '290000', 'Y00208', '', '', 0),
('290080', '11病房', 'Y00232', '290000', '', '', '', 0),
('290130', '市區門診', 'Y00372', '290000', '', '', '', 0),
('030020', '研考組', 'Y00524', '030000', 'Y00025', '', '', 0),
('100000', '社會工作室', 'Y00852', '100000', '', '', '', 0),
('290010', '1病房', 'Y00668', '290000', 'Y00152', '', '', 0),
('030090', '勤務中心', 'Y00524', '030000', '', '', '', 0),
('290090', '12病房', 'Y00265', '290000', 'Y00250', '', '', 0),
('030060', '安全組', 'Y00524', '030000', '', '', '', 0),
('240000', '復健科', 'Y00402', '240000', 'Y00076', '', '', 0),
('400000', '離職人員', '', '', '', '', '', 0),
('030110', '替代役', 'Y00524', '030000', '', '', '', 0),
('010002', '感染管制組', 'Y00681', '010000', '', '', '', 0),
('040050', '資訊組', 'Y00251', '040000', '', '', '', 0),
('290160', '9病房', '', '290000', '', '', '', 0),
('040010', '醫務企劃組', 'Y00251', '040000', '', '', '', 0),
('290140', '中期照護病房', 'Y00135', '290000', '', '', '', 0),
('260000', '健康管理中心', 'Y00790', '260000', '', '', '', 0),
('040070', '社區組', 'Y00251', '040000', '', '', '', 0),
('120000', '放射線科', 'Y00817', '', 'Y00088', '', '', 0),
('200010', '心理組', 'Y00578', '200000', 'Y00106', '', '', 0),
('200020', '社工組', 'Y00578', '200000', 'Y00109', '', '', 0),
('200030', '職能治療組', 'Y00578', '200000', 'Y00916', '', '', 0),
('200040', '康復之家', 'Y00578', '200000', 'Y00821', '', '', 0),
('200050', '社區復健中心', 'Y00578', '200000', '', '', '', 0),
('900000', '測試部門', '', '900000', 'T9412002', '', '', 1),
('030060', '院辦室', 'S00442', '030000', 'S00021', '', '', 1),
('020000', '副院長室', 'S00857', '010000', 'S00908', '', '', 1),
('010000', '院長室', 'S00857', '010000', '', '', '', 1),
('030000', '秘書室', 'S00442', '030000', 'S00055', '', '', 1),
('030040', '總機室', 'S00031', '030000', 'S00045', '', '', 1),
('030010', '洗衣室', 'S00031', '030000', 'S00032', '', '', 1),
('030020', '營繕室', 'S00442', '030000', 'S00031', '', '', 1),
('050000', '主計室', 'S00706', '050000', '', '', '', 1),
('060000', '政風室', 'S00015', '060000', 'S00013', '', '', 1),
('070000', '人事室', 'S00941', '070000', 'S00331', '', '', 1),
('110000', '內科部', 'S00833', '110000', '', '', '', 1),
('150000', '外科部', 'S00770', '150000', '', '', '', 1),
('290060', '門診', 'S00234', '290000', 'S00250', '', '', 1),
('290010', '一、二、隔離病房', 'S00179', '290000', '', '', '', 1),
('290020', '三病房', 'S00310', '290000', '', '', '', 1),
('290000', '護理部', 'S00644', '290000', 'S00644', '', '', 1),
('270000', '放射線科', 'S00877', '270000', '', '', '', 1),
('240000', '神經復健部', 'S00833', '240000', '', '', '', 1),
('250000', '藥劑科', 'S00698', '250000', 'S00140', '', '', 1),
('210000', '婦產科', 'S00908', '210000', '', '', '', 1),
('200000', '精神部', 'S00137', '200000', '', '', '', 1),
('290040', '八病房', 'S00726', '290000', '', '', '', 1),
('290050', '九病房', 'S00291', '290000', 'S00185', '', '', 1),
('290160', '洗腎室', 'S00806', '290000', '', '', '', 1),
('290080', '居家護理', 'S0088', '290000', '', '', '', 1),
('290180', '開刀房', 'S00873', '290000', '', '', '', 1),
('290030', '七病房', 'S00726', '290000', '', '', '', 1),
('290140', '加護病房', 'S00232', '290000', '', '', '', 1),
('300000', '慢性呼吸治療室', '', '300000', '', '', '', 1),
('030030', '駕駛室', 'S00031', '030000', 'S00038', '', '', 1),
('100000', '社會工作室', 'S00895', '100000', '', '', '', 1),
('290150', '急診室', 'S00263', '290000', 'S00256', '', '', 1),
('290190', '十一病房', 'S00830', '290000', '', '', '', 1),
('400000', '離職人員', '', '400000', '', '', '', 1),
('030070', '水電班', 'S00031', '030000', 'S00023', '', '', 1),
('030080', '廢水處理', 'S00031', '030000', 'S00036', '', '', 1),
('220000', '急診醫學科', 'S00833', '220000', '', '', '', 1),
('280000', '健康管理中心', 'S00876', '280000', '', '', '', 1),
('290200', '十二病房', 'S00204', '290000', '', '', '', 1),
('290090', '護理之家', 'S0088', '290000', '', '', '', 1),
('150010', '骨科', 'S00770', '150000', '', '', '', 1),
('090000', '感控組', 'S00107', '090000', 'S00107', '', '', 1),
('040010', '醫務企劃組', 'S00613', '040000', '', '', '', 1),
('040000', '醫務企管室', 'S00613', '040000', '', '', '', 1),
('040080', '社區組', 'S00613', '040000', '', '', '', 1),
('040030', '醫療費用組', 'S00613', '040000', '', '', '', 1),
('040040', '醫療事務組', 'S00613', '040000', '', '', '', 1),
('040050', '病歷管理組', 'S00613', '040000', '', '', '', 1),
('040060', '資訊組', 'S00613', '040000', '', '', '', 1),
('040070', '營養科', 'S00613', '040000', '', '', '', 1),
('260000', '病理檢驗科', 'S00061', '260000', '', '', '', 1),
('240010', '神經內科', 'S00833', '240000', '', '', '', 1),
('240020', '復健科', 'S00784', '240000', '', '', '', 1),
('150020', '眼科', 'S00770', '150000', '', '', '', 1),
('200010', '心理組', 'S00137', '200000', '', '', '', 1),
('200020', '社工組', 'S00137', '200000', '', '', '', 1),
('200030', '職能治療組', 'S00137', '200000', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ftctl_employ`
--

CREATE TABLE IF NOT EXISTS `ftctl_employ` (
  `EPNO` varchar(10) NOT NULL DEFAULT '',
  `EPID` varchar(12) NOT NULL DEFAULT '',
  `EPNAME` varchar(8) NOT NULL DEFAULT '',
  `PASSWORD` varchar(8) NOT NULL DEFAULT '',
  `EMAIL` varchar(32) NOT NULL DEFAULT '',
  `DPNO` varchar(8) NOT NULL DEFAULT '',
  `POSITION` varchar(20) NOT NULL DEFAULT '',
  `DUTYNAME` varchar(12) NOT NULL DEFAULT '',
  `RANKNAME` varchar(12) NOT NULL DEFAULT '',
  `UPEPNO` varchar(10) NOT NULL DEFAULT '',
  `SINGPIC` varchar(32) NOT NULL DEFAULT '',
  `ENAGENT` char(1) NOT NULL DEFAULT '',
  `AGREELEVEL` char(1) NOT NULL DEFAULT '',
  `RPTSEQ` char(3) NOT NULL DEFAULT '',
  `INDATE` varchar(9) NOT NULL DEFAULT '',
  `ONJOB` char(1) NOT NULL DEFAULT '',
  `IDCARDNO` varchar(10) NOT NULL DEFAULT '',
  `BIRTHDAY` varchar(9) NOT NULL DEFAULT '',
  `EPKIND` varchar(10) NOT NULL DEFAULT '',
  `CLASS` varchar(8) NOT NULL DEFAULT '',
  `INDAY` varchar(9) NOT NULL DEFAULT '',
  `OUTDAY` varchar(9) NOT NULL DEFAULT '',
  `YEAR` varchar(4) NOT NULL DEFAULT '',
  `OADD` varchar(80) NOT NULL DEFAULT '',
  `NADD` varchar(80) NOT NULL DEFAULT '',
  `TEL` varchar(10) NOT NULL DEFAULT '',
  `MANAGER` char(1) NOT NULL DEFAULT '',
  `POSTPOWER` char(1) NOT NULL DEFAULT '',
  `OLDYEARS` varchar(4) NOT NULL DEFAULT '',
  `SEX` char(2) NOT NULL DEFAULT '',
  `FORCEREAD` char(2) NOT NULL DEFAULT '',
  `PWLASTDATE` varchar(9) NOT NULL DEFAULT '',
  `EXTDPNO` varchar(8) NOT NULL DEFAULT '',
  `ISCONTINUE` char(1) NOT NULL DEFAULT '',
  `TRANSFEREE` char(1) NOT NULL DEFAULT '',
  `BRANCHNO` int(11) NOT NULL,
  KEY `A` (`EPNO`),
  KEY `B` (`OUTDAY`),
  KEY `C` (`EPNO`,`OUTDAY`),
  KEY `E` (`IDCARDNO`),
  KEY `F` (`EPNO`,`DPNO`,`IDCARDNO`,`OUTDAY`,`EPKIND`,`POSITION`),
  KEY `G` (`EPNO`,`INDAY`),
  KEY `H` (`EPNO`,`EPID`),
  KEY `D` (`EPID`),
  FULLTEXT KEY `PWLASTDATE` (`PWLASTDATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ftctl_employ`
--

INSERT INTO `ftctl_employ` (`EPNO`, `EPID`, `EPNAME`, `PASSWORD`, `EMAIL`, `DPNO`, `POSITION`, `DUTYNAME`, `RANKNAME`, `UPEPNO`, `SINGPIC`, `ENAGENT`, `AGREELEVEL`, `RPTSEQ`, `INDATE`, `ONJOB`, `IDCARDNO`, `BIRTHDAY`, `EPKIND`, `CLASS`, `INDAY`, `OUTDAY`, `YEAR`, `OADD`, `NADD`, `TEL`, `MANAGER`, `POSTPOWER`, `OLDYEARS`, `SEX`, `FORCEREAD`, `PWLASTDATE`, `EXTDPNO`, `ISCONTINUE`, `TRANSFEREE`, `BRANCHNO`) VALUES
('Y00893', 'A123456789', '丁振新', '123456', 'abc@abc.com', '040050', '替代役', '承辦人', '', 'Y00516', '', '', '', '', '', '1', 'A123456789', '076/1/1', '替代役', '', '102/11/15', '', '0002', '', '', '', '2', '1', '0000', 'M', 'N', '103/01/15', '', '0', '0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_course`
--

CREATE TABLE IF NOT EXISTS `group_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_topic` text NOT NULL,
  `DATEFROM` varchar(10) NOT NULL,
  `DATETO` varchar(10) NOT NULL,
  `CREDIT` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `group_course`
--


-- --------------------------------------------------------

--
-- Table structure for table `questionnaire`
--

CREATE TABLE IF NOT EXISTS `questionnaire` (
  `serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `TOPIC_ID` int(11) NOT NULL,
  `Q1` int(11) NOT NULL,
  `Q2` int(11) NOT NULL,
  `Q3` int(11) NOT NULL,
  `Q4` int(11) NOT NULL,
  `Q5` int(11) NOT NULL,
  `Q6` int(11) NOT NULL,
  `Q7` int(11) NOT NULL,
  `Q8` int(11) NOT NULL,
  `Q9` int(11) NOT NULL,
  `Q10` int(11) NOT NULL,
  `Q11` text NOT NULL,
  PRIMARY KEY (`serial_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questionnaire`
--

-- --------------------------------------------------------

--
-- Table structure for table `savh_ftctl_depart`
--

CREATE TABLE IF NOT EXISTS `savh_ftctl_depart` (
  `DPNO` varchar(8) NOT NULL DEFAULT '',
  `DPNAME` varchar(30) NOT NULL DEFAULT '',
  `DPLEADER` varchar(10) NOT NULL DEFAULT '',
  `UPDPNO` varchar(8) NOT NULL DEFAULT '',
  `DPREGISTER` varchar(10) NOT NULL DEFAULT '',
  `EMAIL` varchar(50) NOT NULL DEFAULT '',
  `TEAM` char(1) NOT NULL DEFAULT '',
  KEY `A` (`DPNO`),
  KEY `B` (`DPNAME`),
  KEY `C` (`UPDPNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `savh_ftctl_depart`
--

INSERT INTO `savh_ftctl_depart` (`DPNO`, `DPNAME`, `DPLEADER`, `UPDPNO`, `DPREGISTER`, `EMAIL`, `TEAM`) VALUES
('900000', '測試部門', '', '900000', 'T9412002', '', ''),
('030060', '院辦室', 'S00442', '030000', 'S00021', '', ''),
('020000', '副院長室', 'S00857', '010000', 'S00908', '', ''),
('010000', '院長室', 'S00857', '010000', '', '', ''),
('030000', '秘書室', 'S00442', '030000', 'S00055', '', ''),
('030040', '總機室', 'S00031', '030000', 'S00045', '', ''),
('030010', '洗衣室', 'S00031', '030000', 'S00032', '', ''),
('030020', '營繕室', 'S00442', '030000', 'S00031', '', ''),
('050000', '主計室', 'S00706', '050000', '', '', ''),
('060000', '政風室', 'S00015', '060000', 'S00013', '', ''),
('070000', '人事室', 'S00941', '070000', 'S00331', '', ''),
('110000', '內科部', 'S00833', '110000', '', '', ''),
('150000', '外科部', 'S00770', '150000', '', '', ''),
('290060', '門診', 'S00234', '290000', 'S00250', '', ''),
('290010', '一、二、隔離病房', 'S00179', '290000', '', '', ''),
('290020', '三病房', 'S00310', '290000', '', '', ''),
('290000', '護理部', 'S00644', '290000', 'S00644', '', ''),
('270000', '放射線科', 'S00877', '270000', '', '', ''),
('240000', '神經復健部', 'S00833', '240000', '', '', ''),
('250000', '藥劑科', 'S00698', '250000', 'S00140', '', ''),
('210000', '婦產科', 'S00908', '210000', '', '', ''),
('200000', '精神部', 'S00137', '200000', '', '', ''),
('290040', '八病房', 'S00726', '290000', '', '', ''),
('290050', '九病房', 'S00291', '290000', 'S00185', '', ''),
('290160', '洗腎室', 'S00806', '290000', '', '', ''),
('290080', '居家護理', 'S0088', '290000', '', '', ''),
('290180', '開刀房', 'S00873', '290000', '', '', ''),
('290030', '七病房', 'S00726', '290000', '', '', ''),
('290140', '加護病房', 'S00232', '290000', '', '', ''),
('300000', '慢性呼吸治療室', '', '300000', '', '', ''),
('030030', '駕駛室', 'S00031', '030000', 'S00038', '', ''),
('100000', '社會工作室', 'S00895', '100000', '', '', ''),
('290150', '急診室', 'S00263', '290000', 'S00256', '', ''),
('290190', '十一病房', 'S00830', '290000', '', '', ''),
('400000', '離職人員', '', '400000', '', '', ''),
('030070', '水電班', 'S00031', '030000', 'S00023', '', ''),
('030080', '廢水處理', 'S00031', '030000', 'S00036', '', ''),
('220000', '急診醫學科', 'S00833', '220000', '', '', ''),
('280000', '健康管理中心', 'S00876', '280000', '', '', ''),
('290200', '十二病房', 'S00204', '290000', '', '', ''),
('290090', '護理之家', 'S0088', '290000', '', '', ''),
('150010', '骨科', 'S00770', '150000', '', '', ''),
('090000', '感控組', 'S00107', '090000', 'S00107', '', ''),
('040010', '醫務企劃組', 'S00613', '040000', '', '', ''),
('040000', '醫務企管室', 'S00613', '040000', '', '', ''),
('040080', '社區組', 'S00613', '040000', '', '', ''),
('040030', '醫療費用組', 'S00613', '040000', '', '', ''),
('040040', '醫療事務組', 'S00613', '040000', '', '', ''),
('040050', '病歷管理組', 'S00613', '040000', '', '', ''),
('040060', '資訊組', 'S00613', '040000', '', '', ''),
('040070', '營養科', 'S00613', '040000', '', '', ''),
('260000', '病理檢驗科', 'S00061', '260000', '', '', ''),
('240010', '神經內科', 'S00833', '240000', '', '', ''),
('240020', '復健科', 'S00784', '240000', '', '', ''),
('150020', '眼科', 'S00770', '150000', '', '', ''),
('200010', '心理組', 'S00137', '200000', '', '', ''),
('200020', '社工組', 'S00137', '200000', '', '', ''),
('200030', '職能治療組', 'S00137', '200000', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `savh_ftctl_employ`
--

CREATE TABLE IF NOT EXISTS `savh_ftctl_employ` (
  `EPNO` varchar(10) NOT NULL DEFAULT '',
  `EPID` varchar(12) NOT NULL DEFAULT '',
  `EPNAME` varchar(8) NOT NULL DEFAULT '',
  `PASSWORD` varchar(8) NOT NULL DEFAULT '',
  `EMAIL` varchar(32) NOT NULL DEFAULT '',
  `DPNO` varchar(8) NOT NULL DEFAULT '',
  `POSITION` varchar(20) NOT NULL DEFAULT '',
  `DUTYNAME` varchar(12) NOT NULL DEFAULT '',
  `RANKNAME` varchar(12) NOT NULL DEFAULT '',
  `UPEPNO` varchar(10) NOT NULL DEFAULT '',
  `SINGPIC` varchar(32) NOT NULL DEFAULT '',
  `ENAGENT` char(1) NOT NULL DEFAULT '',
  `AGREELEVEL` char(1) NOT NULL DEFAULT '',
  `RPTSEQ` char(3) NOT NULL DEFAULT '',
  `INDATE` varchar(9) NOT NULL DEFAULT '',
  `ONJOB` char(1) NOT NULL DEFAULT '',
  `IDCARDNO` varchar(10) NOT NULL DEFAULT '',
  `BIRTHDAY` varchar(9) NOT NULL DEFAULT '',
  `EPKIND` varchar(10) NOT NULL DEFAULT '',
  `CLASS` varchar(8) NOT NULL DEFAULT '',
  `INDAY` varchar(9) NOT NULL DEFAULT '',
  `OUTDAY` varchar(9) NOT NULL DEFAULT '',
  `YEAR` varchar(4) NOT NULL DEFAULT '',
  `OADD` varchar(80) NOT NULL DEFAULT '',
  `NADD` varchar(80) NOT NULL DEFAULT '',
  `TEL` varchar(10) NOT NULL DEFAULT '',
  `MANAGER` char(1) NOT NULL DEFAULT '',
  `POSTPOWER` char(1) NOT NULL DEFAULT '',
  `OLDYEARS` varchar(4) NOT NULL DEFAULT '',
  `SEX` char(2) NOT NULL DEFAULT '',
  `FORCEREAD` char(2) NOT NULL DEFAULT '',
  `PWLASTDATE` varchar(9) NOT NULL DEFAULT '',
  `EXTDPNO` varchar(8) NOT NULL DEFAULT '',
  `ISCONTINUE` char(1) NOT NULL DEFAULT '',
  `TRANSFEREE` char(1) NOT NULL DEFAULT '',
  KEY `A` (`EPNO`),
  KEY `B` (`OUTDAY`),
  KEY `C` (`EPNO`,`OUTDAY`),
  KEY `E` (`IDCARDNO`),
  KEY `F` (`EPNO`,`DPNO`,`IDCARDNO`,`OUTDAY`,`EPKIND`,`POSITION`),
  KEY `G` (`EPNO`,`INDAY`),
  KEY `H` (`EPNO`,`EPID`),
  KEY `D` (`EPID`),
  FULLTEXT KEY `PWLASTDATE` (`PWLASTDATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `savh_ftctl_employ`
--

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE IF NOT EXISTS `signup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `IDCARDNO` varchar(10) NOT NULL,
  `course_id` int(11) NOT NULL,
  `leave_type` int(11) NOT NULL,
  `registered` int(11) NOT NULL,
  `DATEFROM` varchar(10) NOT NULL,
  `DATETO` varchar(10) NOT NULL,
  `intime` varchar(5) NOT NULL,
  `outtime` varchar(5) NOT NULL,
  `questionnaire` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `signup`
--


-- --------------------------------------------------------

--
-- Table structure for table `speaker`
--

CREATE TABLE IF NOT EXISTS `speaker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` text NOT NULL,
  `IDCARDNO` varchar(10) NOT NULL,
  `WORKEXP` text NOT NULL,
  `CURRENTWORK` text NOT NULL,
  `EDUCATION` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `speaker`
--

-- --------------------------------------------------------

--
-- Table structure for table `ysvh_ftctl_depart`
--

CREATE TABLE IF NOT EXISTS `ysvh_ftctl_depart` (
  `DPNO` varchar(8) NOT NULL DEFAULT '',
  `DPNAME` varchar(30) NOT NULL DEFAULT '',
  `DPLEADER` varchar(10) NOT NULL DEFAULT '',
  `UPDPNO` varchar(8) NOT NULL DEFAULT '',
  `DPREGISTER` varchar(10) NOT NULL DEFAULT '',
  `EMAIL` varchar(50) NOT NULL DEFAULT '',
  `TEAM` char(1) NOT NULL DEFAULT '',
  KEY `A` (`DPNO`),
  KEY `B` (`DPNAME`),
  KEY `C` (`UPDPNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ysvh_ftctl_depart`
--

INSERT INTO `ysvh_ftctl_depart` (`DPNO`, `DPNAME`, `DPLEADER`, `UPDPNO`, `DPREGISTER`, `EMAIL`, `TEAM`) VALUES
('040060', '營養科', 'Y00251', '040000', '', '', ''),
('110010', '病理檢驗科', 'Y00659', '', 'Y00068', '', ''),
('900000', '測試部門', '05001', '', '9412007', '', ''),
('040020', '醫療費用組', 'Y00251', '040000', '', '', ''),
('020000', '副院長室', 'Y00872', '010000', '', '', ''),
('010000', '院長室', 'Y00791', '', '', '', ''),
('030000', '秘書室', 'Y00524', '030000', '', '', ''),
('030040', '駕駛班', 'Y00524', '030000', 'Y00022', '', ''),
('030030', '工務組', 'Y00524', '030000', 'Y00470', '', ''),
('040000', '醫務企管室', 'Y00251', '040000', '', '', ''),
('050000', '主計室', 'Y00627', '050000', '', '', ''),
('060000', '政風室', 'Y00686', '', 'Y00312', '', ''),
('070000', '人事室', 'Y00921', '', '', '', ''),
('150015', '牙科', 'Y00092', '', '', '', ''),
('110000', '內科部', 'Y00053', '', 'Y00056', '', ''),
('030100', '清潔班', 'Y00524', '030000', '', '', ''),
('150000', '外科部', 'Y00092', '', 'Y00085', '', ''),
('290120', '加護病房', 'Y00132', '290000', 'Y00134', '', ''),
('290000', '護理部', 'Y00194', '290000', '', '', ''),
('040040', '病歷管理組', 'Y00251', '040000', '', '', ''),
('030080', '出納組', 'Y00524', '030000', 'Y00285', '', ''),
('290150', '員山鄉門診部', 'Y00372', '290000', '', '', ''),
('030070', '採購組', 'Y00524', '030000', 'Y00577', '', ''),
('250000', '藥劑科', 'Y00126', '250000', 'Y00117', '', ''),
('210000', '婦產科', 'Y00092', '', '', '', ''),
('040030', '醫療事務組', 'Y00251', '040000', '', '', ''),
('200000', '精神部', 'Y00578', '', 'Y00578', '', ''),
('030010', '院辦組', 'Y00524', '030000', 'Y00278', '', ''),
('290100', '開刀房', 'Y00086', '290000', '', '', ''),
('290110', '急門診', 'Y00234', '290000', '', '', ''),
('290020', '2病房', 'Y00149', '290000', '', '', ''),
('290030', '3病房', 'Y00162', '290000', 'Y00161', '', ''),
('290040', '5病房', 'Y00148', '290000', '', '', ''),
('290050', '6病房', 'Y00139', '290000', 'Y00165', '', ''),
('290060', '7病房', 'Y00247', '290000', 'Y00186', '', ''),
('290070', '8病房', 'Y00236', '290000', 'Y00208', '', ''),
('290080', '11病房', 'Y00232', '290000', '', '', ''),
('290130', '市區門診', 'Y00372', '290000', '', '', ''),
('030020', '研考組', 'Y00524', '030000', 'Y00025', '', ''),
('100000', '社會工作室', 'Y00852', '100000', '', '', ''),
('290010', '1病房', 'Y00668', '290000', 'Y00152', '', ''),
('030090', '勤務中心', 'Y00524', '030000', '', '', ''),
('290090', '12病房', 'Y00265', '290000', 'Y00250', '', ''),
('030060', '安全組', 'Y00524', '030000', '', '', ''),
('240000', '復健科', 'Y00402', '240000', 'Y00076', '', ''),
('400000', '離職人員', '', '', '', '', ''),
('030110', '替代役', 'Y00524', '030000', '', '', ''),
('010002', '感染管制組', 'Y00681', '010000', '', '', ''),
('040050', '資訊組', 'Y00251', '040000', '', '', ''),
('290160', '9病房', '', '290000', '', '', ''),
('040010', '醫務企劃組', 'Y00251', '040000', '', '', ''),
('290140', '中期照護病房', 'Y00135', '290000', '', '', ''),
('260000', '健康管理中心', 'Y00790', '260000', '', '', ''),
('040070', '社區組', 'Y00251', '040000', '', '', ''),
('120000', '放射線科', 'Y00817', '', 'Y00088', '', ''),
('200010', '心理組', 'Y00578', '200000', 'Y00106', '', ''),
('200020', '社工組', 'Y00578', '200000', 'Y00109', '', ''),
('200030', '職能治療組', 'Y00578', '200000', 'Y00916', '', ''),
('200040', '康復之家', 'Y00578', '200000', 'Y00821', '', ''),
('200050', '社區復健中心', 'Y00578', '200000', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ysvh_ftctl_employ`
--

CREATE TABLE IF NOT EXISTS `ysvh_ftctl_employ` (
  `EPNO` varchar(10) NOT NULL DEFAULT '',
  `EPID` varchar(12) NOT NULL DEFAULT '',
  `EPNAME` varchar(8) NOT NULL DEFAULT '',
  `PASSWORD` varchar(8) NOT NULL DEFAULT '',
  `EMAIL` varchar(32) NOT NULL DEFAULT '',
  `DPNO` varchar(8) NOT NULL DEFAULT '',
  `POSITION` varchar(20) NOT NULL DEFAULT '',
  `DUTYNAME` varchar(12) NOT NULL DEFAULT '',
  `RANKNAME` varchar(12) NOT NULL DEFAULT '',
  `UPEPNO` varchar(10) NOT NULL DEFAULT '',
  `SINGPIC` varchar(32) NOT NULL DEFAULT '',
  `ENAGENT` char(1) NOT NULL DEFAULT '',
  `AGREELEVEL` char(1) NOT NULL DEFAULT '',
  `RPTSEQ` char(3) NOT NULL DEFAULT '',
  `INDATE` varchar(9) NOT NULL DEFAULT '',
  `ONJOB` char(1) NOT NULL DEFAULT '',
  `IDCARDNO` varchar(10) NOT NULL DEFAULT '',
  `BIRTHDAY` varchar(9) NOT NULL DEFAULT '',
  `EPKIND` varchar(10) NOT NULL DEFAULT '',
  `CLASS` varchar(8) NOT NULL DEFAULT '',
  `INDAY` varchar(9) NOT NULL DEFAULT '',
  `OUTDAY` varchar(9) NOT NULL DEFAULT '',
  `YEAR` varchar(4) NOT NULL DEFAULT '',
  `OADD` varchar(80) NOT NULL DEFAULT '',
  `NADD` varchar(80) NOT NULL DEFAULT '',
  `TEL` varchar(10) NOT NULL DEFAULT '',
  `MANAGER` char(1) NOT NULL DEFAULT '',
  `POSTPOWER` char(1) NOT NULL DEFAULT '',
  `OLDYEARS` varchar(4) NOT NULL DEFAULT '',
  `SEX` char(2) NOT NULL DEFAULT '',
  `FORCEREAD` char(2) NOT NULL DEFAULT '',
  `PWLASTDATE` varchar(9) NOT NULL DEFAULT '',
  `EXTDPNO` varchar(8) NOT NULL DEFAULT '',
  `ISCONTINUE` char(1) NOT NULL DEFAULT '',
  `TRANSFEREE` char(1) NOT NULL DEFAULT '',
  KEY `A` (`EPNO`),
  KEY `B` (`OUTDAY`),
  KEY `C` (`EPNO`,`OUTDAY`),
  KEY `D` (`EPID`),
  KEY `E` (`IDCARDNO`),
  KEY `F` (`EPNO`,`DPNO`,`IDCARDNO`,`OUTDAY`,`EPKIND`,`POSITION`),
  KEY `G` (`EPNO`,`INDAY`),
  KEY `H` (`EPNO`,`EPID`),
  FULLTEXT KEY `PWLASTDATE` (`PWLASTDATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ysvh_ftctl_employ`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
