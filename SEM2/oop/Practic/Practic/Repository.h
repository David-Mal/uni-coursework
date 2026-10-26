#pragma once
#include <vector>
#include "Car.h"
class Repository
{
	std::vector<Car> v;
public:
	int add(Car obj);
	int remove(Car obj);
	std::vector<Car> getVector();
	void addDefault();
};

