drop database if exists `CodeBurst`;
create database `CodeBurst`;
use `CodeBurst`;

drop table if exists `User`;
create table `User`(
	UserId int(10) not null auto_increment,
	UserName varchar(50) not null,
	UserPass varchar(20) not null,
	primary key(UserId)
);

drop table if exists `Round`;
create table `Round`(
	RoundId int(10) not null auto_increment,
	RoundName varchar(50) not null,
	RoundBase varchar(100) not null,
	CodingStart datetime not null,
	BurningStart datetime not null,
	RoundEnd datetime not null,
	primary key(RoundId)
);

drop table if exists `Match`;
create table `Match`(	
	RoundId int(10) not null,
	RedUserId int(10) not null,
	BlueUserId int(10) not null,
	`RedUserScore` tinyint(4) NOT NULL DEFAULT '0',
	`BlueUserScore` tinyint(4) NOT NULL DEFAULT '0',
	primary key(RoundId, RedUserId)
);

drop table if exists `Code`;
create table `Code`(
	UserId int(10) not null,
	RoundId int(10) not null,
	Submission datetime not null,
	CodeLang varchar(5) not null,
	primary key(UserId, RoundId)
);

drop table if exists `Burn`;
create table `Burn`(
	UserId int(10) not null,
	RoundId int(10) not null,
	Submission datetime not null,
	primary key(UserId, RoundId)
);

DROP VIEW IF EXISTS `matchsinroundscoringdata`; 
CREATE VIEW `matchsinroundscoringdata` AS select `m`.`RoundId` AS `RoundId`,`m`.`RedUserId` AS `RedUserId`,`m`.`BlueUserId` AS `BlueUserId`,`m`.`RedUserScore` AS `RedUserScore`,`m`.`BlueUserScore` AS `BlueUserScore`,`cr`.`Submission` AS `RedUserSubmission`,`cb`.`Submission` AS `BlueUserSubmission` from ((`match` `m` join `code` `cr` on((`m`.`RedUserId` = `cr`.`UserId`))) join `code` `cb` on((`m`.`BlueUserId` = `cb`.`UserId`)));

