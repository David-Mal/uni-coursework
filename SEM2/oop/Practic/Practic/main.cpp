#include <iostream>
#include <vector>
#include <string>
#include "UI.h"

int main()
{
	UI ui;
	ui.addDefault();
	bool running = true;
	while (running)
	{
		ui.printMenu();
		char opt;
		std::cout << ">";
		std::cin >> opt;
		switch (opt)
		{
		case '0':
			running = false;
			break;
		case '1':
		{
			Car temp = ui.readCar();
			if (ui.add(temp))
				std::cout << "Car added successfully\n";
			else
				std::cout << "This car already exists\n";
			break;
		}
		case '2':
		{
			Car temp = ui.readCar();
			if (ui.remove(temp))
				std::cout << "Car removed\n";
			else
				std::cout << "This does not exist\n";
			break;
		}
		case '3':
			ui.printAll();
			break;
		case '4':
			ui.printVintage();
			break;
		default:
			std::cout << "Invalid option\n";
			break;
		}
	}

}