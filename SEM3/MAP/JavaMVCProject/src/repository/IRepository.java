package repository;

import model.IVehicle;

public interface IRepository {
    void add(IVehicle vehicle) throws Exception;
    void remove(String name) throws Exception;
    IVehicle[] getAll();
}
