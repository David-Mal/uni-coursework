package state;

import type.Type;
import value.Value;

import java.util.HashMap;
import java.util.Map;

public class SymTable implements SymTableInterface{

    private final Map<String, Value> map=new HashMap<>();

    @Override
    public boolean isDefined(String varName) {
        return map.containsKey(varName);
    }

    @Override
    public Type getType(String vareName) {
        return map.get(vareName).getType();
    }

    @Override
    public void declareVariable(String varName, Type type) {
        map.put(varName,type.getDefaultValue());
    }

    @Override
    public void updateVariable(String varName, Value value) {
        map.put(varName,value);
    }

    @Override
    public Value getValue(String varName) {
        return map.get(varName);
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        for (Map.Entry<String, Value> entry : map.entrySet()) {
            sb.append(entry.getKey())
                    .append(" -> ")
                    .append(entry.getValue()).append(", ");
        }
        return sb.toString();
    }
}
