create database if not exists exdb;
use exdb;

create table if not exists User(
    id int auto_increment primary key,
    username varchar(255) not null,
    nativeLanguage varchar(255)
);

create table if not exists Exercise(
    id int auto_increment primary key,
    prompt varchar(255),
    correctAnswer varchar(255),
    difficulty int,
    topic varchar(10),
    CONSTRAINT chk_ex_diff check (difficulty IN (1, 2, 3))
);

create table if not exists Result(
    id int auto_increment primary key,
    userId int,
    exerciseId int,
    givenAnswer varchar(255),
    correct int,
    attemptedAt datetime,
    foreign key (userId) references User(id),
    foreign key (exerciseId) references Exercise(id)
);

-- insert into User(username, nativeLanguage) values ('Messi', 'spanish'), ('David', 'romanian');
-- insert into Exercise(prompt, correctAnswer, difficulty, topic) values
--     ('What is 2+2?',          '4',              1, 'maths'),
--     ('What is 5*3?',          '15',             2, 'maths'),
--     ('What is the integral of x?', 'x^2/2+C',  3, 'maths'),
--     ('Who wrote Luceafarul?', 'Mihai Eminescu', 1, 'romanian'),
--     ('Name a Romanian poet.', 'Mihai Eminescu', 2, 'romanian'),
--     ('What century did Eminescu live in?', '19',3, 'romanian');
-- insert into User(username, nativeLanguage) values ('Ion', 'romanian');
