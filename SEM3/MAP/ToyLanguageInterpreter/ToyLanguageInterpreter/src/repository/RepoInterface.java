package repository;

import state.PrgState;

import java.util.List;

public interface RepoInterface {
    void addPrg(PrgState prg);
    List<PrgState> getAllPrg();
    PrgState getCrtPrg();
    boolean isEmpty();
    int size();
}
