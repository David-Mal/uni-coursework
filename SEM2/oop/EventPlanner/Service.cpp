#include "Service.h"
#include <cmath>
#include <stdexcept>

Service::Service(Repository& r) : repo(r) {}

const vector<Person>& Service::getAllPersons() const {
    return repo.getAllPersons();
}

const vector<Event>& Service::getAllEvents() const {
    return repo.getAllEvents();
}

void Service::addEvent(const string& organiser, const string& name, const string& description,
                       double lat, double lon, const string& date) {
    Event e(organiser, name, description, lat, lon, date);
    repo.addEvent(e);
    notifyObservers();
}

void Service::updateEvent(const string& name, double lat, double lon,
                          const string& newDesc, const string& newDate) {
    repo.updateEvent(name, lat, lon, newDesc, newDate);
    notifyObservers();
}

void Service::attendEvent(const string& eventName, double lat, double lon, const string& personName) {
    repo.addAttendee(eventName, lat, lon, personName);
    notifyObservers();
}

vector<Event> Service::getEventsNear(const string& personName, double maxDistance) const {
    Person p = repo.getPersonByName(personName);
    vector<Event> result;

    for (const auto& e : repo.getAllEvents()) {
        double dlat = p.getLatitude() - e.getLatitude();
        double dlon = p.getLongitude() - e.getLongitude();
        double dist = sqrt(dlat * dlat + dlon * dlon);
        if (dist <= maxDistance)
            result.push_back(e);
    }

    return result;
}

vector<Event> Service::getPopularEvents(int minAttendees) const {
    vector<Event> result;

    for (const auto& e : repo.getAllEvents())
        if ((int)e.getAttending().size() >= minAttendees)
            result.push_back(e);

    return result;
}

void Service::saveEventsToFile(const string& filename) const {
    repo.saveEvents(filename);
}
const Event& Service::getEventByNameAndLocation(const string& name, double lat, double lon) const {
    return repo.getEventByNameAndLocation(name, lat, lon);
}