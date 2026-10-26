package controller;

import model.IVehicle;
import repository.IRepository;

import java.util.ArrayList;
import java.util.List;

public class VehicleController {
    private IRepository repository;

    public VehicleController(IRepository repository) {
        this.repository = repository;
    }

    public void addVehicle(IVehicle v) throws Exception {
        repository.add(v);
    }

    public void removeVehicle(String name) throws Exception {
        repository.remove(name);
    }

    public List<IVehicle> getVehiclesWithRepairCostOver(double cost) {
        List<IVehicle> result = new ArrayList<>();
        for (IVehicle v : repository.getAll()) {
            if (v.getRepairCost() > cost) {
                result.add(v);
            }
        }
        return result;
    }

    public IVehicle[] getAllVehicles() {
        return repository.getAll();
    }
}
