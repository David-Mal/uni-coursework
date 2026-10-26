from datetime import date


class Rental:
    def __init__(self, id: str, book_id: int, client_id: str, rented_date: date, returned_date: date):
        self.id = id
        self.book_id = book_id
        self.client_id = client_id
        self.rented_date = rented_date
        self.returned_date = returned_date

    def get_rental_id(self):
        return self.id

    def get_book_id(self):
        return self.book_id

    def get_client_id(self):
        return self.client_id

    def get_rented_date(self):
        return self.rented_date

    def get_returned_date(self):
        return self.returned_date

    def __eq__(self, other):
        if not isinstance(other, Rental):
            return False
        return self.id == other.id

    def __str__(self):
        return (f"Rental ID: {self.id} with book ID: {self.book_id} owned by client ID: {self.client_id} from {self.rented_date}"
                + f" to {self.returned_date}" if self.returned_date else "")

    def __iter__(self):
        return iter([self.id, self.book_id, self.client_id, self.rented_date, self.returned_date])
