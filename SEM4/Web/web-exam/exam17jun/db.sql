create database if not exists recipedb;
use recipedb;

create table if not exists Users(
    id int auto_increment primary key,
    username varchar(255) not null
);

create table if not exists Ingredient(
    id int auto_increment primary key,
    name varchar(255) not null,
    unit varchar(255),
    caloriesPer100g int
);
-- DAIRY-Butter, GRAIN-Flour, FOOD_GROUP-Ingredient

create table if not exists Recipe(
    id int auto_increment primary key,
    userId int not null,
    title varchar(255),
    totalCalories int,
    foreign key (userId) references Users(id)
);

create table if not exists RecipeStep(
    id int auto_increment primary key,
    recipeId int not null,
    stepNumber int,
    description varchar(255),
    ingredientIds varchar(255) not null, 
-- contains a comma separated list of ingredients Ids used in this step
    foreign key (recipeId) references Recipe(id)
);

insert into Users(username) values ('Messi'), ('David'), ('Yamal');
insert into Ingredient(name, unit, caloriesPer100g) values
('PROTEIN-Beef', 'kg', 280), 
('PROTEIN-Chicken', 'kg', 150),
('PROTEIN-Fish', 'kg', 150),
('DAIRY-Milk', 'l', 120),
('DAIRY-Cheese', 'g', 120),
('DAIRY-Butter', 'g', 200),
('GRAIN-Flour', 'g', 135),
('VEGETABLE-Ttomato', 'kg', 60),
('VEGETABLE-Cucumber', 'kg', 30),
('FRUIT-Apple', 'kg', 45),
('FRUIT-Banana', 'kg', 70),
('GRAIN-Rice', 'g', 75),
('DAIRY-Yogurt', 'g', 100),
('PROTEIN-Egg', 'g', 46),
('VEGETABLE-Onion', 'kg', 40),
('VEGETABLE-Carrot', 'kg', 25),
('VEGETABLE-Cabbage', 'kg', 90),
('VEGETABLE-Pepper', 'kg', 60),
('FRUIT-Orange', 'g', 38);

