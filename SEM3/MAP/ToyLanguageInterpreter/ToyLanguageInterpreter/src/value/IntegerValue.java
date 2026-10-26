package value;

import type.Type;

public class IntegerValue implements Value{
    private int value;

    public IntegerValue(int value){
        this.value=value;
    }

    @Override
    public Type getType() {
        return Type.INTEGER;
    }

    public int getValue() {
        return value;
    }

    @Override
    public boolean equals(Object obj){
        if(obj==this) return true;
        if(obj == null || obj.getClass()!=this.getClass()) return false;
        var comp= (IntegerValue) obj;
        return comp.getValue()==this.getValue();
    }

    @Override
    public String toString(){
        return "IntegerValue( "+ value +" )";
    }
}
