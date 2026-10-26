package view;
import Statement.Statement;
import Statement.AssignmentStatement;
import Statement.CompoundStatement;
import Statement.PrintStatement;
import Statement.VarDeclStatement;
import expression.ArithmeticalExpression;
import expression.ConstantExpression;
import expression.VariableExpression;
import type.Type;
import value.IntegerValue;
import repository.Repo;
import state.ExeStack;
import state.ListOut;
import state.PrgState;
import state.SymTable;
import controller.Controller;
import Exception.MyException;
import java.util.Scanner;

public class View {
    public void run() throws MyException {
        Scanner scanner = new Scanner(System.in);

        // Example 1: int v; v = 2; print(v)
        Statement ex1 = new CompoundStatement(
                new VarDeclStatement(Type.INTEGER, "v"),
                new CompoundStatement(
                        new AssignmentStatement("v", new ConstantExpression(new IntegerValue(2))),
                        new PrintStatement(new VariableExpression("v"))
                )
        );

        // Example 2: int a; int b; a=2+3*5; b=a+1; print(b)
        Statement ex2 = new CompoundStatement(
                new VarDeclStatement(Type.INTEGER, "a"),
                new CompoundStatement(
                        new VarDeclStatement(Type.INTEGER, "b"),
                        new CompoundStatement(
                                new AssignmentStatement("a", new ArithmeticalExpression("+",
                                        new ConstantExpression(new IntegerValue(2)),
                                        new ArithmeticalExpression("*",
                                                new ConstantExpression(new IntegerValue(3)),
                                                new ConstantExpression(new IntegerValue(5))
                                        ))),
                                new CompoundStatement(
                                        new AssignmentStatement("b",
                                                new ArithmeticalExpression("+",
                                                        new VariableExpression("a"),
                                                        new ConstantExpression(new IntegerValue(1))
                                                )),
                                        new PrintStatement(new VariableExpression("b"))
                                )
                        )
                )
        );

        //Statement ex3=

        System.out.println("Select a program to execute:");
        System.out.println("1. Simple variable and print");
        System.out.println("2. Arithmetic expressions and print");
        System.out.print("Enter choice: ");

        int choice = scanner.nextInt();

        Statement selectedProgram = (choice == 1) ? ex1 : ex2;


        Repo repo = new Repo();
        PrgState prg = new PrgState(new ExeStack(), new SymTable(), new ListOut(), selectedProgram);
        Controller ctrl = new Controller(repo);
        ctrl.setViewFlagTrue();
        ctrl.add(prg);

        ctrl.allStep();
    }
}
