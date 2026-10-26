from domain import Address, Driver
class Repository:
    def __init__(self, filename_addresses, filename_drivers):
        self.filename_addresses = filename_addresses
        self.filename_drivers = filename_drivers
        self.addresses = []
        self.drivers = []
        self.load_data_addresses()
        self.load_data_drivers()

    def load_data_addresses(self):
        try:
            f = open(self.filename_addresses, 'r').read().split("\n")
            for line in f:
                if line == "":
                    continue
                id, name, x, y = line.split(",")
                address = Address(int(id), name, int(x), int(y))
                self.addresses.append(address)
        except IOError as e:
            raise e

    def load_data_drivers(self):
        try:
            f = open(self.filename_drivers, 'r').read().split("\n")
            for line in f:
                if line == "":
                    continue
                name, x, y = line.split(",")
                driver = Driver(name, int(x), int(y))
                self.drivers.append(driver)
        except IOError as e:
            raise e

    def save_data_addresses(self):
        try:
            f = open(self.filename_addresses, 'w')
            for address in self.addresses:
                f.write(f"{address.id},{address.name},{address.x},{address.y}\n")
        except IOError as e:
            raise e

    def save_data_drivers(self):
        try:
            f = open(self.filename_drivers, 'w')
            for driver in self.drivers:
                f.write(f"{driver.name},{driver.x},{driver.y}\n")
        except IOError as e:
            raise e

    def get_all_addresses(self):
        return self.addresses

    def get_all_drivers(self):
        return self.drivers

    def get_address_by_id(self, id):
        if id not in [a.id for a in self.addresses]:
            raise Exception("Invalid id, doesnt exist")

        for address in self.addresses:
            if address.id == id:
                return address


