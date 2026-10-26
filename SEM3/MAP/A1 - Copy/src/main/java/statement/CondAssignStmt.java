package statement;

import exceptions.ADTException;
import exceptions.MyException;
import utils.PrgState;
import utils.IDict;
import utils.IStack;
import type.IType;
import exp.IExp;

public class CondAssignStmt implements IStmt {
    private final String variable;
    private final IExp exp1;
    private final IExp exp2;
    private final IExp exp3;

    public CondAssignStmt(String variable, IExp exp1, IExp exp2, IExp exp3) {
        this.variable = variable;
        this.exp1 = exp1;
        this.exp2 = exp2;
        this.exp3 = exp3;
    }

    @Override
    public PrgState execute(PrgState state) throws MyException {
        IStack<IStmt> stack = state.getExeStack();

        IStmt transformed = new IfStmt(
                exp1,
                new AssignStmt(variable, exp2),
                new AssignStmt(variable, exp3)
        );

        stack.push(transformed);
        return null;
    }

    @Override
    public IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException {
        return typeEnv;
    }

    @Override
    public IStmt deepCopy() {
        return new CondAssignStmt(variable, exp1.deepCopy(), exp2.deepCopy(), exp3.deepCopy());
    }

    @Override
    public String toString() {
        return variable + "=(" + exp1.toString() + ")?" + exp2.toString() + ":" + exp3.toString();
    }
}