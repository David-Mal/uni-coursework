class Client:
    def __init__(self, id: int, name: str):
        self.id = id
        self.name = name

    def get_client_id(self):
        return self.id

    def get_name(self):
        return self.name

    def set_client_id(self, id):
        self.id = id

    def set_name(self, name):
        self.name = name

    def __eq__(self, other):
        if not isinstance(other, Client):
            return False
        return self.id == other.id

    def __str__(self):
        return f"{self.id}: {self.name}"

    def __iter__(self):
        return iter([self.id, self.name])
