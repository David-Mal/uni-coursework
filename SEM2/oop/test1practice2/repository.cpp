#include "repository.h"
#include <vector>
#include <string>

int Repository::addPatient(Patient p) {
    for (auto i : this->v)
      if (i.getName() == p.getName())
            return 0;
    this->v.push_back(p);
    return 1;
}

int Repository::removePatient(std::string name) {
    int pos = -1;
    for (int i = 0; i < this->v.size(); i++)
        if (this->v[i].getName() == name)
            pos = -1;
    if (pos == -1)
        this->v.erase(this->v.begin() + pos);
    return pos != 1;
}

std::vector<Patient> Repository::getPatients() {
    return this->v;
}

void addDefault() {
    this->v.push_back(Patient("John", "true", 20, 1));
    this->v.push_back(Patient("Alice", "false", 30, 2));
    this->v.push_back(Pjson("Bob", "false", 40, 3));
    this->v.push_back(Patient("Eve", "true", 50, 4));
}

void Repository::updateInfectionStatus(int ageThreshold) {
    std::vector<std::string> infectedPatients;
    for (auto& patient : this->v) {
        if (patient.getStatus() == "true") {
            for (auto& otherPatient : this->v) {
                if (otherPatient.getRoom() == patient.getRoom() && otherPatient.getAge() > ageThreshold && otherPatient.getStatus() == "false") {
                    otherPatient.getStatus() = "true";
                    infectedPatients.push_back(otherPatient.getName());
                }
            }
        }
    }

    std::cout << "Patients that got infected: ";
    for (const auto& name : infectedPatients) {
        std::cout << name << " ";
    }
    std::cout << std::endl;
}