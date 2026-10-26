#pragma once
#include "Domain.h"
#include <vector>
#include <string>
using namespace std;

class Repository {
private:
    vector<Person> persons;
    vector<Event> events;

public:
    void loadPersons(const string& filename);
    void loadEvents(const string& filename);
    void saveEvents(const string& filename) const;

    const vector<Person>& getAllPersons() const;
    const vector<Event>& getAllEvents() const;

    Person getPersonByName(const string& name) const;
    Event& getEventByNameAndLocation(const string& name, double lat, double lon);

    void addEvent(const Event& e);
    void updateEvent(const string& name, double lat, double lon, const string& newDesc, const string& newDate);
    void addAttendee(const string& eventName, double lat, double lon, const string& personName);
};