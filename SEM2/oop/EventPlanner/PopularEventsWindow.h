//
// Created by mariasiu alexandra on 15.06.2025.
//

#ifndef POPULAREVENTSWINDOW_H
#define POPULAREVENTSWINDOW_H

#include <QWidget>
#include <QListWidget>
#include "Service.h"
#include "Observer.h"

class PopularEventsWindow : public QWidget, public Observer {
    Q_OBJECT

private:
    Service& service;
    QListWidget* eventList;

    void populate();

public:
    PopularEventsWindow(Service& service);
    ~PopularEventsWindow();

    void update() override;
};
#endif //POPULAREVENTSWINDOW_H
