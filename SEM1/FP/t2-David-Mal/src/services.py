from xmlrpc.client import MAXINT


class Service:
    def __init__(self, repo):
        self.repo = repo

    def get_all_drivers(self):
        drivers = self.repo.get_all_drivers()
        return sorted(drivers, key=lambda x: x.name)

    def get_all_addresses(self):
        addresses = self.repo.get_all_addresses()
        return sorted(addresses, key=lambda x: x.name)

    def get_address_by_id(self, address_id):
        return self.repo.get_address_by_id(address_id)

    def find_distance(self, address_id):
        """
        Function that sorts the drivers list accordingly to the distances from the given address
        The drivers list are sorted using selection sort
        :param address_id: the id of the given address
        :return: the sorted list of drivers
        """
        address = self.repo.get_address_by_id(address_id)
        address_x = int(address.x)
        address_y = int(address.y)
        distances = []
        drivers = self.repo.get_all_drivers()
        for driver in drivers:
            driver_x = int(driver.x)
            driver_y = int(driver.y)
            manhattan = abs(driver_x - address_x) + abs(driver_y - address_y)
            distances.append(manhattan)
        for i in range(len(drivers)):
            for j in range(i + 1, len(drivers)):
                if distances[i] > distances[j]:
                    distances[i], distances[j] = distances[j], distances[i]
                    drivers[i], drivers[j] = drivers[j], drivers[i]
        return drivers, distances

    def check_id(self, id):
        if id not in [a.id for a in self.get_all_addresses()]:
            raise Exception("Address does not exist!")

    def distance_for_each_address(self):
        addresses = self.get_all_addresses()
        drivers = self.get_all_drivers()
        distances = []
        drivers_to_be_printed = []
        for address in addresses:
            address_x = int(address.x)
            address_y = int(address.y)
            minim_distance = MAXINT
            minim_driver = None
            for driver in drivers:
                driver_x = int(driver.x)
                driver_y = int(driver.y)
                distance = abs(driver_x - address_x) + abs(driver_y - address_y)
                if distance < minim_distance:
                    minim_distance = distance
                    minim_driver = driver
            drivers_to_be_printed.append((int(address.id), minim_driver, minim_distance))
        return sorted(drivers_to_be_printed, key=lambda x: x[2], reverse=True)


