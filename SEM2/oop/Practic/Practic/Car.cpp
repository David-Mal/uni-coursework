#include "Car.h"

Car::Car(std::string manName, std::string model, std::string color, int year): manName{manName}, model{model}, color{color}, year{year} {}

std::string Car::getManName() noexcept
{
	return this->manName;
}

std::string Car::getModel() noexcept
{
	return this->model;
}

std::string Car::getColor() noexcept
{
	return this->color;
}

int Car::getYear() noexcept
{
	return this->year;
}
