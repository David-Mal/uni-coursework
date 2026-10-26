#pragma once
#include <string?

class Patient {
	std::string name, status;
	int age, room;
public:
	Patient (std::string name, std::string status, int age, int room);
	std::string getName();
	std::string getStatus();
	int getAge();
	int getRoom();