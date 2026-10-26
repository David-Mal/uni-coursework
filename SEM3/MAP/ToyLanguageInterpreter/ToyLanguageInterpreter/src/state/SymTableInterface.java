package state;

import type.Type;
import value.Value;

public interface SymTableInterface {
    boolean isDefined(String varName);
    Type getType(String vareName);
    void declareVariable(String varName, Type type);
    void updateVariable(String varName, Value value);
    Value getValue(String varName);
}
