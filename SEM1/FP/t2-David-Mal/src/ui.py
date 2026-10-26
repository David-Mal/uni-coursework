from src.repository import Repository
from src.services import Service
from test_for_second_functionality import Test


class Ui:
    def __init__(self):
        self.service = Service((Repository("addresses.txt", "drivers.txt")))

    def menu(self):
        print("1. Display addresses and drivers")
        print("2. Drivers sorted by distance")
        print("3. Print distances for each address")
        print("4. Exit")

    def run(self):

        while True:
            self.menu()
            choice = input("Enter your choice: ")
            if choice == "1":
                drivers = self.service.get_all_drivers()
                print("DRIVERS:")
                print()
                for driver in drivers:
                    print(driver)

                print()

                addresses = self.service.get_all_addresses()
                print("ADDRESSES:")
                print()
                for address in addresses:
                    print(address)

            elif choice == "2":
                try:
                    address_id = int(input("Enter address id: "))
                    self.service.check_id(address_id)
                    drivers, distances = self.service.find_distance(address_id)
                    for i in range(len(drivers)):
                        print(f"{drivers[i]}, {distances[i]} km")
                except ValueError:
                    print("Invalid address id")
                except Exception as e:
                    print(e)

            elif choice == "3":

                drivers_to_be_printed = self.service.distance_for_each_address()
                for driver in drivers_to_be_printed:
                    address = self.service.get_address_by_id(int(driver[0]))
                    print(f"For address: {address.id} - {address.name} at ({address.x}, {address.y}) the closest driver is {driver[1]} at distance {driver[2]} km")


            elif choice == "4":
                break
            else:
                print("Invalid choice")
