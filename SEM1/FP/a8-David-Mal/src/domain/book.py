class Book:
    def __init__(self, id: str, title: str, author: str):
        self.id = id
        self.title = title
        self.author = author

    def get_book_id(self):
        return self.id

    def get_title(self):
        return self.title

    def get_author(self):
        return self.author

    def set_book_id(self, id):
        self.id = id

    def set_title(self, title):
        self.title = title

    def set_author(self, author):
        self.author = author

    def __eq__(self, other):
        if isinstance(other, Book):
            return self.id == other.id
        return False

    def __str__(self):
        return f"{self.id} | {self.title} | {self.author}"

    def __iter__(self):
        return iter([self.id, self.title, self.author])
