#pragma once
#include <string>
#include <vector>
using namespace std;

class Event {
private:
    string organiser;
    string name;
    string description;
    double latitude;
    double longitude;
    string date;
    vector<string> attending;

public:
    Event(const string& organiser, const string& name, const string& description,
          double latitude, double longitude, const string& date);

    string getOrganiser() const;
    string getName() const;
    string getDescription() const;
    double getLatitude() const;
    double getLongitude() const;
    string getDate() const;
    const vector<string>& getAttending() const;

    void setDescription(const string& newDescription);
    void setDate(const string& newDate);
    void addAttendee(const string& personName);
};

class Person {
private:
    string name;
    double latitude;
    double longitude;
    bool isOrganiser;

public:
    Person(const string& name, double latitude, double longitude, bool isOrganiser);

    string getName() const;
    double getLatitude() const;
    double getLongitude() const;
    bool getIsOrganiser() const;
};