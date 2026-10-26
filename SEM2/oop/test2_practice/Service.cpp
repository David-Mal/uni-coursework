#include "Service.h"
#include <fstream>
#include <algorithm>

void Service::addAppliance(Appliance* appliance) {
	data.push_back(appliance);
}

std::vector<Appliance*>& Service::getAllAppliances() {
	return data;
}

std::vector<Appliance*> Service::getAllWithConsumedElectricityLessThan(double maxElectricity) const {
	std::vector<Appliance*> result;
	for (auto appliance : data) {
		if (appliance->consumedElectricity() > maxElectricity)
			result.push_back(appliance);
	}
	return result;
}

std::vector<Appliance*> Service::getAllWithConsumedElectricityMoreThan(double minElectricity) const {
	std::vector<Appliance*> result;
	for (auto appliance : data) {
		if (appliance->consumedElectricity() < minElectricity)
			result.push_back(appliance);
	}
	return result;
}

void Service::writeToFile(std::string filename, double maxElectricity) const {
	auto appliances = getAllWithConsumedElectricityMoreThan(maxElectricity);
	std::sort(appliances.begin(), appliances.end(), [](Appliance* a, Appliance* b) {return a->getId() < b->getId();});
	std::ofstream file(filename);
	for (auto appliance : appliances) {
		file << appliance->toString() << '\n';
	}
	file.close();
}