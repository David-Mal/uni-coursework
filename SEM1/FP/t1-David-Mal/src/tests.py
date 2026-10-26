from functions import *

def test_to_string():
    flight = {'code': '0B3002', 'duration': 45, 'departure': 'Cluj', 'destination': 'London'}
    assert isinstance(to_string(flight), str)

def test_add():
    airport = []
    flight = {'code': '0B3002', 'duration': 45, 'departure': 'Cluj', 'destination': 'London'}
    add_flight(airport, "0B3002 45 Cluj London")
    assert airport[0] == flight
    assert len(airport) == 1

def test_delete_flight():
    airport = [
        {'code': '0B3002', 'duration': 45, 'departure': 'Cluj', 'destination': 'London'},
        {'code': '0C999', 'duration': 50, 'departure': 'Cluj', 'destination': 'Paris'}
    ]
    assert len(delete_flight(airport, '0C999')) == 1

def test():
    test_to_string()