cmake_minimum_required(VERSION 3.30)
project(EventPlanner)

set(CMAKE_CXX_STANDARD 20)
set(CMAKE_PREFIX_PATH C:/Qt/6.9.0/mingw_64)

find_package(Qt6 REQUIRED COMPONENTS Widgets)

set(CMAKE_AUTOMOC ON)
SET(CMAKE_AUTORCC ON)
SET(CMAKE_AUTOUIC ON)

add_executable(EventPlanner main.cpp
        Domain.cpp
        Domain.h
        Repository.cpp
        Repository.h
        Observer.cpp
        Observer.h
        Subject.cpp
        Subject.h
        Service.cpp
        Service.h
        PersonWindow.cpp
        PersonWindow.h
        PopularEventsWindow.cpp
        PopularEventsWindow.h)

target_link_libraries(EventPlanner Qt6::Widgets)