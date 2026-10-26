#pragma once
#include <string>
#include <sstream>
#include <fstream>

class Day {
private:
      int startH, endH, temperature, precipitation;
      std::string description;

public:
      Day(int startH, int endH, int temperature, std::string): startH{startH}, endH{endH}, temperature{temperature} {};
      ~Day(){};
      int getStartHour() { return startH; }
      int getEndHour() { return endH; }
      int getTemperature() { return temperature; }
      std::string getDescription() { return description; }
      std::string toString() {
              std::string output = std::to_string(getStartHour()) + ";" + std::to_string(getEndHour()) + ";" + std::to_string(temperature) + ";" + description + "\n";
              return output;
      }
};