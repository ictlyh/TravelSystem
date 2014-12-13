drop database IF EXISTS project2;
create database project2;
use project2;

CREATE TABLE IF NOT EXISTS `ADMIN`(
`adminName` varchar(16) NOT NULL,
`password` varchar(32) NOT NULL,
PRIMARY KEY(`adminName`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ADMIN`(`adminName`,`password`) VALUES
('root','63a9f0ea7bb98050796b649e85481845');

#DROP TABLE IF EXISTS `CUSTOMERS`;
CREATE TABLE IF NOT EXISTS `CUSTOMERS` (
  `custName` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`custName`),
  UNIQUE KEY `name` (`custName`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


#DROP TABLE IF EXISTS `FLIGHTS`;
CREATE TABLE IF NOT EXISTS `FLIGHTS` (
  `flightNum` varchar(9) NOT NULL,
  `price` int(11) NOT NULL,
  `numSeats` int(11) NOT NULL,
  `numAvail` int(11) NOT NULL,
  `FromCity` varchar(20) NOT NULL,
  `ArivCity` varchar(20) NOT NULL,
  PRIMARY KEY (`flightNum`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `FLIGHTS` (`flightNum`, `price`, `numSeats`, `numAvail`, `FromCity`, `ArivCity`) VALUES
('CX00001', 100, 100, 100, 'Hefei', 'Beijing'),
('CX00002', 200, 200, 200, 'Hefei', 'Shanghai'),
('CX00003', 300, 300, 300, 'Shanghai', 'Beijing'),
('CX00004', 300, 300, 300, 'Beijing', 'Shanghai'),
('NEV00001', 500, 500,500, 'Hefei', 'San Francisco'),
('OC00001', 400, 400, 400, 'Hefei', 'Beijing');

#DROP TABLE IF EXISTS `CARS`;
CREATE TABLE IF NOT EXISTS `CARS` (
  `location` varchar(20) NOT NULL,
  `price` int(11) NOT NULL,
  `numCars` int(11) NOT NULL,
  `numAvail` int(11) NOT NULL,
  PRIMARY KEY (`location`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `CARS` (`location`, `price`, `numCars`, `numAvail`) VALUES
('Beijing', 30, 300, 300),
('Hefei', 10, 100, 100),
('Shanghai', 20, 200, 200);

#DROP TABLE IF EXISTS `HOTELS`;
CREATE TABLE IF NOT EXISTS `HOTELS` (
  `location` varchar(20) NOT NULL,
  `price` int(11) NOT NULL,
  `numRooms` int(11) NOT NULL,
  `numAvail` int(11) NOT NULL,
  PRIMARY KEY (`location`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `HOTELS` (`location`, `price`, `numRooms`, `numAvail`) VALUES
('Beijing', 100, 1000, 1000),
('Hefei', 70, 700, 700),
('Shanghai', 90, 900, 900);


#DROP TABLE IF EXISTS `RESERVATIONS`;
CREATE TABLE IF NOT EXISTS `RESERVATIONS` (
  `custName` varchar(16) NOT NULL,
  `resvType` int(11) NOT NULL,
  `resvKey` int(11) NOT NULL AUTO_INCREMENT,
  `aditInfo` varchar(20) NOT NULL,
  PRIMARY KEY (`resvKey`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;