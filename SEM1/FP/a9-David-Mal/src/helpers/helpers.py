from datetime import datetime, timedelta, date
import random

def random_date(start: datetime, end: datetime) -> datetime:
    """
    Generate a random datetime between two datetime objects.

    :param start: The starting datetime.
    :param end: The ending datetime.
    :return: A random datetime between start and end.
    """
    if start > end:
        raise ValueError("Start datetime must be earlier than end datetime.")

    # Calculate the difference in seconds between the two datetimes
    delta = (end - start).total_seconds()

    # Generate a random number of seconds to add to the start datetime
    random_seconds = random.uniform(0, delta)

    # Return the resulting random datetime
    return start + timedelta(seconds=random_seconds)

def read_date(prompt: str) -> date:
    """
    Read a date from user input.

    :param prompt: The prompt to display to the user.
    :return: A datetime object representing the entered date.
    """
    while True:
        try:
            date_str = input(prompt)
            return datetime.strptime(date_str, "%Y-%m-%d").date()
        except ValueError:
            print("Invalid date format. Please enter a date in YYYY-MM-DD format.")
