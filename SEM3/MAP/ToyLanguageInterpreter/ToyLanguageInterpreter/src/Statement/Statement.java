package Statement;

import state.PrgState;
import Exception.MyException;
public interface Statement {
    PrgState execute(PrgState prgState) throws MyException;
}
