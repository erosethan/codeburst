drop database if exists `CodeBurst`;
create database `CodeBurst`;
use `CodeBurst`;

drop table if exists `User`;
create table `User`(
	UserId int(10) not null auto_increment,
	UserName varchar(50) not null,
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
