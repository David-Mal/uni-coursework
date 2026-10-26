#ifndef UI_H
#define UI_H

#include "Service.h"

class UI {
private:
	Service service;

public:
	void start();
	void printMenu();
	void addAppliance();
	void showAppliances();
	void showInefficient();
	void writeToFile() const;
};

#endif
