CC     = gcc
CFLAGS = -Wall -Wextra -g

lexer: lex.yy.c symtab.c pif.c main.c symtab.h pif.h
	$(CC) $(CFLAGS) -o lexer lex.yy.c symtab.c pif.c main.c

lex.yy.c: lexer.l
	flex lexer.l

test: lexer
	mkdir -p out
	-./lexer tests/program1.barca out/program1
	-./lexer tests/program2.barca out/program2
	-./lexer tests/errors.barca out/errors

clean:
	rm -rf lexer lex.yy.c out
