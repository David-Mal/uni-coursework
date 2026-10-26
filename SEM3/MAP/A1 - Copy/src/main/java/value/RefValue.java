package value;

import type.IType;
import type.RefType;

public class RefValue implements IValue {
    int address;
    IType type;

    public RefValue(int address, IType type) {
        this.address = address;
        this.type = type;
    }

    @Override
    public IType getType() {
        return new RefType(type);
    }

    public int getAddress() {
        return address;
    }

    @Override
    public IValue deepCopy() {
        return new RefValue(address, type);
    }

    @Override
    public String toString() {
        return "RefValue(" + address + ", " + type + ')';
    }

    @Override
    public boolean equals(IValue other) {
        return other instanceof RefValue && address == ((RefValue) other).address;
    }
}
