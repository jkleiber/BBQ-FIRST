-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 30, 2014 at 01:00 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `regionaldata`
--

CREATE TABLE IF NOT EXISTS `regionaldata` (
  `reg_name` text NOT NULL,
  `banners` int(10) NOT NULL,
  `teams` int(10) NOT NULL,
  `years` int(10) NOT NULL,
  `bbq` text NOT NULL,
  `sponsored` text NOT NULL,
  `reg_key` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `regionaldata`
--

INSERT INTO `regionaldata` (`reg_name`, `banners`, `teams`, `years`, `bbq`, `sponsored`, `reg_key`) VALUES
('Alamo Regional sponsored by Rackspace Hosting', 87, 64, 437, '1.359375', 'official', '2014txsa'),
('Archimedes Division', 310, 100, 741, '3.1', 'official', '2014arc'),
('Arizona Regional', 45, 51, 304, '0.88235294117647', 'official', '2014azch'),
('Arkansas Regional', 51, 39, 221, '1.3076923076923', 'official', '2014arfa'),
('Autodesk PNW FRC Championship', 96, 64, 378, '1.5', 'official', '2014pncmp'),
('BattleCry 15', 107, 56, 644, '1.9107142857143', 'unofficial', '2014bc'),
('Battle O''Baltimore', 0, 0, 0, '0', 'unofficial', '2014mdbb'),
('Bayou Regional', 49, 57, 330, '0.85964912280702', 'official', '2014lake'),
('Beantown Blitz', 64, 24, 247, '2.6666666666667', 'unofficial', '2014bt'),
('Bedford FIRST Robotics District Competition', 81, 41, 313, '1.9756097560976', 'official', '2014mibed'),
('Boilermaker Regional', 40, 40, 286, '1', 'official', '2014inwl'),
('Buckeye Regional', 89, 54, 445, '1.6481481481481', 'official', '2014ohcl'),
('BunnyBots', 0, 0, 0, '0', 'unofficial', '2014orbb'),
('CAGE Match', 0, 0, 0, '0', 'unofficial', '2014incm'),
('Center Line FIRST Robotics District Competition', 69, 40, 255, '1.725', 'official', '2014micen'),
('Central Illinois Regional', 63, 40, 305, '1.575', 'official', '2014ilil'),
('Central Valley Regional', 60, 45, 345, '1.3333333333333', 'official', '2014cama'),
('Chesapeake Regional', 66, 54, 398, '1.2222222222222', 'official', '2014mdba'),
('Chezy Champs', 144, 38, 360, '3.7894736842105', 'unofficial', '2014cc'),
('Colorado Regional', 37, 52, 344, '0.71153846153846', 'official', '2014code'),
('CORI', 0, 0, 0, '0', 'unofficial', '2014ohri'),
('Cow Town ThrowDown', 0, 0, 0, '0', 'unofficial', '2014cttd'),
('Crossroads Regional', 110, 45, 367, '2.4444444444444', 'official', '2014inth'),
('CT State Championship', 90, 34, 361, '2.6470588235294', 'unofficial', '2014ctsc'),
('Curie Division', 375, 101, 805, '3.7128712871287', 'official', '2014cur'),
('Dallas Regional', 89, 45, 285, '1.9777777777778', 'official', '2014txda'),
('Duel Down Under', 0, 0, 0, '0', 'unofficial', '2014audd'),
('Duel on the Delaware', 0, 0, 0, '0', 'unofficial', '2014njdd'),
('Einstein Field', 244, 29, 271, '8.4137931034483', 'official', '2014cmp'),
('Escanaba FIRST Robotics District Competition', 41, 36, 175, '1.1388888888889', 'official', '2014miesc'),
('Festival de Robotique FRC a Montreal Regional', 15, 41, 152, '0.36585365853659', 'official', '2014qcmo'),
('Finger Lakes Regional', 98, 49, 442, '2', 'official', '2014nyro'),
('Galileo Division', 351, 100, 849, '3.51', 'official', '2014gal'),
('Gateway Robotics Challenge', 0, 0, 0, '0', 'unofficial', '2014mogw'),
('Girls'' Generation', 0, 0, 0, '0', 'unofficial', '2014orgg'),
('Granite State District Event', 42, 39, 324, '1.0769230769231', 'official', '2014nhnas'),
('Greater DC Regional', 41, 51, 411, '0.80392156862745', 'official', '2014dcwa'),
('Greater Kansas City Regional', 69, 58, 384, '1.1896551724138', 'official', '2014mokc'),
('Greater Pittsburgh Regional', 54, 48, 310, '1.125', 'official', '2014papi'),
('Greater Toronto East Regional', 76, 49, 303, '1.5510204081633', 'official', '2014onto'),
('Greater Toronto West Regional', 31, 30, 168, '1.0333333333333', 'official', '2014onto2'),
('Great Lakes Bay Region FIRST Robotics District Competition', 59, 40, 203, '1.475', 'official', '2014mimid'),
('GRITS', 0, 0, 0, '0', 'unofficial', '2014gagr'),
('Groton District Event', 83, 33, 337, '2.5151515151515', 'official', '2014ctgro'),
('Gull Lake FIRST Robotics District Competition', 33, 41, 184, '0.80487804878049', 'official', '2014migul'),
('Hartford District Event', 80, 40, 380, '2', 'official', '2014cthar'),
('Hawaii Regional', 51, 39, 217, '1.3076923076923', 'official', '2014hiho'),
('Howell FIRST Robotics District Competition', 112, 40, 287, '2.8', 'official', '2014mihow'),
('Hub City Regional', 34, 42, 201, '0.80952380952381', 'official', '2014txlu'),
('Indiana Robotics Invitational', 605, 68, 818, '8.8970588235294', 'unofficial', '2014iri'),
('Inland Empire Regional', 36, 40, 233, '0.9', 'official', '2014casb'),
('Israel Regional', 31, 53, 279, '0.58490566037736', 'official', '2014ista'),
('Kettering Kickoff', 0, 0, 0, '0', 'unofficial', '2014mikk'),
('Kettering University FIRST Robotics District Competition', 51, 40, 231, '1.275', 'official', '2014miket'),
('Lake Superior Regional', 38, 57, 280, '0.66666666666667', 'official', '2014mndu'),
('Lansing FIRST Robotics District Competition', 89, 42, 234, '2.1190476190476', 'official', '2014milan'),
('Las Vegas Regional', 99, 51, 341, '1.9411764705882', 'official', '2014nvlv'),
('Livonia FIRST Robotics District Competition', 48, 40, 269, '1.2', 'official', '2014miliv'),
('Lone Star Regional', 88, 56, 357, '1.5714285714286', 'official', '2014txho'),
('Los Angeles Regional sponsored by The Roddenberry Foundation', 68, 66, 461, '1.030303030303', 'official', '2014calb'),
('Mainely SPIRIT', 0, 0, 0, '0', 'unofficial', '2014mems'),
('MAR FIRST Robotics Bridgewater-Raritan District Competition', 68, 46, 426, '1.4782608695652', 'official', '2014njbri'),
('MAR FIRST Robotics Clifton District Competition', 32, 33, 295, '0.96969696969697', 'official', '2014njcli'),
('MAR FIRST Robotics Hatboro-Horsham District Competition', 100, 40, 379, '2.5', 'official', '2014pahat'),
('MAR FIRST Robotics Lenape-Seneca District Competition', 75, 40, 379, '1.875', 'official', '2014njtab'),
('MAR FIRST Robotics Mt. Olive District Competition', 61, 38, 350, '1.6052631578947', 'official', '2014njfla'),
('MAR FIRST Robotics Springside Chestnut Hill District Competition', 83, 34, 348, '2.4411764705882', 'official', '2014paphi'),
('Mexico City Regional', 8, 38, 107, '0.21052631578947', 'official', '2014mxmc'),
('Michigan Advanced Robotics Competition', 252, 48, 514, '5.25', 'unofficial', '2014marc'),
('Michigan FRC State Championship', 278, 70, 609, '3.9714285714286', 'official', '2014micmp'),
('Mid-Atlantic Robotics FRC Region Championship', 180, 58, 587, '3.1034482758621', 'official', '2014mrcmp'),
('MidKnight Mayhem', 0, 0, 0, '0', 'unofficial', '2014njmm'),
('Midwest Regional', 74, 54, 342, '1.3703703703704', 'official', '2014ilch'),
('Minnesota 10000 Lakes Regional', 18, 64, 325, '0.28125', 'official', '2014mnmi'),
('Minnesota North Star Regional', 29, 60, 310, '0.48333333333333', 'official', '2014mnmi2'),
('Monty Madness', 95, 42, 440, '2.2619047619048', 'unofficial', '2014mm'),
('MVRC', 0, 0, 0, '0', 'unofficial', '2014ohmv'),
('New England FRC Region Championship', 119, 54, 568, '2.2037037037037', 'official', '2014necmp'),
('Newton Division', 405, 100, 804, '4.05', 'official', '2014new'),
('New York City Regional', 67, 66, 519, '1.0151515151515', 'official', '2014nyny'),
('New York Tech Valley Regional', 77, 38, 337, '2.0263157894737', 'official', '2014nytr'),
('North Bay Regional', 35, 36, 170, '0.97222222222222', 'official', '2014onnb'),
('North Carolina Regional', 39, 54, 313, '0.72222222222222', 'official', '2014ncre'),
('Northeastern University District Event', 44, 40, 329, '1.1', 'official', '2014mabos'),
('Northern Lights Regional', 57, 55, 303, '1.0363636363636', 'official', '2014mndu2'),
('Ohio FRC State Championship', 0, 0, 0, '0', 'unofficial', '2014ohcmp'),
('Oklahoma Regional', 44, 63, 405, '0.6984126984127', 'official', '2014okok'),
('Orlando Regional', 103, 62, 456, '1.6612903225806', 'official', '2014flor'),
('Ozark Mountain Brawl', 0, 0, 0, '0', 'unofficial', '2014aroz'),
('Palmetto Regional', 77, 67, 382, '1.1492537313433', 'official', '2014scmb'),
('Panther Prowl', 0, 0, 0, '0', 'unofficial', '2014flpp'),
('Peachtree Regional', 63, 65, 344, '0.96923076923077', 'official', '2014gadu'),
('Pine Tree District Event', 30, 38, 291, '0.78947368421053', 'official', '2014melew'),
('PNW FIRST Robotics Auburn District Event', 35, 36, 186, '0.97222222222222', 'official', '2014waahs'),
('PNW FIRST Robotics Auburn Mountainview District Event', 22, 32, 154, '0.6875', 'official', '2014waamv'),
('PNW FIRST Robotics Central Washington University District Event', 17, 35, 167, '0.48571428571429', 'official', '2014waell'),
('PNW FIRST Robotics Eastern Washington University District Event', 27, 31, 144, '0.87096774193548', 'official', '2014wache'),
('PNW FIRST Robotics Glacier Peak District Event', 25, 32, 158, '0.78125', 'official', '2014wasno'),
('PNW FIRST Robotics Mt. Vernon District Event', 17, 28, 155, '0.60714285714286', 'official', '2014wamou'),
('PNW FIRST Robotics Oregon City District Event', 39, 35, 212, '1.1142857142857', 'official', '2014orore'),
('PNW FIRST Robotics Oregon State University District Event', 31, 30, 208, '1.0333333333333', 'official', '2014orosu'),
('PNW FIRST Robotics Shorewood District Event', 28, 33, 185, '0.84848484848485', 'official', '2014washo'),
('PNW FIRST Robotics Wilsonville District Event', 34, 31, 185, '1.0967741935484', 'official', '2014orwil'),
('Queen City Regional', 100, 47, 352, '2.1276595744681', 'official', '2014ohci'),
('R2OC', 68, 32, 286, '2.125', 'unofficial', '2014ilrr'),
('Rah Cha Cha Ruckus', 0, 0, 0, '0', 'unofficial', '2014nyrr'),
('Rah Cha Cha Ruckus', 0, 0, 0, '0', 'unofficial', '2014ruc'),
('Red Stick Rumble', 0, 0, 0, '0', 'unofficial', '2014rsr'),
('Rhode Island District Event', 48, 37, 291, '1.2972972972973', 'official', '2014rismi'),
('RoboReboot', 14, 18, 79, '0.77777777777778', 'unofficial', '2014txrb'),
('ROBOTICON', 0, 0, 0, '0', 'unofficial', '2014flrc'),
('Robot Roundup', 0, 0, 0, '0', 'unofficial', '2014txrr'),
('Rookie Rumble', 0, 0, 0, '0', 'unofficial', '2014orrr'),
('Rumble in the Roads', 0, 0, 0, '0', 'unofficial', '2014varr'),
('Sacramento Regional', 62, 55, 422, '1.1272727272727', 'official', '2014casa'),
('San Diego Regional', 68, 60, 335, '1.1333333333333', 'official', '2014casd'),
('SBPLI Long Island Regional', 51, 50, 498, '1.02', 'official', '2014nyli'),
('SCRIW IV', 0, 0, 0, '0', 'unofficial', '2014scs'),
('Silicon Valley Regional', 122, 58, 514, '2.1034482758621', 'official', '2014casj'),
('Smoky Mountains Regional', 34, 50, 216, '0.68', 'official', '2014tnkn'),
('Southfield FIRST Robotics District Competition', 70, 40, 296, '1.75', 'official', '2014misou'),
('South Florida Regional', 54, 47, 327, '1.1489361702128', 'official', '2014flfo'),
('Southington District Event', 48, 33, 305, '1.4545454545455', 'official', '2014ctsou'),
('St. Joseph FIRST Robotics District Competition', 58, 41, 225, '1.4146341463415', 'official', '2014misjo'),
('St. Louis Regional', 33, 45, 243, '0.73333333333333', 'official', '2014mosl'),
('Suffield Shakedown', 0, 0, 0, '0', 'unofficial', '2014ctss'),
('Tech Valley Robot Rumble', 0, 0, 0, '0', 'unofficial', '2014nytv'),
('Texas Robotics Invitational', 84, 29, 214, '2.8965517241379', 'unofficial', '2014txri'),
('THOR', 0, 0, 0, '0', 'unofficial', '2014ncth'),
('Traverse City FIRST Robotics District Competition', 8, 40, 170, '0.2', 'official', '2014mitvc'),
('Troy FIRST Robotics District Competition', 111, 40, 241, '2.775', 'official', '2014mitry'),
('UNH District Event', 43, 38, 339, '1.1315789473684', 'official', '2014nhdur'),
('Utah Regional', 42, 48, 282, '0.875', 'official', '2014utwv'),
('Virginia Regional', 61, 65, 545, '0.93846153846154', 'official', '2014vari'),
('Waterford FIRST Robotics District Competition', 95, 40, 208, '2.375', 'official', '2014miwat'),
('Waterloo Regional', 128, 30, 158, '4.2666666666667', 'official', '2014onwa'),
('Western Canada Regional', 15, 36, 129, '0.41666666666667', 'official', '2014abca'),
('West Michigan FIRST Robotics District Competition', 73, 40, 273, '1.825', 'official', '2014miwmi'),
('West Michigan Robotics Invitational', 0, 0, 0, '0', 'unofficial', '2014wmri'),
('Where is Wolcott', 0, 0, 0, '0', 'unofficial', '2014wiwi'),
('Windsor Essex Great Lakes Regional', 99, 40, 232, '2.475', 'official', '2014onwi'),
('Wisconsin Regional', 108, 60, 488, '1.8', 'official', '2014wimi'),
('WPI District Event', 33, 40, 330, '0.825', 'official', '2014mawor'),
('WVROX', 0, 0, 0, '0', 'unofficial', '2014wvro');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
