#include "domain.h"
#include <string>

Weather::Weather(int start, int end, int temp, int precipitation, std::string description)
    : start(start), end(end), temp(temp), precipitation(precipitation), description(description) {}

Weather::~Weather() {}

int Weather::getStart() const { return start; }
int Weather::getEnd() const { return end; }
int Weather::getTemp() const { return temp; }
int Weather::getPrecipitation() const { return precipitation; }
std::string Weather::getDescription() const { return description; }

std::string Weather::toString() const {
    return std::to_string(start) + " <-> " + std::to_string(end) + "  |  " +
           std::to_string(temp) + "ˇ C  | " + std::to_string(precipitation) +
           "% |  " + description;
}