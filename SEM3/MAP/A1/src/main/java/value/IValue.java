package value;

import type.IType;

public interface IValue {
    IType getType();

    IValue deepCopy();

    boolean equals(IValue other);
}
