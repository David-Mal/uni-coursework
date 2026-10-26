create database if not exists hotel;
use hotel;

create table User(
    id int auto_increment primary key,
    username varchar(255) not null unique,
    password varchar(255) not null
);

create table HotelRoom(
    id int auto_increment primary key,
    roomNumber varchar(50) not null unique,
    capacity int not null,
    basePrice int not null
);

create table Reservation(
    id int auto_increment primary key,
    userId int not null,
    roomId int not null,
    checkInDate date not null,
    checkOutDate date not null,
    numberOfGuests int not null,
    totalPrice int not null,
    foreign key (userId) references User(id),
    foreign key (roomId) references HotelRoom(id)
);

insert into User (username, password) values ('David', '1'), ('Alice', '1'), ('Bob', '1');
insert into HotelRoom (roomNumber, capacity, basePrice) values ('101', 2, 100), ('102', 2, 100), ('103', 2, 100);
insert into Reservation (userId, roomId, checkInDate, checkOutDate, numberOfGuests, totalPrice) values 
(1, 1, '2024-07-01', '2024-07-05', 2, 400),
(2, 2, '2024-07-10', '2024-07-15', 2, 500),
(3, 3, '2024-07-20', '2024-07-25', 2, 500);
