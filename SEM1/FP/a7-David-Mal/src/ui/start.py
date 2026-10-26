from src.ui.ui import UI
from src.repository.binary_file_repository import BinaryFileRepository
from src.repository.json_file_repository import JSONFileRepository
from src.repository.memory_repository import MemoryRepository
#from src.repository.sql_repository import SQLRepository
from src.repository.text_file_repository import TextFileRepository
from src.services.services import Services

def get_repo():
    with open("settings.properties", "r") as settings_file:
        for line in settings_file:
            line = line.strip()
            if line.startswith("REPOSITORY="):
                repository_type = line[len("REPOSITORY="):]
                if repository_type == "memory":
                    return MemoryRepository()
                elif repository_type == "text":
                    return TextFileRepository("numbers.txt")
                elif repository_type == "binary":
                    return BinaryFileRepository("numbers.data")
                elif repository_type == "json":
                    return JSONFileRepository("numbers.json")
                else:
                    raise ValueError("Invalid repository type!")
            else:
                raise ValueError("Invalid settings file!")

if __name__ == "__main__":
    repository = get_repo()
    repo = Services(repository)
    ui = UI(repo)
    ui.menu()