package Statement;

import state.PrgState;
import Exception.MyException;
public class NoOpStatement implements Statement{
    @Override
    public PrgState execute(PrgState prgState) throws MyException {
        return prgState;
    }
}
