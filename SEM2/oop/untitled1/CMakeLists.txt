cmake_minimum_required(VERSION 3.30)
project(untitled1)

set(CMAKE_CXX_STANDARD 20)
set(CMAKE_AUTOMOC ON)
set(CMAKE_PREFIX_PATH )

add_executable(untitled1 main.cpp service.cpp repository.cpp domain.cpp gui.cpp)
