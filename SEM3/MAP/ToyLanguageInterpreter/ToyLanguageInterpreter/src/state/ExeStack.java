package state;

import Statement.Statement;

import java.util.LinkedList;
import java.util.List;

public class ExeStack implements ExeStackInterface{

    private final List<Statement> exeStack= new LinkedList<>();

    @Override
    public void push(Statement statement) {
        exeStack.addFirst(statement);
    }

    @Override
    public Statement pop() {
        return exeStack.removeFirst();
    }

    public boolean isEmpty(){
        return exeStack.isEmpty();
    }


    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("{");
        if (!exeStack.isEmpty()) {
            //sb.append(" | ");
            boolean first = true;
            for (Statement s : exeStack) {
                if (!first) sb.append(" | ");
                sb.append(s == null ? "null" : s.toString());
                first = false;
            }
        }

        sb.append("}");
        return sb.toString();
    }

}
