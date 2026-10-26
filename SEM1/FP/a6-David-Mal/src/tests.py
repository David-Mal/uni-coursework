from functions import *
from src.functions import _filter


def test_create_transaction():
    transaction = {"day": 10, "amount": 100, "type": "out", "description": "Emag"}
    assert create_transaction(10, 100, "out", "Emag") == transaction

def test_sort_transactions_by_day():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    assert sort_transactions_by_day(account) == [
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"}
    ]

def test_add_transaction():
    bank_account = initialize_account()
    add_transaction(bank_account, [30, 2500, "in", "salary"])
    assert len(bank_account) == 1
    assert bank_account[0] == {"day": 30, "amount": 2500, "type": "in", "description": "salary"}

def test_remove_transaction_by_day():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    remove_transactions_by_day(account, 12)
    assert account == [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]

def test_remove_transaction_in_date_range():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    remove_transactions_in_date_range(account, 3, 9)
    assert account == [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"}
    ]
def test_remove_transactions_by_type():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    remove_transactions_by_type(account, "in")
    assert account == [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]

def test_remove_transactions():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    remove_transactions(account, ["in"])
    assert account == [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]

def test_replace_transaction():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    replace_transaction(account, [12, "in", "salary", "with", 2000])
    assert account == [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 2000, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]

def test_compute_account_balance():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    assert compute_account_balance(account, 10) == 95

def test_filter_transactions_by_type():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    assert filter_transactions_by_type(account, "in") == [
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"}
    ]

def test_filter_transactions_bellow_amount():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    assert filter_transactions_below_amount(account, 300) == [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
    ]

def test_filter():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    assert _filter(account, ["in"]) == [
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"}
    ]

def test_undo():
    account = [
        {"day": 15, "amount": 100, "type": "out", "description": "Emag"},
        {"day": 12, "amount": 250, "type": "in", "description": "salary"},
        {"day": 3, "amount": 400, "type": "in", "description": "gift"},
        {"day": 9, "amount": 305, "type": "out", "description": "clothes"}
    ]
    previous_states = [account]
    add_transaction(account, [30, 2500, "in", "lottery"])
    previous_states.append(account)
    undo_last_operation(account, previous_states)
    assert account == previous_states[0]

def testing():
    test_undo()
    test_compute_account_balance()
    test_filter_transactions_by_type()
    test_filter_transactions_bellow_amount()
    test_filter()
    test_add_transaction()
    test_compute_account_balance()
    test_remove_transaction_by_day()
    test_sort_transactions_by_day()
    test_create_transaction()
    test_replace_transaction()