package repository;

import model.IVehicle;

public class VehicleRepository implements IRepository {
    private IVehicle[] vehicles;
    private int size;

    public VehicleRepository(int capacity) {
        vehicles = new IVehicle[capacity];
        size = 0;
    }

    @Override
    public void add(IVehicle vehicle) throws Exception {
        if (size >= vehicles.length) {
            throw new Exception("Repository is full!");
        }
        vehicles[size++] = vehicle;
    }

    @Override
    public void remove(String name) throws Exception {
        boolean found = false;
        for (int i = 0; i < size; i++) {
            if (vehicles[i].getName().equalsIgnoreCase(name)) {
                for (int j = i; j < size - 1; j++) {
                    vehicles[j] = vehicles[j + 1];
                }
                vehicles[--size] = null;
                found = true;
                break;
            }
        }
        if (!found) {
            throw new Exception("Vehicle not found: " + name);
        }
    }

    @Override
    public IVehicle[] getAll() {
        IVehicle[] result = new IVehicle[size];
        System.arraycopy(vehicles, 0, result, 0, size);
        return result;
    }
}
