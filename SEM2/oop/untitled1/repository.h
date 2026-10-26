#pragma once

#include <string>
#include <vector>
#include <sstream>
#include <fstream>
#include "domain.h"

class Repository {

private:
      std::vector<Day> days;
      std::string description;
      int temperature, startH, endH, precipitation;
public:
  Repository();
  ~Repository();
  void loadData();
};