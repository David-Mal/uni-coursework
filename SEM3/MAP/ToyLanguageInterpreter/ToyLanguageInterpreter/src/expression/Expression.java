package expression;

import state.SymTable;
import state.SymTableInterface;
import value.Value;

public interface Expression {
    Value evaluate(SymTableInterface symTable);
}
