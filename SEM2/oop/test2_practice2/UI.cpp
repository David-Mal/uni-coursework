#include "UI.h"
#include <iostream>
#include <iomanip>


void UI::printMenu() {
    std::cout << "1. Add department." << std::endl;
    std::cout << "2. Show departments." << std::endl;
    std::cout << "3. Show efficient departments." << std::endl;
    std::cout << "4. Write to file." << std::endl;
    std::cout << "5. Exit." << std::endl;
    std::cout << "\nYour option is: >";
}

void UI::initialEntities() {
    auto const entity1 = new NeonatalUnit("Ben Carson Hospital", 5, 9.0);
    auto const entity2 = new Surgery("John Hopkins Hospital", 10, 50);
    auto const entity3 = new NeonatalUnit("Queen Elisabeth Hospital", 6, 8.0);
    controller.addDepartment(entity1);
    controller.addDepartment(entity2);
    controller.addDepartment(entity3);
}

void UI::start() {
    initialEntities();
    std::cout << std::setprecision(2);
    std::cout << "Welcome!" << std::endl;
    while (true) {
        printMenu();
        int option;
        std::cin >> option;
        switch (option) {
            case 1:
                addDepartment();
                break;

            case 2:
                showDepartments();
                break;

            case 3:
                showEfficient();
                break;

            case 4:
                writeToFile();
                break;

            case 5:
                std::cout<< "Goodbye!" << std::endl;
                return;
                break;

            default:
                std::cout << "Invalid option!" << std::endl;
        }
    }
}

void UI::addDepartment() {
    std::cout << "What kind of department?" << std::endl;
    std::cout << "1. Neonatal unit." << std::endl;
    std::cout << "2. Surgery." << std::endl;
    std::cout << "\nYour option is: >";
    int option;
    std::string hospitalName;
    int numberOfDoctors, numberOfMothers, numberOfNewborns, numberOfPatients;
    double averageGrade;

    std::cin >> option;
    std::cin.ignore();
    switch (option) {
        case 1: {
            std::cout << "Please enter the hospital name: >";
            std::getline(std::cin, hospitalName);

            std::cout << "Please enter the number of doctors: >";
            std::cin >> numberOfDoctors;

            std::cout << "Please enter the average grade: >";
            std::cin >> averageGrade;

            NeonatalUnit* neonatalUnit = new NeonatalUnit(hospitalName, numberOfDoctors, averageGrade);
            controller.addDepartment(neonatalUnit);
            break;
        }

        case 2: {
            std::cout << "Please enter the hospital name: >";
            std::getline(std::cin, hospitalName);

            std::cout << "Please enter the number of doctors: >";
            std::cin >> numberOfDoctors;

            std::cout << "Please enter the number of patients: >";
            std::cin >> numberOfPatients;

            Surgery* surgery = new Surgery(hospitalName, numberOfDoctors, numberOfPatients);
            controller.addDepartment(surgery);
            break;
        }

        default:
            std::cout << "Invalid option!" << std::endl;
    }
}

void UI::showDepartments() {
    auto departments = controller.getAllDepartments();
    for (auto department : departments) {
        std::cout << department->toString() << std::endl;
    }
}


void UI::showEfficient() {
    auto departments = controller.getAllEfficientDepartments();
    for (auto department : departments) {
        std::cout << department->toString() << std::endl;
    }
}

void UI::writeToFile() const {
    std::string filename;
    std::cin.ignore();
    std::cout << "Please enter the file name: >";
    std::getline(std::cin, filename);
    controller.writeToFile(filename);
}