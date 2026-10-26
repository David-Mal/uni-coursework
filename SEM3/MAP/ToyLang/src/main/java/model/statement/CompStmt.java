package model.statement;

public class CompStmt implements IStmt {
    IStmt first;
    IStmt second;


    String toString() {
        return "(" + first.toString() + ";" + second.toString() + ")";
    }

}
