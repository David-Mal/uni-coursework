package expression;

import state.SymTableInterface;
import value.Value;

public class VariableExpression implements Expression{

    private String varName;


    public VariableExpression(String varName) {
        this.varName = varName;
    }

    @Override
    public Value evaluate(SymTableInterface symTable){
        if(!symTable.isDefined(varName)){
            throw new RuntimeException("Variable not defined");
        }
        return symTable.getValue(varName);
    }

    @Override
    public String toString() {
        return "VariableExpression{" +
                "varName='" + varName + '\'' +
                '}';
    }
}
