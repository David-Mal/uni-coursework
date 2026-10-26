//
// Created by mariasiu alexandra on 14.06.2025.
//

#ifndef SERVICE_H
#define SERVICE_H

#include "Repository.h"
#include "Subject.h"

class Service : public Subject {
private:
    Repository& repo;

public:
    Service(Repository& r);

    const vector<Person>& getAllPersons() const;
    const vector<Event>& getAllEvents() const;

    void addEvent(const string& organiser, const string& name, const string& description,
                  double lat, double lon, const string& date);

    void updateEvent(const string& name, double lat, double lon,
                     const string& newDesc, const string& newDate);

    void attendEvent(const string& eventName, double lat, double lon, const string& personName);

    vector<Event> getEventsNear(const string& personName, double maxDistance) const;
    vector<Event> getPopularEvents(int minAttendees) const;

    void saveEventsToFile(const string& filename) const;
    const Event& getEventByNameAndLocation(const string& name, double lat, double lon) const;
};

#endif // SERVICE_H