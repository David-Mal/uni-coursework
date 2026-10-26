package Statement;

import expression.Expression;
import state.PrgState;
import Exception.MyException;
public class PrintStatement implements Statement{

    private Expression expression;

    public PrintStatement(Expression expr){
        this.expression=expr;
    }

    @Override
    public PrgState execute(PrgState prgState) throws MyException {
        prgState.getListOut().add(expression.evaluate(prgState.getSymTable()));
        return prgState;
    }

    public Expression getExpression() {
        return expression;
    }

    @Override
    public String toString(){
        return "Print Statement: " +
                "Expression= "+expression;
    }
}
