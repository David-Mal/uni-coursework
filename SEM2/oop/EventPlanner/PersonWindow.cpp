#include "PersonWindow.h"
#include <QVBoxLayout>
#include <QFormLayout>
#include <QMessageBox>
#include <QLineEdit>
#include <cmath>
#include <algorithm>

PersonWindow::PersonWindow(Service& service, const Person& person)
    : service(service), me(person) {
    setupUI();
    connectSignals();
    refreshEventList();
}

void PersonWindow::setupUI() {
    this->setWindowTitle(QString::fromStdString(me.getName()));
    this->resize(700, 400);

    QVBoxLayout* layout = new QVBoxLayout(this);

    locationLabel = new QLabel(QString("Location: (%1, %2)")
        .arg(me.getLatitude()).arg(me.getLongitude()));
    layout->addWidget(locationLabel);

    nearbyCheck = new QCheckBox("Show only nearby events (≤ 5 units)");
    layout->addWidget(nearbyCheck);

    eventList = new QListWidget;
    layout->addWidget(eventList);

    // Organiser-only: Add Event
    if (true) {
        auto* form = new QFormLayout;

        nameEdit = new QLineEdit;
        descEdit = new QLineEdit;
        latEdit = new QLineEdit;
        lonEdit = new QLineEdit;
        dateEdit = new QLineEdit;
        addButton = new QPushButton("Add Event");

        form->addRow("Name:", nameEdit);
        form->addRow("Description:", descEdit);
        form->addRow("Latitude:", latEdit);
        form->addRow("Longitude:", lonEdit);
        form->addRow("Date:", dateEdit);
        layout->addLayout(form);
        layout->addWidget(addButton);
    }
}

void PersonWindow::connectSignals() {
    connect(nearbyCheck, &QCheckBox::stateChanged, this, &PersonWindow::refreshEventList);

    if (addButton) {
        connect(addButton, &QPushButton::clicked, [=]() {
            try {
                std::string name = nameEdit->text().toStdString();
                std::string desc = descEdit->text().toStdString();
                double lat = std::stod(latEdit->text().toStdString());
                double lon = std::stod(lonEdit->text().toStdString());
                std::string date = dateEdit->text().toStdString();

                service.addEvent(me.getName(), name, desc, lat, lon, date);
                refreshEventList();
            } catch (const std::exception& ex) {
                QMessageBox::warning(this, "Add Error", ex.what());
            }
        });
    }
    // reset input fields for convenience
    nameEdit->clear();
    descEdit->clear();
    latEdit->clear();
    lonEdit->clear();
    dateEdit->clear();

    // refresh the list
    refreshEventList();
}

void PersonWindow::refreshEventList() {
    eventList->clear();
    auto events = service.getAllEvents();

    std::sort(events.begin(), events.end(), [](const Event& a, const Event& b) {
        return a.getDate() < b.getDate();
    });

    for (const auto& e : events) {
        double dx = me.getLatitude() - e.getLatitude();
        double dy = me.getLongitude() - e.getLongitude();
        double dist = std::sqrt(dx * dx + dy * dy);
        if (nearbyCheck->isChecked() && dist > 5.0)
            continue;

        QString label = QString::fromStdString(
            "Name: " + e.getName() +
            " | Location: (" + std::to_string(e.getLatitude()) + ", " + std::to_string(e.getLongitude()) + ")" +
            " | Date: " + e.getDate() +
            " | Organiser: " + e.getOrganiser() +
            " | Attendees: " + std::to_string(e.getAttending().size())
        );

        auto* item = new QListWidgetItem(label);
        if (e.getOrganiser() == me.getName())
            item->setBackground(Qt::green);

        eventList->addItem(item);
    }
}