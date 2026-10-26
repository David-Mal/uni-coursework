package value;

import type.Type;

public class BooleanValue implements Value{
    public boolean value;

    public BooleanValue(boolean value){
        this.value=value;
    }

    @Override
    public Type getType() {
        return Type.BOOLEAN;
    }

    public boolean getValue(){
        return value;
    }

    @Override
    public boolean equals(Object obj){
        if(obj==this)return true;
        if(obj==null || obj.getClass()!=this.getClass())return false;
        var comp=(BooleanValue)obj;
        return comp.getValue()==this.getValue();
    }
}
