#ifndef SERVICE_H
#define SERVICE_H

#include "Domain.h"
#include <vector>

class Service {
private:
	std::vector<Appliance*> data;

public:
	void addAppliance(Appliance* appliance);
	std::vector<Appliance*>& getAllAppliances();
	std::vector<Appliance*> getAllWithConsumedElectricityLessThan(double maxElectricity) const;
	std::vector<Appliance*> getAllWithConsumedElectricityMoreThan(double minElectricity) const;
	void writeToFile(std::string filename, double maxElectricity) const;
};

#endif
