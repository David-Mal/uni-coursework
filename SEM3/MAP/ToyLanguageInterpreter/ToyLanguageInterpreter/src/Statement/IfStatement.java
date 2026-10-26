package Statement;

import expression.Expression;
import state.PrgState;
import value.BooleanValue;
import value.Value;
import Exception.MyException;
public class IfStatement implements Statement{
    Expression condition;
    Statement thenS;
    Statement elseS;

    public IfStatement(Expression condition, Statement thenS, Statement elseS) {
        this.condition = condition;
        this.thenS = thenS;
        this.elseS = elseS;
    }

    @Override
    public PrgState execute(PrgState prgState) throws MyException {
        Value value= condition.evaluate(prgState.getSymTable());
        if(value instanceof BooleanValue  booleanValue){
            if(booleanValue.getValue()){
                prgState.getExeStack().push(thenS);
            }
            else{
                prgState.getExeStack().push(elseS);
            }
        }
        return prgState;
    }
}


