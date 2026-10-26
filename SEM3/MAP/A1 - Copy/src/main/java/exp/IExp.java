
package exp;

import exceptions.ExpressionException;
import exceptions.MyException;
import type.IType;
import value.IValue;
import utils.IDict;
import utils.IHeap;

public interface IExp {
    IValue eval(IDict<String, IValue> symTable, IHeap<Integer, IValue> heap)
            throws MyException, ExpressionException;

    IType typecheck(IDict<String, IType> typeEnv) throws ExpressionException;

    IExp deepCopy();
}
