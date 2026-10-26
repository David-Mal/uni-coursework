package model.statement;

public interface IStmt {
    PrgState execute(PrgState prg) throws MyException;

}
