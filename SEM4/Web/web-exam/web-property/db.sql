create database if not exists db_property;
use db_property;

create table if not exists User (
    id int auto_increment primary key,
    username varchar(255) not null unique,
    secretQuestion varchar(255) not null,
    secretAnswer varchar(255) not null
);

create table if not exists Property (
    id int auto_increment primary key,
    address varchar(255) not null,
    description varchar(255) not null  
);

create table if not exists UserToProperties (
    id int auto_increment primary key,
    idUser int not null,
    idProperty int not null,
    foreign key (idUser) references User(id),
    foreign key (idProperty) references Propery(id)
);

insert into User(username, secretQuestion, secretAnswer) values ('Messi', 'What is your wifes name?', 'Antonella'), ('David', 'What is your bench press pr in kg?', '110');
insert into Property(address, description) values ('Barcelona', 'sagrada famillia, camp nou, beaches'), ('Cluj', 'Marasti, Manasturi, Zorilor, Gheorgheni'), ('Paris', 'Eiffel Tower, Versailles, Louvre, Champs-Delysees, Notre Damme'), ('London', 'Big Ben, Buckingham Palace, Westminister Abbey, British Museum'), ('Rome', 'Colosseum, Pantheon, Trevi Fountain, Roman forum, Sistine Chapel, Vatican'), ('New York', 'central park, times square, gramercy, statue of liberty, Empire State, Brooklyn Bridge');
insert into UserToProperties(idUser, idProperty) values (1, 1), (1, 3), (2, 1), (2, 2), (2, 3);