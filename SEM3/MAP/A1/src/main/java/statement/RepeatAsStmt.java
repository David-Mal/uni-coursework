package statement;

import exceptions.*;
import utils.PrgState;
import utils.IDict;
import utils.IStack;
import type.IType;
import type.BoolType;
import exp.IExp;

public class RepeatAsStmt implements IStmt {
    private IStmt stmt;
    private IExp exp;

    public RepeatAsStmt(IStmt stmt, IExp exp) {
        this.stmt = stmt;
        this.exp = exp;
    }

    @Override
    public PrgState execute(PrgState state) throws MyException {
        IStack<IStmt> stack = state.getExeStack();
        IStmt transformed = new CompStmt(stmt, new WhileStmt(exp, stmt));
        stack.push(transformed);
        return null;
    }

    @Override
    public IDict<String, IType> typecheck(IDict<String, IType> typeEnv) throws MyException {
        try {
            IType typeExp = exp.typecheck(typeEnv);
            if (typeExp.equals(new BoolType())) {
                stmt.typecheck(typeEnv.deepCopy());
                return typeEnv;
            } else {
                throw new MyException("RepeatAs Error: Condition is not a boolean");
            }
        } catch (ExpressionException e) {
            throw new MyException(e.getMessage());
        }
    }

    @Override
    public IStmt deepCopy() {
        return new RepeatAsStmt(stmt.deepCopy(), exp.deepCopy());
    }

    @Override
    public String toString() {
        return "repeat (" + stmt.toString() + ") as (" + exp.toString() + ")";
    }
}