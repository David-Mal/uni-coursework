package repository;

import java.util.List;

import exceptions.MyException;
import utils.PrgState;

public interface IRepository {
    void addPrg(PrgState prg);

    void logPrgStateExec(PrgState prg) throws MyException;

    List<PrgState> getPrgList();

    void setPrgList(List<PrgState> prgList);
}