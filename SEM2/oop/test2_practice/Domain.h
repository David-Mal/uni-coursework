#ifndef DOMAIN_H
#define DOMAIN_H

#include <string>

class Appliance {
protected:
	std::string id;

public:
	const std::string& getId() { return id; }
	virtual double consumedElectricity() = 0;
	virtual std::string toString() = 0;
	virtual ~Appliance() = default;
};


class Refrigerator : public Appliance {
private:
	std::string electricityUsageClass;
	bool hasFreezer = false;

public:
	Refrigerator(std::string id, std::string electricityUsageClass, bool hasFreezer);
	double consumedElectricity() override;
	std::string toString() override;
};


class Dishwasher : public Appliance {
private:
	double electricityConsumedForOneHour = 0;

public:
	Dishwasher(std::string id, double electricityConsumedForOneHour);
	double consumedElectricity() override;
	std::string toString() override;
};

#endif
