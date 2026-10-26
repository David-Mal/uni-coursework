package state;

import Statement.Statement;

import java.util.Stack;

public interface ExeStackInterface {
    void push(Statement statement);
    Statement pop();
    boolean isEmpty();
}
