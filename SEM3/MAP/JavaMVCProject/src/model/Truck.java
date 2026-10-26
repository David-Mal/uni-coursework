package model;

public class Truck implements IVehicle {
    private String name;
    private double repairCost;

    public Truck(String name, double repairCost) {
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
        return "Truck: " + name + " (Repair cost: " + repairCost + " RON)";
    }
}
