package state;

import Statement.Statement;

import javax.swing.plaf.nimbus.State;
import java.util.Objects;
import Exception.MyException;

public class PrgState {
    private final ExeStackInterface exeStack;
    private final SymTableInterface symTable;
    private final ListOutInterface listOut;
    private Statement originalProgram;

    public PrgState(ExeStackInterface exeStack, SymTableInterface symTable, ListOutInterface listOut, Statement prg) throws MyException {
        this.exeStack=exeStack;
        this.listOut=listOut;
        this.symTable=symTable;
        this.exeStack.push(prg);

    }

    public SymTableInterface getSymTable() {
        return symTable;
    }

    public ListOutInterface getListOut() {
        return listOut;
    }

    public ExeStackInterface getExeStack() {
        return exeStack;
    }

    @Override
    public boolean equals(Object obj){
        if(obj == this) return true;
        if(obj==null || obj.getClass()!=this.getClass()) return false;
        var comp = (PrgState)obj;
        return Objects.equals(comp.getExeStack(),this.getExeStack()) &&
                Objects.equals(comp.getSymTable(),this.getSymTable()) &&
                Objects.equals(comp.getListOut(),this.getListOut());
    }

    @Override
    public String toString(){
        return "ProgramState[" +"\n"+
                "Execution Stack =" + exeStack +"\n"+
                "Symbol Table= " + symTable+"\n"+
                "List Out= " +listOut+
                "]"+"\n";
    }

}
