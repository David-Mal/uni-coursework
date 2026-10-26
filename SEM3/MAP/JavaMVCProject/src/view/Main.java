package view;

import controller.VehicleController;
import model.*;
import repository.*;

public class Main {
    public static void main(String[] args) {
        try {
            IRepository repo = new VehicleRepository(10);
            VehicleController controller = new VehicleController(repo);

            controller.addVehicle(new Car("Dacia Logan", 800));
            controller.addVehicle(new Truck("Volvo Truck", 2500));
            controller.addVehicle(new Motorcycle("Yamaha", 1200));
            controller.addVehicle(new Car("BMW", 3000));

            System.out.println("All vehicles with repair cost > 1000 RON:");
            for (IVehicle v : controller.getVehiclesWithRepairCostOver(1000)) {
                System.out.println(v);
            }
        } catch (Exception e) {
            System.out.println("Error: " + e.getMessage());
        }
    }
}
