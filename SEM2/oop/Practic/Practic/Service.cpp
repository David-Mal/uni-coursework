#include "Service.h"

int Service::add(Car obj)
{
    return this->repo.add(obj);
}

int Service::remove(Car obj)
{
    return this->repo.remove(obj);
}

std::vector<Car> Service::getVector()
{
    return this->repo.getVector();
}

void Service::sortVector(std::vector<Car> v)
{
    for (int i = 0; i < v.size() - 1; i++)
        for (int j = i + 1; j < v.size(); j++)
            if (v[i].getManName() > v[j].getManName())
                std::swap(v[i], v[j]);
            else if (v[i].getManName() == v[j].getManName())
                if (v[i].getModel() > v[j].getModel())
                    std::swap(v[i], v[j]);
}

void Service::addDefault()
{
    this->repo.addDefault();
}
