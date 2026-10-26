from src.functions import *


def get_user_input():
    commands = """
    add code duration departure_city destination_city
    remove code
    list departure_city
    replace departure_city number_of_minutes
    exit
    """
    print(commands)
    return input("Enter your command: ")

def parse_command(command: str):
    tokens = command.split()
    action = tokens[0]
    params = tokens[1:]
    return action, params

def display(airport: list):
    for t in airport:
        print(to_string(t))


def main():
    airport = create_airport()
    airport = [
        {'code': '0B3002', 'duration': 45, 'departure': 'Cluj', 'destination': 'London'},
        {'code': '0C999', 'duration': 50, 'departure': 'Cluj', 'destination': 'Paris'},
        {'code': '0D123', 'duration': 25, 'departure': 'Buenos-Aires', 'destination': 'Roma'}

    ]
    while True:
        commands = get_user_input()
        action, params = parse_command(commands)
        if action == 'add':
            add_flight(airport, params)

        elif action == 'remove':
            airport = delete_flight(airport, params[0])

        elif action == 'list':
            sorted_flights = show_flight(airport, params[0])
            display(sorted_flights)

        elif action == 'replace':
            airport = replace_flight(airport, params[0], int(params[1]))

        elif action == 'exit':
            print("Goodbye!")
            break