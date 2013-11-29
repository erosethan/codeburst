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

drop view if exists `matchsalldata`;
create view `matchsalldata` AS
select M.*, CR.Submission as RedUserSubmission, CB.Submission as BlueUserSubmission, UR.UserName as RedUserName, UB.UserName as BlueUserName
from `Match` as M
left outer join `Code` as CR ON CR.UserId = M.RedUserId
left outer join `Code` as CB ON CB.UserId = M.BlueUserId
inner join `User` as UR on UR.UserId = RedUserId
inner join `User` as UB on UB.UserId = BlueUserId;

drop view if exists `matchsalldatawinners`;
create view `matchsalldatawinners` as
select *, if(RedUserSubmission IS NULL, "Blue", if(BlueUserScore = RedUserScore, if(BlueUserSubmission < RedUserSubmission, "Blue", "Red"), if(BlueUserScore > RedUserScore, "Blue", "Red"))) as Winner
from `matchsalldata`;

drop view if exists `codingstagescore`;
create view `codingstagescore` as
(select R.RoundId, MR.RedUserName as UserName, TIMESTAMPDIFF(MINUTE, R.CodingStart, MR.RedUserSubmission) as Submission
from `matchsalldata` as MR
inner join `Round` as R on MR.RoundId = R.RoundId)
union
(select R.RoundId, MB.BlueUserName as UserName, TIMESTAMPDIFF(MINUTE, R.CodingStart, MB.BlueUserSubmission) as Submission
from `matchsalldata` as MB
inner join `Round` as R on MB.RoundId = R.RoundId)
order by Submission;

drop view if exists `finalscore`;
create view `finalscore` as
(select R.RoundId, MR.RedUserName as UserName, MR.RedUserScore as Score, TIMESTAMPDIFF(MINUTE, R.CodingStart, MR.RedUserSubmission) as Submission, MR.Winner = "Red" as Winner
from `matchsalldatawinners` as MR
inner join `Round` as R on MR.RoundId = R.RoundId)
union
(select R.RoundId, MB.BlueUserName as UserName, MB.BlueUserScore as Score, TIMESTAMPDIFF(MINUTE, R.CodingStart, MB.BlueUserSubmission) as Submission, MB.Winner = "Blue" as Winner
from `matchsalldatawinners` as MB
inner join `Round` as R on MB.RoundId = R.RoundId)
order by Score DESC, Submission ASC;
