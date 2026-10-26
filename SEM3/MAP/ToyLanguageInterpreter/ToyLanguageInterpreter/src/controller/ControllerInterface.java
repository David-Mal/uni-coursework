package controller;

import state.PrgState;
import Exception.MyException;
import java.util.List;

public interface ControllerInterface {
    void add(PrgState prg);
    List<PrgState>getAll();
    PrgState oneStep(PrgState state) throws MyException;
    void allStep() throws MyException;
}
