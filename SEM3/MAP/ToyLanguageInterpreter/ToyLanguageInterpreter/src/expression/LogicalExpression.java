package expression;

import state.SymTableInterface;
import type.Type;
import value.BooleanValue;
import value.Value;

public class LogicalExpression implements Expression{
    private String operator;
    private Expression left;
    private Expression right;

    public LogicalExpression(String operator, Expression left, Expression right) {
        this.operator = operator;
        this.left = left;
        this.right = right;
    }


    @Override
    public Value evaluate(SymTableInterface symTable) {
        var leftTerm=left.evaluate(symTable);
        var rightTerm=right.evaluate(symTable);
        checkTypes(leftTerm,rightTerm,Type.BOOLEAN);
        BooleanValue leftValue=(BooleanValue) leftTerm;
        BooleanValue rightValue=(BooleanValue) rightTerm;
        return evaluateBooleanExpr(leftValue,rightValue,operator);
    }

    private void checkTypes(Value leftTerm, Value rightTerm, Type type){
        if(leftTerm.getType()!=type || rightTerm.getType()!=type){
            throw new RuntimeException("Types are not the same");
        }
    }
    private BooleanValue evaluateBooleanExpr(BooleanValue leftValue,BooleanValue rightValue, String operator){
        return switch (operator) {
            case "&&" -> new BooleanValue(leftValue.getValue() && rightValue.getValue());
            case "||" -> new BooleanValue(leftValue.getValue() || rightValue.getValue());
            default -> throw new RuntimeException("Operand not supported");
        };
    }

    @Override
    public String toString() {
        return "LogicalExpression{" +
                "operator='" + operator + '\'' +
                ", left=" + left +
                ", right=" + right +
                '}';
    }
}
