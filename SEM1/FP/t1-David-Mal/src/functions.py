def get_code(flight: dict):
    return flight['code']

def get_duration(flight: dict):
    return flight['duration']

def get_departure(flight: dict):
    return flight['departure']

def get_destination(flight: dict):
    return flight['destination']

def set_code(flight: dict, code: str):
    flight['code'] = code

def set_duration(flight: dict, duration: int):
    flight['duration'] = duration

def set_departure(flight: dict, departure: str):
    flight['departure'] = departure

def set_destination(flight: dict, destination: str):
    flight['destination'] = destination

def create_airport():
    """
    creates an empty list of flights
    :return:
    """
    return []

def to_string(flight: dict) -> str:
    """
    convert dictionary to string
    :param flight: a dictionary
    :return:the string obtained from the dictionary
    """
    string_flight = f"code: { flight['code'] }, duration: {flight['duration']}, departure_city: {flight['departure']}, destination_city: {flight['destination']}"
    return string_flight

def create_flight(code: str, duration: int, departure: str, destination: str) -> dict:
    """
    creates a new dictionary named flight, to be added to the list of flights
    :param code: code of the flight
    :param duration: duration of the flight
    :param departure: departure city of the flight
    :param destination: destination city of the flight
    :return: the created dictionary
    """
    return {'code': code, 'duration': duration, 'departure': departure, 'destination': destination}

def add_flight(airport : list, params):
    """

    :param airport: the list of flights
    :param params: the list of parameters used to call the function
    :return: None
    """
    if len(params) < 4:
        raise ValueError("At least 4 params required")

    if len(params[0]) < 3 or len(params[2]) < 3 or len(params[3]) < 3:
        raise ValueError("Input too short!")

    try:
        duration = int(params[1])
        if duration < 10 or duration > 60:
            raise ValueError("Duration must be between 10 and 60")
    except ValueError:
        raise ValueError("Duration should be integer!")

    if len(params) == 4:
        for t in airport:
            if get_code(t) == params[0]:
                raise ValueError("There is already a flight with the given code!")
        airport.append(create_flight(params[0], params[1], params[2], params[3]))

def delete_flight(airport: list, code: str):
    """
    function that deletes specific flight from the airport list
    :param airport: the list of flights
    :param code: the code of the flight to be deleted
    :return: None
    """
    airport[:] = [t for t in airport if t['code'] != code]
    return airport

def show_flight(airport: list, departure: str):
    """

    :param airport: the list of flights
    :param departure: the city of departure
    :return: a list of sorted flights accordingly
    """
    sorted_flights = sorted(airport, key=lambda k: k['destination'])
    sorted_flights[:] = [t for t in airport if t['departure'] == departure]
    return sorted_flights

def replace_flight(airport: list, departure: str, delay: int) -> list:
    """
    function to replace the duration of flights starting from a given departure city by adding a given delay
    :param airport: the list of flights
    :param departure: the given string for departure city
    :param delay: the given delay which should be added to every certain flight
    :return: the modified version of airport
    """
    if delay < 10 or delay > 60:
        raise ValueError("Incorrect delay!")
    for t in airport:
        if get_departure(t) == departure:
            try:
                duration = int(get_duration(t))
            except ValueError:
                raise ValueError("Duration should be integer!")
            set_duration(t, duration + delay)
    return airport
