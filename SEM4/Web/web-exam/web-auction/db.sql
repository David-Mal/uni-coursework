create database if not exists auctiondb;
use auctiondb;

create table if not exists Category (
    id int auto_increment primary key,
    name varchar(255) not null
);

create table if not exists Auction (
    id int auto_increment primary key,
    user varchar(255) not null,
    categoryID int not null,
    `description` varchar(255) not null,
    `date` datetime not null,
    foreign key (categoryID) references Category(id)
);

insert into Category(name) values ('books'), ('videogames'), ('music'), ('movies'), ('sports');
insert into Auction(user, categoryID, `description`, `date`) values ('David', 2, 'Ghost of Tsushima', '2025-12-12 15:30:00'), ('Alice', 3,'Hotel California', '2026-03-31 12:00:00'), ('Oscar', 5, 'Football', '2026-04-16 20:15:00'), ('Stacy', 1, 'The picture of Dorian Gray', '2026-10-11 09:45:00');