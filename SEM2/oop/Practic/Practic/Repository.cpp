#include "Repository.h"
#include <algorithm>
int Repository::add(Car obj)
{
    for (auto& it : this->v)
        if (it.getManName() == obj.getManName() and it.getYear() == obj.getYear())
            return 0;
    this->v.push_back(obj);
    return 1;
}

int Repository::remove(Car obj)
{
    int pos = -1;
    for (int i = 0; i < this->v.size() and pos == -1; i++)
        if (this->v[i].getManName() == obj.getManName() and this->v[i].getModel() == obj.getModel() and this->v[i].getColor() == obj.getColor() and this->v[i].getYear() == obj.getYear())
            pos = i;
    if(pos != -1)
        this->v.erase(this->v.begin()+pos);
    return pos != -1;
}

std::vector<Car> Repository::getVector()
{
    return this->v;
}

void Repository::addDefault()
{
    this->v.push_back(Car{ "Dacia", "Logan", "red", 2006 });
    this->v.push_back(Car{ "Dacia", "Logan", "blue", 2007 });
    this->v.push_back(Car{ "Dacia", "Logan", "white", 2008 });
    this->v.push_back(Car{ "Dacia", "Duster", "silver", 2009 });
    this->v.push_back(Car{ "Dacia", "Logan", "black", 2010 });
    this->v.push_back(Car{ "Dacia", "Logan", "red", 2020 });
    this->v.push_back(Car{ "Dacia", "Stepway", "white", 2012 });
    this->v.push_back(Car{ "Dacia", "Logan", "silver", 2016 });
}
