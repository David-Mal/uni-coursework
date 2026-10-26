#pragma once
#include "Repository.h"
class Service
{
	Repository repo;
public:
	int add(Car obj);
	int remove(Car obj);
	std::vector<Car> getVector();
	void sortVector(std::vector<Car> v);
	void addDefault();
};

