package Statement;

import state.PrgState;
import type.Type;
import Exception.MyException;
public class VarDeclStatement implements Statement{
    private String varName;
    private Type type;

    public VarDeclStatement(Type type, String varName){
        this.varName=varName;
        this.type=type;
    }

    @Override
    public PrgState execute(PrgState prgState) throws MyException {
        var symTable=prgState.getSymTable();
        if(symTable.isDefined(varName)){
            throw new RuntimeException("Variable already defined");
        }
        symTable.declareVariable(varName,type);
        return prgState;
    }

    public Type getType(){
        return type;
    }

    public String getVarName(){
        return varName;
    }

    @Override
    public String toString(){
        return "Variable Declaration: type= "+type +", variable name=" +varName;
    }

}
