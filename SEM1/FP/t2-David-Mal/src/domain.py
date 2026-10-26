class Driver:
    def __init__(self, name: str, x: int, y: int):
        self.name = name
        self.x = x
        self.y = y

    def __str__(self):
        return f"Driver {self.name} at ({self.x}, {self.y})"

class Address:
    def __init__(self, id: int, name: str, x: int, y: int):
        self.id = id
        self.name = name
        self.x = x
        self.y = y

    def __str__(self):
        return f"Address {self.id} - {self.name} at ({self.x} {self.y})"
