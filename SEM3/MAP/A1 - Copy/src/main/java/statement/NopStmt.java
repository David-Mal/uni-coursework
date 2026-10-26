package statement;

import exceptions.MyException;
import utils.PrgState;
import type.IType;
import utils.IDict;

public class NopStmt implements IStmt {
    @Override
    public PrgState execute(PrgState prg) throws MyException {
        return null;
    }

    @Override
    public String toString() {
        return "NOP";
    }

    @Override
    public IStmt deepCopy() {
        return new NopStmt();
    }

    @Override
    public IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException {
        return typeEnv;
    }
}