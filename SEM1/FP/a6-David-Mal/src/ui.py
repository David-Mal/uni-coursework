# Task 4 ---> Bank Account
# This is the program's UI module. The user interface and all interaction with the user (print and input statements) are found here
#
from colorama import Fore, Style
from tabulate import tabulate
from functions import *
from src.functions import _filter


def get_user_input():
    print()
    print(Fore.CYAN + Style.DIM + "--------------------------------------")
    commands = """
    add <value> <type> <description>
    insert <day> <value> <type> <description>
    remove <day>
    remove <start day> to <end day>
    remove <type>
    replace <day> <type> <description> with <value>
    list
    list <type>
    list [ < | = | > ] <value>
    list balance <day>
    filter <type>
    filter <type> <value>
    undo 
    exit        
    """
    print()
    print("Supported commands look like this:")
    print(f"{commands}" + Style.RESET_ALL)
    return input("Enter command: ")

def show_error_message(error_message):
    """Display an error message in red."""
    print(Fore.RED + f"Error: {error_message}" + Style.RESET_ALL)


def parse_user_command(command_input):
    """
    Parse the user's command input into an action and arguments.
    :param command_input: The command string entered by the user.
    :return: A tuple containing the action and a list of arguments.
    """
    tokens = command_input.split()
    action = tokens[0].lower()
    arguments = tokens[1:]
    return action, arguments


def display_account_table(account):
    """
    Display the transactions in the bank account as a table.
    :param account: The bank account list containing transactions.
    """
    sorted_transactions = sort_transactions_by_day(account)
    column_headers = ["Day", "Amount", "Type", "Description"]
    table_rows = [to_list(transaction) for transaction in sorted_transactions]

    print("\n" + "-" * 38 + "\n")
    print("Transactions:\n")
    print(tabulate(table_rows, headers=column_headers, tablefmt="fancy_grid"))
    print("\n" + "-" * 38 + "\n")


def handle_add_transaction(account, arguments):
    """
    Add a transaction through the UI, with error handling.
    :param account: The bank account list.
    :param arguments: Arguments for the transaction to be added.
    """
    try:
        add_transaction(account, arguments)
    except ValueError as error:
        show_error_message("Failed to add the transaction.")
        print(str(error) + Style.RESET_ALL)


def handle_remove_transactions(account, arguments):
    """
    Remove transactions through the UI, with error handling.
    :param account: The bank account list.
    :param arguments: Criteria for the transactions to be removed.
    """
    try:
        remove_transactions(account, arguments)
    except ValueError as error:
        show_error_message("Failed to remove the transactions.")
        print(str(error) + Style.RESET_ALL)


def handle_replace_transaction(account, arguments):
    """
    Replace a transaction amount through the UI, with error handling.
    :param account: The bank account list.
    :param arguments: Criteria for the transaction replacement.
    """
    try:
        replace_transaction(account, arguments)
    except ValueError as error:
        show_error_message("Failed to replace the transaction.")
        print(str(error) + Style.RESET_ALL)


def list_transactions_by_type(account, transaction_type):
    """
    List transactions of a specific type.
    :param account: The bank account list.
    :param transaction_type: The type of transactions to display ("in" or "out").
    """
    filtered_transactions = [
        transaction for transaction in account if get_transaction_type(transaction) == transaction_type
    ]
    display_account_table(filtered_transactions)


def display_balance(account, day):
    """
    Display the account balance up to a specific day.
    :param account: The bank account list.
    :param day: The day up to which the balance is calculated.
    """
    balance = compute_account_balance(account, day)
    print(f"Balance at the end of day {Fore.GREEN}{day}{Style.RESET_ALL} is {Fore.MAGENTA}{balance}{Style.RESET_ALL}")


def list_transactions_smaller(account, threshold):
    """
    List transactions with an amount less than the threshold.
    :param account: The bank account list.
    :param threshold: The maximum amount for transactions to display.
    """
    filtered_transactions = [t for t in account if get_transaction_amount(t) < threshold]
    display_account_table(filtered_transactions)


def list_transactions_greater(account, threshold):
    """
    List transactions with an amount greater than the threshold.
    :param account: The bank account list.
    :param threshold: The minimum amount for transactions to display.
    """
    filtered_transactions = [t for t in account if get_transaction_amount(t) > threshold]
    display_account_table(filtered_transactions)


def list_transactions_equal(account, amount):
    """
    List transactions with an amount equal to the given value.
    :param account: The bank account list.
    :param amount: The exact amount for transactions to display.
    """
    filtered_transactions = [t for t in account if get_transaction_amount(t) == amount]
    display_account_table(filtered_transactions)


def list_transactions_by_amount(account, arguments):
    """
    List transactions based on comparison operators and amounts.
    :param account: The bank account list.
    :param arguments: List containing the operator (<, >, =) and amount.
    """
    operator, value = arguments[0], int(arguments[1])
    if operator == "<":
        list_transactions_smaller(account, value)
    elif operator == ">":
        list_transactions_greater(account, value)
    elif operator == "=":
        list_transactions_equal(account, value)
    else:
        show_error_message("Invalid command.")


def handle_list_transactions(account, arguments):
    """
    Handle the listing of transactions based on criteria.
    :param account: The bank account list.
    :param arguments: Criteria for listing transactions.
    """
    if not arguments:
        display_account_table(account)
    elif len(arguments) == 1:
        list_transactions_by_type(account, arguments[0])
    elif len(arguments) == 2 and arguments[0] == "balance":
        display_balance(account, int(arguments[1]))
    elif len(arguments) == 2:
        list_transactions_by_amount(account, arguments)
    else:
        show_error_message("Invalid listing command.")


def handle_filter_transactions(account, arguments):
    """
    Filter transactions through the UI, with error handling.
    :param account: The bank account list.
    :param arguments: Filtering criteria.
    """
    try:
        filtered_transactions = _filter(account, arguments)
        display_account_table(filtered_transactions)
    except ValueError as error:
        show_error_message("Failed to filter the transactions.")
        print(str(error) + Style.RESET_ALL)


def handle_undo(account, history):
    """
    Undo the last action through the UI, with error handling.
    :param account: The bank account list.
    :param history: The history stack of previous states.
    """
    try:
        undo_last_operation(account, history)
    except ValueError as error:
        show_error_message("Failed to undo the last operation.")
        print(str(error) + Style.RESET_ALL)


def exit_program():
    """Display a farewell message and exit the program."""
    print(Fore.GREEN + "Exiting... " + Style.RESET_ALL)

def main_program():
    """
    Entry point for the banking application. Handles user input, processes commands,
    and updates the bank account state accordingly.
    """
    # Initialize the bank account and previous states
    current_account = initialize_account()
    account_history = []  # To track changes for undo functionality

    # Populate the account with initial random transactions
    current_account = generate_random_transactions(current_account)
    display_account_table(current_account)

    while True:
        user_input = get_user_input()
        command, parameters = parse_user_command(user_input)

        # Execute commands based on user input
        if command in {"add", "insert"}:
            account_history.append(current_account.copy())
            handle_add_transaction(current_account, parameters)

        elif command == "remove":
            account_history.append(current_account.copy())
            handle_remove_transactions(current_account, parameters)

        elif command == "replace":
            account_history.append(current_account.copy())
            handle_replace_transaction(current_account, parameters)

        elif command == "list":
            handle_list_transactions(current_account, parameters)

        elif command == "filter":
            handle_filter_transactions(current_account, parameters)

        elif command == "undo":
            handle_undo(current_account, account_history)

        elif command == "exit":
            exit_program()
            break

        else:
            show_error_message("Invalid command entered. Please try again.")


