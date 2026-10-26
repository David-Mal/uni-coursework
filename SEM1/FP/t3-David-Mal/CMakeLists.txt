cmake_minimum_required(VERSION 3.30)
project(t3-David-Mal)

set(CMAKE_CXX_STANDARD 20)
set(CMAKE_AUTOMOC ON)
set(CMAKE_PREFIX_PATH C:/Qt/6.9.0/mingw_64)
find_package(Qt6 COMPONENTS
        Core
        Gui
        Widgets
        REQUIRED)
add_executable(t3-David-Mal main.cpp domain.cpp domain.h service.cpp service.h repository.cpp repository.h gui.cpp gui.h)
target_link_libraries(t3-David-Mal
        Qt::Core
        Qt::Gui
        Qt::Widgets)
