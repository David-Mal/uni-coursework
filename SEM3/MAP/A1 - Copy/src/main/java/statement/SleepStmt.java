package statement;

import exceptions.ADTException;
import exceptions.MyException;
import utils.IDict;
import utils.PrgState;
import utils.IStack;
import type.IType;

public class SleepStmt implements IStmt {
    private int value;

    public SleepStmt(int value) {
        this.value = value;
    }

    @Override
    public PrgState execute(PrgState state) throws MyException {
        if (value > 0) {
            IStack<IStmt> stack = state.getExeStack();
            stack.push(new SleepStmt(value - 1));
        }
        return null;
    }

    @Override
    public IStmt deepCopy() {
        return new SleepStmt(value);
    }

    @Override
    public IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException {
        return typeEnv;
    }

    @Override
    public String toString() {
        return "sleep(" + value + ")";
    }
}