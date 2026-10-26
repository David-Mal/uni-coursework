#include "Controller.h"
#include <fstream>
#include <algorithm>


void Controller :: addDepartment(HospitalDepartment* department) {
    data.push_back(department);
}

std::vector<HospitalDepartment*>& Controller :: getAllDepartments() {
    return data;
}

std::vector<HospitalDepartment*> Controller :: getAllEfficientDepartments() const {
    std::vector<HospitalDepartment*> result;
    for (auto department : data) {
        if (department->isEfficient())
            result.push_back(department);
    }
    return result;
}

void Controller :: writeToFile(std::string filename) const {
    auto departments = getAllEfficientDepartments();
    std::sort(departments.begin(), departments.end(), [](HospitalDepartment* a, HospitalDepartment* b) {return a->getHospitalName() < b->getHospitalName();});
    std::ofstream file(filename);
    for (auto department : departments) {
        file << department->toString() << '\n';
    }
    file.close();
}

