package model;

public class Car implements IVehicle {
    private String name;
    private double repairCost;

    public Car(String name, double repairCost) {
        this.name = name;
        this.repairCost = repairCost;
    }

    @Override
    public String getName() {
        return name;
    }

    @Override
    public double getRepairCost() {
        return repairCost;
    }

    @Override
    public String toString() {
        return "Car: " + name + " (Repair cost: " + repairCost + " RON)";
    }
}
