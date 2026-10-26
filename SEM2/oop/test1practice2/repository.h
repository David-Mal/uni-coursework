#pragma once
#include <vector>

class Repository {
    std::vector<Patient> v;
public:
    int addPatient(Patient p);
    int removePatient(std::string name);
    std::vector<Patient> getPatients();
    void addDefault();
};