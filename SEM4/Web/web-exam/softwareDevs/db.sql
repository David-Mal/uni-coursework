create database if not exists devs;
use devs;

create table if not exists SoftwareDeveloper (
    id int auto_increment primary key,
    name varchar(255) not null,
    age int not null,
    skills varchar(255) not null
);

create table if not exists Project (
    id int auto_increment primary key,
    ProjectManagerID int,
    name varchar(255),
    description varchar(255),
    members varchar(255),
    foreign key (ProjectManagerID) references SoftwareDeveloper(id)
);

insert ignore into SoftwareDeveloper(name, age, skills) values
('Messi', 39, 'dribling, body-feints, free-kicks, finesse shots, through passes'),
('CR7', 41, 'ronaldo chop, fake-shots, long-shots, power-shots, bicycle kicks, penalty'),
('Yamal', 18, 'trivela, dribling, finesse shots, creativity'),
('Mbappe', 28, 'speed, sprints, power shots, finesse shots, penalty, dribling'),
('Neymar', 34, 'dribling, hocus-pocus, sombrero, step-overs, ginga, joga bonito'),
('Ronaldinho', 53, 'ranibow flick, elastico, heel to ball, no look pass, roulette, rabona, joga bonito');

insert ignore into  Project(ProjectManagerID, name, description, members) values
(1, 'Barcelona', 'best team in the world', 'Messi,Yamal,Neymar,Ronaldinho'),
(2, 'Real Madrid', '15 ucl', 'CR7,Mbappe'),
(3, 'WC2026', 'world cup', 'Yamal,Mbappe,Messi,CR7,Neymar'),
(6, 'Brazil', '5 wc trophies', 'Ronaldinho,Neymar');