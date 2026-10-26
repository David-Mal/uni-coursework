create database if not exists filesdb;
use filesdb;

create table if not exists Users(
    id int auto_increment primary key,
    username varchar(255) not null unique,
    password varchar(255) not null
);

create table if not exists Files(
    id int auto_increment primary key,
    userid int not null,
    filename varchar(255) not null,
    filepath varchar(255) not null,
    size int not null,
    foreign key (userid) references Users(id)
);

insert into Users(username, password) values ('David', '123'), ('Messi', '123'), ('Yamal', '123');
insert into Files (userid, filename, filepath, size) values
(2, 'contract_inchiriere.pdf', '/storage/docs/contract_inchiriere.pdf', 1048576),
(2, 'note_sedinta.txt', '/storage/text/note_sedinta.txt', 1536),
(2, 'poza_buletin.jpg', '/storage/images/poza_buletin.jpg', 256000),
(3, 'raport_vanzari_2026.xlsx', '/storage/reports/raport_vanzari_2026.xlsx', 512000),
(1, 'prezentare_proiect.pptx', '/storage/presentations/prezentare_proiect.pptx', 2097152),
(1, 'note_sedinta.txt', '/storage/text/note_sedinta.txt', 1536),
(2, 'note_sedinta.txt', '/storage/text/note_sedinta.txt', 1536),
(3, 'raport_vanzari_2026.xlsx', '/storage/reports/raport_vanzari_2026.xlsx', 512000);


