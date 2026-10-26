package Statement;

import expression.Expression;
import state.PrgState;
import state.SymTableInterface;
import value.Value;
import Exception.MyException;
public class AssignmentStatement implements Statement{
    String varName;
    Expression exp;

    public AssignmentStatement(String varName, Expression exp){
        this.varName=varName;
        this.exp=exp;
    }

    @Override
    public PrgState execute(PrgState prgState) throws MyException {
        SymTableInterface symTable= prgState.getSymTable();
        if(!symTable.isDefined(varName)){
            throw new RuntimeException("Variable not defined");
        }
        Value value =exp.evaluate(symTable);
        if(!value.getType().equals(symTable.getType(varName))){
            throw new RuntimeException("Type is not the same");
        }
        symTable.updateVariable(varName,value);
        return prgState;
    }

    @Override
    public String toString(){
        return "Assignment Statement : [ " +
                "variable name= " + varName +
                "variable name=" + exp+
                " ]";
    }
}
