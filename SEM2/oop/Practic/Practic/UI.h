#pragma once
#include "Service.h"
class UI
{
	Service service;

public:
	int add(Car obj);
	int remove(Car obj);
	void printMenu();
	void printAll();
	void printVintage();
	void addDefault();
	Car readCar();
};

