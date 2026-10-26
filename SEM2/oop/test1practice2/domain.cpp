#include "domain.h"
#include <string>

Patient::Patient(std::string name, std::string status, int age, int room): name{name}, status{status}, age{age}, room{room} {}

std::string Patient::getName() {
    return this->name;
}

std::string Patient::getStatus() {
    return this->status;
}

int Patient::getAge() {
    return this->age;
}

int Patient::getRoom() {
    return this->room;
}