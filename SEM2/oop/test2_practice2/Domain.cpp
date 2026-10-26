#include "Domain.h"
#include <iostream>

NeonatalUnit::NeonatalUnit(std::string hospitalName, int numberOfDoctors, double averageGrade) {
    this->hospitalName = hospitalName;
    this->numberOfDoctors = numberOfDoctors;
    this->averageGrade = averageGrade;
}

std::string NeonatalUnit::toString() {
    std::string returnedString = "Neonatal unit department in " + hospitalName + ", with " + std::to_string(numberOfDoctors) + " doctors, " +
        "and average grade for newborns " + std::to_string(averageGrade);
    return returnedString;
}

bool NeonatalUnit::isEfficient() {
    if (averageGrade > 8.5)
        return true;
    return false;
}

Surgery::Surgery(std::string hospitalName, int numberOfDoctors, int numberOfPatients) {
    this->hospitalName = hospitalName;
    this->numberOfDoctors = numberOfDoctors;
    this->numberOfPatients = numberOfPatients;
}

std::string Surgery::toString() {
    std::string returnedString = "Surgery department in " + hospitalName + ", with " + std::to_string(numberOfDoctors) + " doctors and " +
                                 std::to_string(numberOfPatients) + " patients";
    return returnedString;
}

bool Surgery::isEfficient() {
    if (numberOfPatients / numberOfDoctors >= 2)
        return true;
    return false;
}