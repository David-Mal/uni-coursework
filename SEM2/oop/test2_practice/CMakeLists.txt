cmake_minimum_required(VERSION 3.28)
project(test2_practice)

set(CMAKE_CXX_STANDARD 20)

add_executable(test2_practice main.cpp
        main.cpp
        UI.cpp
        UI.h
        Service.cpp
        Service.h
        Domain.cpp
        Domain.h)
