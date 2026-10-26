#ifndef CONTROLLER_H
#define CONTROLLER_H

#include "Domain.h"
#include <vector>

class Controller {

private:
    std::vector<HospitalDepartment*> data;

public:
    void addDepartment(HospitalDepartment* department);
    std::vector<HospitalDepartment*>& getAllDepartments();
    std::vector<HospitalDepartment*> getAllEfficientDepartments() const;
    void writeToFile(std::string filename) const;
};



#endif
