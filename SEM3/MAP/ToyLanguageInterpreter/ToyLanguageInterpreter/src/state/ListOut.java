package state;

import java.util.ArrayList;
import java.util.List;

public class ListOut implements ListOutInterface{
    public final List<Object> outList=new ArrayList<>();


    @Override
    public void add(Object value) {
        outList.add(value);
    }

    @Override
    public String toString(){
        return outList.toString();
    }
}
