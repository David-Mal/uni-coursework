#include "UI.h"
#include <iostream>
#include <algorithm>
#define CURRENTYEAR 2025
int UI::add(Car obj)
{
    return this->service.add(obj);
}

int UI::remove(Car obj)
{
    return this->service.remove(obj);
}

void UI::printMenu()
{
    std::cout << "1.Add a new car\n";
    std::cout << "2.Remove a car\n";
    std::cout << "3.Print all\n";
    std::cout << "4.Print Vintage\n";
    std::cout << "0.Exit\n";
}

void UI::printAll()
{
    int k = 1;
    this->service.sortVector(this->service.getVector());

    for (auto it : this->service.getVector())
    {
        std::cout << "Car number. " << k++ << std::endl;
        std::cout << "---Man. NAme: " << it.getManName() << std::endl;
        std::cout << "---Model: " << it.getModel() << std::endl;
        std::cout << "---Year of fab.: " << it.getYear() << std::endl;
        std::cout << "---Color: " << it.getColor() << std::endl;
        std::cout << std::endl;
    }
}

void UI::printVintage()
{
    std::vector<Car> temp;
    for (auto it : this->service.getVector())
    {
        if ( CURRENTYEAR - it.getYear() >= 45)
            temp.push_back(it);
    }

    int k = 0;
    if (temp.empty())
    {
        std::cout << "No such car in the vector\n";
        return;
    }
    this->service.sortVector(temp);
    for (auto it : temp)
    {
        std::cout << "Car number. " << k++ << std::endl;
        std::cout << "---Man. NAme: " << it.getManName() << std::endl;
        std::cout << "---Model: " << it.getModel() << std::endl;
        std::cout << "---Year of fab.: " << it.getYear() << std::endl;
        std::cout << "---Color: " << it.getColor() << std::endl;
        std::cout << std::endl;
    }
}

void UI::addDefault()
{
    this->service.addDefault();
}

Car UI::readCar()
{
    std::string manName, color, model; int year;
    std::cout << "-Enter the man. Name: ";
    std::cin >> manName;
    std::cout << "-Enter the model: ";
    std::cin >> model;
    std::cout << "-Enter the year: ";
    std::cin >> year;
    std::cout << "-Enter the color: ";
    std::cin >> color;
    return Car{ manName, model, color, year };
}

