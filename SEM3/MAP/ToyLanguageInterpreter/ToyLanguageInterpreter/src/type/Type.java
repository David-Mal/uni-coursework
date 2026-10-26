package type;

import value.BooleanValue;
import value.IntegerValue;
import value.Value;

public enum Type {
    BOOLEAN,
    INTEGER;

    public Value getDefaultValue(){
        switch (this) {
            case INTEGER -> {
                return new IntegerValue(0);
            }
            case BOOLEAN -> {
                return new BooleanValue(false);
            }
            default -> throw new RuntimeException("Invalid type");
        }
    }
}
