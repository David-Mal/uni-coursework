#pragma once
#include <string>
class Car
{
	std::string manName, model, color;
	int year;

public:
	Car(std::string manName, std::string model, std::string color, int year);
	std::string getManName() noexcept;
	std::string getModel() noexcept;
	std::string getColor() noexcept;
	int getYear() noexcept;
};

