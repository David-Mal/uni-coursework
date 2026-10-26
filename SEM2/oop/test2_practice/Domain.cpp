#include "Domain.h"

#include <iostream>
#include <ostream>

Refrigerator::Refrigerator(std::string id, std::string electricityUsageClass, bool hasFreezer) {
	this->id = id;
	this->electricityUsageClass = electricityUsageClass;
	this->hasFreezer = hasFreezer;
}

std::string Refrigerator::toString() {
	std::string returnedString = "Refrigerator " + id + ", class " + electricityUsageClass + ", ";
	if (hasFreezer)
		returnedString += " with freezer, ";
	returnedString += " with consumption " + std::to_string(consumedElectricity()) + " KW";
	return returnedString;
}

double Refrigerator::consumedElectricity() {
	double consumption = 0;
	int x = 0;
	if (electricityUsageClass == "A")
		x = 3;
	else if (electricityUsageClass == "A++")
		x = 2;
	consumption = x * 30;
	if (hasFreezer)
		consumption += 20;
	return consumption;
}


Dishwasher::Dishwasher(std::string id, double electricityConsumedForOneHour) {
	this->id = id;
	this->electricityConsumedForOneHour = electricityConsumedForOneHour;
}

std::string Dishwasher::toString() {
	std::string returnedString = "Dishwasher " + id + ", with consumption " + std::to_string(consumedElectricity()) + " KW";
	return returnedString;
}

double Dishwasher::consumedElectricity() {
	return electricityConsumedForOneHour * 20;
}
