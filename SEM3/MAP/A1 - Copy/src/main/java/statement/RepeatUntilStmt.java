package statement;

import exceptions.ADTException;
import exceptions.ExpressionException;
import exceptions.MyException;
import exp.NotExp;
import type.BoolType;
import utils.IDict;
import utils.PrgState;
import utils.IStack;
import type.IType;
import exp.IExp;
import exp.LogicExp;

public class RepeatUntilStmt implements IStmt {
    private final IStmt stmt;
    private final IExp exp;

    public RepeatUntilStmt(IStmt stmt, IExp exp) {
        this.stmt = stmt;
        this.exp = exp;
    }

    @Override
    public PrgState execute(PrgState state) throws MyException {
        IStack<IStmt> stack = state.getExeStack();

        IStmt transformed = new CompStmt(
                stmt,
                new WhileStmt(new NotExp(exp), stmt)
        );

        stack.push(transformed);
        return null;
    }

    @Override
    public IStmt deepCopy() {
        return new RepeatUntilStmt(stmt.deepCopy(), exp.deepCopy());
    }

    @Override
    public IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException {
        try {
            IType typeExp = exp.typecheck(typeEnv);

            if (typeExp.equals(new BoolType())) {

                stmt.typecheck(typeEnv.deepCopy());
                return typeEnv;
            } else {
                throw new MyException("RepeatUntil Error: Condition expression is not a boolean");
            }
        } catch (ExpressionException e) {
            throw new MyException(e.getMessage());
        }
    }

    @Override
    public String toString() {
        return "repeat(" + stmt.toString() + ") until " + exp.toString();
    }
}