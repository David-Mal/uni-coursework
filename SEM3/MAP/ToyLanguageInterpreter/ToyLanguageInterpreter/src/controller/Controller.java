package controller;

import Statement.Statement;
import repository.Repo;
import repository.RepoInterface;
import state.ExeStackInterface;
import state.PrgState;
import Exception.MyException;
import java.util.List;

public class Controller implements ControllerInterface{

    boolean viewFlag=false;
    RepoInterface repo;

    public Controller(){
        RepoInterface repo= new Repo();
    }

    public Controller(RepoInterface repo){
        this.repo=repo;
    }

    public void setViewFlagTrue(){
        viewFlag=true;
    }

    public void setViewFlagFalse() {
        this.viewFlag = false;
    }

    @Override
    public void add(PrgState prg) {
        repo.addPrg(prg);
    }

    @Override
    public List<PrgState> getAll() {
        return repo.getAllPrg();
    }

    @Override
    public PrgState oneStep(PrgState state) throws MyException {
        ExeStackInterface stack= state.getExeStack();
        if(stack.isEmpty()){
            throw new MyException("Execution stack is empty");
        }
        Statement st=stack.pop();
        try {
            return st.execute(state);
        } catch (MyException e) {
            throw new MyException(e);
        }
    }

    @Override
    public void allStep() throws MyException {
        PrgState state=repo.getCrtPrg();
        if(viewFlag) display(state);
        while(!state.getExeStack().isEmpty()) {
            oneStep(state);
            if (viewFlag) display(state);
        }
    }
    private void display(PrgState state){
        System.out.println(state);
    }
}
