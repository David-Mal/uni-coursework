package expression;

import state.SymTableInterface;
import type.Type;
import value.IntegerValue;
import value.Value;

public class ArithmeticalExpression implements Expression{
    private String operator;
    private Expression left;
    private Expression right;

    public ArithmeticalExpression(String operator, Expression left, Expression right) {
        this.operator = operator;
        this.left = left;
        this.right = right;
    }

    @Override
    public Value evaluate(SymTableInterface symTable) {
        var leftTerm= left.evaluate(symTable);
        var rightTerm= right.evaluate(symTable);
        checkTypes(leftTerm,rightTerm, Type.INTEGER);
        var leftValue=(IntegerValue) leftTerm;
        var rightValue=(IntegerValue) rightTerm;
        return evaluateArithExpr(leftValue,rightValue,operator);
    }

    private void checkTypes(Value leftTerm, Value rightTerm, Type type){
        if(leftTerm.getType()!=type || rightTerm.getType()!=type){
            throw new RuntimeException("Types are not the same for " + operator);
        }
    }

    private IntegerValue evaluateArithExpr(IntegerValue leftValue, IntegerValue rightValue, String operator){
        switch (operator) {
            case "+" : return new IntegerValue(leftValue.getValue()+rightValue.getValue());
            case "-" : return new IntegerValue(leftValue.getValue()- rightValue.getValue());
            case "*" :return  new IntegerValue(leftValue.getValue()* rightValue.getValue());
            case "/":
                if(rightValue.getValue()==0){
                    throw new RuntimeException("Division by 0 not supported");
                }
                return new IntegerValue(leftValue.getValue()/ rightValue.getValue());
            default:
                throw new RuntimeException("Operator not found");
        }
    }

    @Override
    public String toString() {
        return "ArithmeticalExpression{" +
                "operator='" + operator + '\'' +
                ", left=" + left +
                ", right=" + right +
                '}';
    }
}
