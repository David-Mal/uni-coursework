package exp;

import exceptions.ExpressionException;
import exceptions.MyException;
import type.BoolType;
import type.IType;
import value.BoolValue;
import value.IValue;
import utils.IDict;
import utils.IHeap;

public class NotExp implements IExp {
    private IExp exp;

    public NotExp(IExp exp) {
        this.exp = exp;
    }

    @Override
    public IValue eval(IDict<String, IValue> symTable, IHeap<Integer, IValue> heap) throws MyException {
        IValue val;
        try {
            val = exp.eval(symTable, heap);
        } catch (ExpressionException e) {
            throw new MyException(e.getMessage());
        }

        if (val.getType().equals(new BoolType())) {
            BoolValue boolVal = (BoolValue) val;
            return new BoolValue(!boolVal.getVal());
        } else {
            throw new MyException("Operand is not a boolean");
        }
    }

    @Override
    public IType typecheck(IDict<String, IType> typeEnv) throws ExpressionException {
        IType type = exp.typecheck(typeEnv);
        if (type.equals(new BoolType())) {
            return new BoolType();
        } else {
            throw new ExpressionException("NOT expression: operand is not boolean");
        }
    }

    @Override
    public IExp deepCopy() {
        return new NotExp(exp.deepCopy());
    }

    @Override
    public String toString() {
        return "!(" + exp.toString() + ")";
    }
}