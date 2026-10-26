# Task 4 ---> Bank Account
# The program's functions are implemented here. There is no user interaction in this file, therefore no input/print statements. Functions here
# communicate via function parameters, the return statement and raising of exceptions. 
#

from random import randint, choice

"""
Transaction structure:
    A transaction contains:
    - day (1-30): The day of the month.
    - amount (positive integer): The money transferred.
    - type ("in" or "out"): Type of transaction ("in" for deposit, "out" for withdrawal).
    - description (string): Description of the transaction.
"""


# Getters and Setters
def get_transaction_day(transaction: dict) -> int:
    """Retrieve the day from a transaction."""
    return int(transaction["day"])


def get_transaction_amount(transaction: dict) -> int:
    """Retrieve the amount from a transaction."""
    return int(transaction["amount"])


def get_transaction_type(transaction: dict) -> str:
    """Retrieve the type of a transaction."""
    return transaction["type"]


def get_transaction_description(transaction: dict) -> str:
    """Retrieve the description of a transaction."""
    return transaction["description"]


def set_transaction_amount(transaction: dict, amount: int):
    """Update the amount of a transaction."""
    transaction["amount"] = int(amount)


# Core Functions
def initialize_account():
    """Initialize an empty bank account."""
    return []


def create_transaction(day: int, amount: int, transaction_type: str, description: str) -> dict:
    """
    Create a transaction record.
    :param day: Day of the transaction (1-30).
    :param amount: Transaction amount (positive integer).
    :param transaction_type: Type of transaction ("in" or "out").
    :param description: Description of the transaction.
    :return: Transaction dictionary.
    """
    return {"day": day, "amount": amount, "type": transaction_type, "description": description}

def to_list(transaction: dict) -> list:
    """
    Converts a transaction dictionary to a list representation.
    :param transaction: A dictionary representing a financial transaction.
    :return: A list containing the transaction's day, amount, type, and description.
    """
    return [int(transaction["day"]), int(transaction["amount"]), transaction["type"], transaction["description"]]



def generate_random_transactions(account):
    """
    Populate an account with random transactions.
    :param account: The bank account list.
    :return: Updated bank account.
    """
    types = ["in", "out"]
    descriptions_out = ["food", "rent", "shopping", "car", "utilities", "travel"]
    descriptions_in = ["salary", "lottery", "gift", "business", "hustle"]
    for _ in range(10):
        day = randint(1, 30)
        amount = randint(10, 500)
        transaction_type = choice(types)
        description = choice(descriptions_out if transaction_type == "out" else descriptions_in)

        transaction = create_transaction(day, amount, transaction_type, description)
        account.append(transaction)
    return account


# Operations
def sort_transactions_by_day(account):
    """Sort transactions in the account by day."""
    return sorted(account, key=lambda t: int(t["day"]))


def add_transaction(account, params):
    """
    Add a new transaction to the account.
    :param account: The bank account list.
    :param params: List of parameters for the transaction.
    """
    if len(params) == 3:
        # Auto-assign the current day
        last_day = max((get_transaction_day(t) for t in account), default=1)
        transaction = create_transaction(last_day, *params)
    elif len(params) == 4:
        transaction = create_transaction(*params)
    else:
        raise ValueError("Invalid parameters for adding a transaction.")
    account.append(transaction)


def remove_transactions_by_day(account, day):
    """Remove all transactions from a specific day."""
    account[:] = [t for t in account if get_transaction_day(t) != day]


def remove_transactions_in_date_range(account, start_day, end_day):
    """Remove all transactions between two days."""
    account[:] = [t for t in account if t["day"] < start_day or t["day"] > end_day]


def remove_transactions_by_type(account, transaction_type):
    """Remove all transactions of a given type."""
    account[:] = [t for t in account if get_transaction_type(t) != transaction_type]

def remove_transactions(account: list, args: list):
    """
    Removes transactions based on specified criteria.
    :param account: List of transactions (bank account).
    :param args: List of arguments specifying the removal criteria.
    - If args contain a single value (day), removes all transactions on that day.
    - If args specify a range (e.g., ["start_day", "to", "end_day"]), removes all transactions in the range.
    - If args specify a type ("in" or "out"), removes all transactions of that type.
    :raises ValueError: If invalid arguments are provided.
    """
    if len(args) == 1 and args[0].isdigit():  # Remove transactions by day
        day_to_remove = int(args[0])
        account[:] = [transaction for transaction in account if transaction["day"] != day_to_remove]

    elif len(args) == 3 and args[1].lower() == "to":  # Remove transactions in a date range
        start_day, end_day = int(args[0]), int(args[2])
        account[:] = [
            transaction
            for transaction in account
            if not (start_day <= transaction["day"] <= end_day)
        ]

    elif len(args) == 1 and args[0].lower() in {"in", "out"}:  # Remove transactions by type
        type_to_remove = args[0].lower()
        account[:] = [transaction for transaction in account if transaction["type"] != type_to_remove]

    else:
        raise ValueError("Invalid arguments for removing transactions.")



# def replace_transaction_amount(account, params):
#     """
#     Replace the amount of a specific transaction.
#     :param account: The bank account list.
#     :param params: Parameters defining the replacement.
#     """
#     if len(params) != 5 or params[3] != "with":
#         raise ValueError("Invalid parameters for replacing a transaction.")
#
#     day, transaction_type, description, _, new_amount = params
#     for transaction in account:
#         if (
#                 get_transaction_day(transaction) == int(day)
#                 and get_transaction_type(transaction) == transaction_type
#                 and get_transaction_description(transaction) == description
#         ):
#             set_transaction_amount(transaction, new_amount)
#             break

def replace_transaction(account: list, args: list):
    """
    Replaces the amount of a specific transaction based on the provided criteria.
    :param account: List of transactions (bank account).
    :param args: List of arguments specifying the replacement criteria:
                 - Format: [day, type, description, "with", new_amount]
    :raises ValueError: If invalid arguments are provided or no matching transaction is found.
    """
    if len(args) == 5 and args[3].lower() == "with":
        day = int(args[0])
        transaction_type = args[1].lower()
        description = args[2]
        new_amount = int(args[4])

        # Look for a matching transaction
        for transaction in account:
            if (
                transaction["day"] == day
                and transaction["type"] == transaction_type
                and transaction["description"] == description
            ):
                transaction["amount"] = new_amount
                return  # Replace only the first matching transaction

        # If no matching transaction is found, raise an error
        raise ValueError("No matching transaction found to replace.")
    else:
        raise ValueError("Invalid arguments for replacing a transaction.")


def compute_account_balance(account, up_to_day) -> int:
    """Calculate the account balance up to a specific day."""
    balance = 0
    for t in account:
        if get_transaction_day(t) <= up_to_day:
            balance += t["amount"] if get_transaction_type(t) == "in" else -t["amount"]
    return balance


# Filtering
def filter_transactions_by_type(account, transaction_type):
    """Filter transactions by type."""
    return [t for t in account if get_transaction_type(t) == transaction_type]


def filter_transactions_below_amount(account, max_amount):
    """Filter transactions below a specified amount."""
    return [t for t in account if get_transaction_amount(t) < max_amount]


def filter_transactions(account, params):
    """
    Filter transactions based on the criteria.
    :param account: The bank account list.
    :param params: Filtering criteria.
    """
    if len(params) == 1:
        if params[0] in ["in", "out"]:
            return filter_transactions_by_type(account, params[0])
        raise ValueError("Invalid filter criteria.")
    elif len(params) == 2:
        filtered = filter_transactions_by_type(account, params[0])
        return filter_transactions_below_amount(filtered, int(params[1]))
    else:
        raise ValueError("Invalid filter parameters.")

def _filter(account: list, args: list) -> list:
    """
    Filters transactions based on specified criteria.
    :param account: List of transactions (bank account).
    :param args: List of arguments specifying the filter criteria:
                 - Format for type-based filtering: ["in"] or ["out"]
                 - Format for type and amount filtering: ["in/out", "<amount"]
    :return: A filtered list of transactions matching the criteria.
    :raises ValueError: If the arguments are invalid.
    """
    if len(args) == 1 and args[0] in {"in", "out"}:  # Filter by transaction type
        transaction_type = args[0].lower()
        return [transaction for transaction in account if transaction["type"] == transaction_type]

    elif len(args) == 2 and args[1].isdigit():  # Filter by transaction type and amount
        transaction_type = args[0].lower()
        max_amount = int(args[1])
        return [
            transaction
            for transaction in account
            if transaction["type"] == transaction_type and transaction["amount"] < max_amount
        ]

    else:
        raise ValueError("Invalid arguments for filtering transactions.")



def undo_last_operation(account, previous_states):
    """
    Undo the last operation by restoring the previous state.
    :param account: The bank account list.
    :param previous_states: List of previous states.
    """
    if not previous_states:
        raise ValueError("No operations to undo.")
    account[:] = previous_states.pop()

