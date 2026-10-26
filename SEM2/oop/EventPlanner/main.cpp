#include <QApplication>
#include "Repository.h"
#include "Service.h"
#include "PersonWindow.h"
#include "PopularEventsWindow.h"
#include <iostream>
int main(int argc, char *argv[]) {
    QApplication app(argc, argv);

    Repository repo;
    repo.loadPersons("people.txt");
    repo.loadEvents("events.txt");

    Service service(repo);
    std::cout << "Loaded persons: " << service.getAllPersons().size() << "\n";

    // Create a window for each person
    for (const auto& person : service.getAllPersons()) {
        auto* w = new PersonWindow(service, person);
        w->show();
    }

    // Create and show the popular events window
    auto* popular = new PopularEventsWindow(service);
    popular->show();

    return app.exec();
}