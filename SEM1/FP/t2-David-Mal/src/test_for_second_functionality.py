from services import Service
from domain import Driver
from src.repository import Repository


class Test:
    def __init__(self, service, drivers, addresses):
        self.drivers = drivers
        self.addresses = addresses
        self.service = service
    def test_find_distance(self):
        given_address = 2000
        sorted_drivers_for_this_address = []

        sorted_drivers_for_this_address.append(Driver("Vasile", 50, 102))
        sorted_drivers_for_this_address.append(Driver("Ion", 10, 21))
        sorted_drivers_for_this_address.append(Driver("Popa", 1, 4))
        function_sorted_drivers = self.service.find_distance(given_address)
        assert function_sorted_drivers == sorted_drivers_for_this_address
