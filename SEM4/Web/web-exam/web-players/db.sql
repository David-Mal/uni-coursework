create database if not exists SportsDB;
use SportsDB;
create table if not exists Player(
    id int auto_increment primary key,
    name varchar(255) not null,
    position varchar(255) not null
);

create table if not exists Team(
    id int auto_increment primary key,
    name varchar(255) not null,
    homeCity varchar(255) not null
);

create table if not exists TeamMembers(
    id int auto_increment primary key,
    idPlayer1 int not null,
    idPlayer2 int not null,
    idTeam int not null,
    foreign key (idPlayer1) references Player(id),
    foreign key (idPlayer2) references Player(id),
    foreign key (idTeam) references Team(id)
);

insert into Player(name, position) values
('John Doe', 'Forward'),
('Jane Smith', 'Midfielder'),
('Bob Johnson', 'Defender'),
('Alice Brown', 'Goalkeeper'),
('Charlie Davis', 'Forward');

insert into Team(name, homeCity) values
('The Eagles', 'New York'),
('The Sharks', 'Los Angeles'),
('The Tigers', 'Chicago');

insert into TeamMembers(idPlayer1, idPlayer2, idTeam) values
(1, 2, 1),
(2, 3, 2),
(1, 3, 3);