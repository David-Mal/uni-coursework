#ifndef UI_H
#define UI_H
#include "Controller.h"


class UI {
private:
    Controller controller;
public:
    void start();
    static void printMenu();
    void addDepartment();
    void showDepartments();
    void showEfficient();
    void writeToFile() const;
    void initialEntities();
};



#endif //UI_H
