#pragma once
#include <string>

class Weather {
private:
    int start, end, temp, precipitation;
    std::string description;

public:
    Weather(int start, int end, int temp, int precipitation, std::string description);
    ~Weather();

    int getStart() const;
    int getEnd() const;
    int getTemp() const;
    int getPrecipitation() const;
    std::string getDescription() const;

    std::string toString() const;
};