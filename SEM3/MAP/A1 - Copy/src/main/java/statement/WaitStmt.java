package statement;

import exceptions.ADTException;
import exceptions.MyException;
import utils.IDict;
import utils.PrgState;
import utils.IStack;
import type.IType;
import value.IntValue;
import exp.ConstantValue;

public class WaitStmt implements IStmt {
    private final int number;

    public WaitStmt(int number) {
        this.number = number;
    }

    @Override
    public PrgState execute(PrgState state) throws MyException {
        if (number > 0) {
            IStack<IStmt> stack = state.getExeStack();
            // Push wait(number-1) first, then print(number) so print is executed first
            stack.push(new WaitStmt(number - 1));
            stack.push(new PrintStmt(new ConstantValue(new IntValue(number))));
        }
        return null;
    }

    @Override
    public IStmt deepCopy() {
        return new WaitStmt(number);
    }

    @Override
    public IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException {
        return typeEnv;
    }

    @Override
    public String toString() {
        return "wait(" + number + ")";
    }
}