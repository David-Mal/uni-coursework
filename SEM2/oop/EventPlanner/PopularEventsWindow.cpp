//
// Created by mariasiu alexandra on 15.06.2025.
//

#include "PopularEventsWindow.h"
#include <QVBoxLayout>
#include <algorithm>

PopularEventsWindow::PopularEventsWindow(Service& service)
    : service(service) {
    this->setWindowTitle("Popular Events");
    QVBoxLayout* layout = new QVBoxLayout(this);

    eventList = new QListWidget;
    layout->addWidget(eventList);

    service.addObserver(this);
    populate();
}

PopularEventsWindow::~PopularEventsWindow() {
    service.removeObserver(this);
}

void PopularEventsWindow::populate() {
    eventList->clear();
    auto events = service.getAllEvents();

    std::sort(events.begin(), events.end(), [](const Event& a, const Event& b) {
        return b.getAttending().size() < a.getAttending().size(); // Descending
    });

    for (const auto& e : events) {
        QString text = QString::fromStdString(
            "Name: " + e.getName() +
            " | Description: " + e.getDescription() +
            " | Location: (" + std::to_string(e.getLatitude()) + ", " + std::to_string(e.getLongitude()) + ")" +
            " | Date: " + e.getDate() +
            " | Attendees: " + std::to_string(e.getAttending().size())
        );
        eventList->addItem(text);
    }
}

void PopularEventsWindow::update() {
    populate();
}