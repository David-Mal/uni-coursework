#include "Repository.h"
#include <fstream>
#include <sstream>
#include <stdexcept>
#include <cmath>

void Repository::loadPersons(const string& filename) {
    persons.clear();
    ifstream in(filename);
    string line;

    while (getline(in, line)) {
        istringstream iss(line);
        string name, latStr, lonStr, orgStr;

        getline(iss, name, '|');
        getline(iss, latStr, '|');
        getline(iss, lonStr, '|');
        getline(iss, orgStr, '|');

        double lat = stod(latStr);
        double lon = stod(lonStr);
        bool isOrg = (orgStr == "1");

        persons.emplace_back(name, lat, lon, isOrg);
    }

    in.close();
}

void Repository::loadEvents(const string& filename) {
    events.clear();
    ifstream in(filename);
    string line;

    while (getline(in, line)) {
        istringstream iss(line);
        string organiser, name, description, latStr, lonStr, date;

        getline(iss, organiser, '|');
        getline(iss, name, '|');
        getline(iss, description, '|');
        getline(iss, latStr, '|');
        getline(iss, lonStr, '|');
        getline(iss, date, '|');

        double lat = stod(latStr);
        double lon = stod(lonStr);

        Event e(organiser, name, description, lat, lon, date);

        string attendee;
        while (getline(iss, attendee, ',')) {
            e.addAttendee(attendee);
        }

        events.push_back(e);
    }

    in.close();
}

void Repository::saveEvents(const string& filename) const {
    ofstream out(filename);

    for (const auto& e : events) {
        out << e.getOrganiser() << "|"
            << e.getName() << "|"
            << e.getDescription() << "|"
            << e.getLatitude() << "|"
            << e.getLongitude() << "|"
            << e.getDate();

        for (const auto& attendee : e.getAttending())
            out << "|" << attendee;

        out << "\n";
    }

    out.close();
}

const vector<Person>& Repository::getAllPersons() const {
    return persons;
}

const vector<Event>& Repository::getAllEvents() const {
    return events;
}

Person Repository::getPersonByName(const string& name) const {
    for (const auto& p : persons)
        if (p.getName() == name)
            return p;
    throw runtime_error("Person not found: " + name);
}

Event& Repository::getEventByNameAndLocation(const string& name, double lat, double lon) {
    for (auto& e : events)
        if (e.getName() == name &&
            abs(e.getLatitude() - lat) < 1e-6 &&
            abs(e.getLongitude() - lon) < 1e-6)
            return e;
    throw runtime_error("Event not found.");
}

void Repository::addEvent(const Event& e) {
    for (const auto& ev : events)
        if (ev.getName() == e.getName() &&
            abs(ev.getLatitude() - e.getLatitude()) < 1e-6 &&
            abs(ev.getLongitude() - e.getLongitude()) < 1e-6)
            throw runtime_error("Event with same name and location exists.");
    events.push_back(e);
}

void Repository::updateEvent(const string& name, double lat, double lon, const string& newDesc, const string& newDate) {
    Event& e = getEventByNameAndLocation(name, lat, lon);
    e.setDescription(newDesc);
    e.setDate(newDate);
}

void Repository::addAttendee(const string& eventName, double lat, double lon, const string& personName) {
    Event& e = getEventByNameAndLocation(eventName, lat, lon);
    e.addAttendee(personName);
}