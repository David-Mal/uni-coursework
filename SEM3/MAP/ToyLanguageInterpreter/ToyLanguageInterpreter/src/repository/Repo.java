package repository;

import state.PrgState;


import java.util.ArrayList;
import java.util.List;

public class Repo implements RepoInterface{

    private List<PrgState> repo=new ArrayList<>();
    private int current=-1;

    @Override
    public void addPrg(PrgState prg) {
        repo.add(prg);
        current++;
    }

    @Override
    public List<PrgState> getAllPrg() {
        return List.copyOf(repo);
    }

    @Override
    public PrgState getCrtPrg() {
        if (current<0 || current>=repo.size()){
            throw new RuntimeException("Index out of bounds");
        }
        return repo.get(current);
    }

    @Override
    public boolean isEmpty() {
        return repo.isEmpty();
    }

    @Override
    public int size() {
        return current;
    }
}
