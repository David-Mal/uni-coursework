#pragma once
#include <QWidget>
#include <QLabel>
#include <QListWidget>
#include <QCheckBox>
#include <QPushButton>
#include "Service.h"

class PersonWindow : public QWidget {
    Q_OBJECT

private:
    Service& service;
    Person me;

    QLabel* locationLabel;
    QListWidget* eventList;
    QCheckBox* nearbyCheck;

    QLineEdit *nameEdit, *descEdit, *latEdit, *lonEdit, *dateEdit;
    QPushButton* addButton;

    void setupUI();
    void refreshEventList();
    void connectSignals();

public:
    PersonWindow(Service& service, const Person& person);
};