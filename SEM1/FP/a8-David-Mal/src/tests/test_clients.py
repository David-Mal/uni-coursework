import unittest
from src.domain.client import Client
from src.repository.rental_repository import RentalRepository
from src.repository.repository import Repository
from src.services.client_service import ClientService
from src.repository.repository_exception import RepositoryException
from src.services.rental_service import RentalService


class TestCientService(unittest.TestCase):
    def setUp(self):
        self.rental_repository = RentalRepository()
        self.client_repository = Repository()
        self.rental_service = RentalService(self.rental_repository)
        self.client_service = ClientService(self.client_repository, self.rental_service)

    def test_add_client_success(self):
        client_id = 10
        name = "Lionel Messi"
        self.client_service.add_client(client_id, name)
        self.assertEqual(len(self.client_service.get_all_clients()), 1)

    def test_remove_client_success(self):
        client_id = 10
        name = "Lionel Messi"
        self.client_service.add_client(client_id, name)
        self.client_service.remove_client(client_id)
        self.assertEqual(len(self.client_service.get_all_clients()), 0)

    def test_update_client_success(self):
        client_id = 20
        name = "Lionel Messi"
        self.client_service.add_client(client_id, name)
        new_name = "THE GOAT"
        self.client_service.update_client(client_id, new_name)
        updated_client = self.client_service.get_all_clients()[0]
        self.assertEqual(updated_client.name, new_name)

    def test_get_all_clients(self):
        self.assertEqual(len(self.client_service.get_all_clients()), 0)