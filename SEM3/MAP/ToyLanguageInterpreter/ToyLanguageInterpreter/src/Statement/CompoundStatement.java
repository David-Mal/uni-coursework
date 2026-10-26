package Statement;

import state.PrgState;
import Exception.MyException;
public class CompoundStatement implements Statement{
    private Statement first;
    private Statement second;

    public CompoundStatement(Statement first, Statement second){
        this.first=first;
        this.second=second;
    }


    @Override
    public PrgState execute(PrgState prgState) throws MyException {
        var stack=prgState.getExeStack();
        stack.push(second);
        stack.push(first);
        return prgState;
    }

    public Statement getFirst() {
        return first;
    }

    public Statement getSecond() {
        return second;
    }

    @Override
    public String toString(){
        return "Compound Statement:[" +
                "first =" + first+
                "second = " +second+
                " ]";
    }
}
