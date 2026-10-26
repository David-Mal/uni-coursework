#pragma once
#include "Observer.h"
#include <vector>
#include <algorithm>

class Subject {
private:
    std::vector<Observer*> observers;

public:
    virtual ~Subject() = default;

    void addObserver(Observer* o) {
        observers.push_back(o);
    }

    void removeObserver(Observer* o) {
        observers.erase(std::remove(observers.begin(), observers.end(), o), observers.end());
    }

protected:
    void notifyObservers() {
        for (auto o : observers)
            o->update();
    }
};
