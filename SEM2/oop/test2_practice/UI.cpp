#include "UI.h"
#include <iostream>

void UI::start() {
	Refrigerator* a1 = new Refrigerator("B11", "A", 1);
	Dishwasher* a2 = new Dishwasher("A63", 2);
	Dishwasher* a3 = new Dishwasher("A32", 50);
	service.addAppliance(a1);
	service.addAppliance(a2);
	service.addAppliance(a3);

	std::cout << "Welcome!" << std::endl;
	while (true) {
		printMenu();
		int option;
		std::cin >> option;
		switch (option) {
			case 1:
				addAppliance();
				break;

			case 2:
				showAppliances();
				break;

			case 3:
				showInefficient();
				break;

			case 4:
				writeToFile();
				break;

			default:
				std::cout << "Invalid option!" << std::endl;
		}
	}
}

void UI::addAppliance() {
	std::cout << "What kind of appliance?" << std::endl;
	std::cout << "1. Refrigerator." << std::endl;
	std::cout << "2. Dishwasher." << std::endl;
	std::cout << "\nYour option is: >";
	int option;
	std::string id, usageClass, buffer;
	double consumption;
	bool hasFreezer = true;
	std::cin >> option;
	std::cin.ignore();
	switch (option) {
		case 1: {
			std::cout << "Please enter the ID: >";
			std::getline(std::cin, id);
			//std::cout << "ID " << id << std::endl;

			std::cout << "Please enter the usage class: >";
			std::getline(std::cin, usageClass);

			if (usageClass != "A" and usageClass != "A++") {
				std::cout << "Invalid usage class!" << std::endl;
				return;
			}

			std::cout << "Does it have a freezer? (Y/N) >";
			std::getline(std::cin, buffer);
			if (buffer == "Y" or buffer == "y")
				hasFreezer = true;
			else if (buffer == "N" or buffer == "n")
				hasFreezer = false;
			else {
				std::cout << "Invalid option!" << std::endl;
				return;
			}

			Refrigerator* refrigerator = new Refrigerator(id, usageClass, hasFreezer);
			service.addAppliance(refrigerator);
			break;
		}

		case 2: {
			std::cout << "Please enter the ID: >";
			std::getline(std::cin, id);

			std::cout << "Please enter the consumed electricity per hour: >";
			std::cin >> consumption;

			Dishwasher* dishwasher = new Dishwasher(id, consumption);
			service.addAppliance(dishwasher);
			break;
		}

		default:
			std::cout << "Invalid option!" << std::endl;
	}
}

void UI::showAppliances() {
	std::vector<Appliance*> appliances = service.getAllAppliances();
	int index = 0;
	for (auto& appliance : appliances) {
		std::cout << ++index << ". " << appliance->toString() << std::endl;
	}
}

void UI::showInefficient() {
	std::vector<Appliance*> appliances = service.getAllWithConsumedElectricityLessThan(100);
	int index = 0;
	for (auto& appliance : appliances) {
		std::cout << ++index << ". " << appliance->toString() << std::endl;
	}
}

void UI::writeToFile() const {
	std::string filename;
	double maxElectricity;
	std::cin.ignore();
	std::cout << "Please enter the file name: >";
	std::getline(std::cin, filename);
	std::cout << "Enter the max electricity: >" << std::endl;
	std::cin >> maxElectricity;

	service.writeToFile(filename, maxElectricity);
}

void UI::printMenu() {
	std::cout << "\nPlease choose an option from below:" << std::endl;
	std::cout << "1. Add a new appliance." << std::endl;
	std::cout << "2. Show all appliances." << std::endl;
	std::cout << "3. Show all inefficient appliances." << std::endl;
	std::cout << "4. Save to file." << std::endl;
	std::cout << "\nYour option is: >";
}