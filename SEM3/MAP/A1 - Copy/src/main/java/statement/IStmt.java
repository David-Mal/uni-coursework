package statement;

import exceptions.MyException;
import utils.PrgState;
import type.IType;
import utils.IDict;

public interface IStmt {
    PrgState execute(PrgState prg) throws MyException;

    IStmt deepCopy();

    IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException;
}