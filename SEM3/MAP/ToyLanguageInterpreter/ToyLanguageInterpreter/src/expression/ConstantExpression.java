package expression;

import state.SymTableInterface;
import value.Value;

public class ConstantExpression implements Expression{
    private Value value;

    public ConstantExpression(Value value){
        this.value=value;
    }

    @Override
    public Value evaluate(SymTableInterface symTable) {
        return value;
    }

    @Override
    public String toString(){
        return "Constant Expression: value= "+value;
    }
}
