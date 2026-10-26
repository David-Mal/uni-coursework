#include "Domain.h"
#include "algorithm"

// Event methods
Event::Event(const string& organiser, const string& name, const string& description,
             double latitude, double longitude, const string& date)
    : organiser(organiser), name(name), description(description),
      latitude(latitude), longitude(longitude), date(date) {}

string Event::getOrganiser() const { return organiser; }
string Event::getName() const { return name; }
string Event::getDescription() const { return description; }
double Event::getLatitude() const { return latitude; }
double Event::getLongitude() const { return longitude; }
string Event::getDate() const { return date; }
const vector<string>& Event::getAttending() const { return attending; }

void Event::setDescription(const string& newDescription) { description = newDescription; }
void Event::setDate(const string& newDate) { date = newDate; }
void Event::addAttendee(const string& personName) {
    if (find(attending.begin(), attending.end(), personName) == attending.end())
        attending.push_back(personName);
}

// Person methods
Person::Person(const string& name, double latitude, double longitude, bool isOrganiser)
    : name(name), latitude(latitude), longitude(longitude), isOrganiser(isOrganiser) {}

string Person::getName() const { return name; }
double Person::getLatitude() const { return latitude; }
double Person::getLongitude() const { return longitude; }
bool Person::getIsOrganiser() const { return isOrganiser; }