#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "symtab.h"
#include "pif.h"

extern FILE *yyin;
extern int yylex(void);

SymTab symtab;
Pif pif;

static FILE *err_file = NULL;
static int error_count = 0;

void lex_error(int line, const char *message, const char *text) {
    error_count++;
    fprintf(stderr, "Lexical error, line %d: %s: %s\n", line, message, text);
    if (err_file) fprintf(err_file, "line %d: %s: %s\n", line, message, text);
}

static FILE *open_out(const char *prefix, const char *ext) {
    char path[1024];
    snprintf(path, sizeof(path), "%s.%s", prefix, ext);
    FILE *f = fopen(path, "w");
    if (!f) { fprintf(stderr, "cannot create %s\n", path); exit(1); }
    return f;
}

int main(int argc, char **argv) {
    if (argc < 2) {
        fprintf(stderr, "Usage: %s <source file> [output prefix]\n", argv[0]);
        return 1;
    }
    yyin = fopen(argv[1], "r");
    if (!yyin) { fprintf(stderr, "cannot open %s\n", argv[1]); return 1; }

    char prefix[1024];
    if (argc >= 3) {
        snprintf(prefix, sizeof(prefix), "%s", argv[2]);
    } else {                                   /* default: source name without extension */
        snprintf(prefix, sizeof(prefix), "%s", argv[1]);
        char *dot = strrchr(prefix, '.');
        if (dot && strchr(dot, '/') == NULL) *dot = '\0';
    }

    err_file = open_out(prefix, "err");
    st_init(&symtab);
    pif_init(&pif);

    yylex();

    FILE *f_pif = open_out(prefix, "pif");
    FILE *f_st = open_out(prefix, "st");
    pif_print(&pif, f_pif);
    st_print(&symtab, f_st);
    if (error_count == 0) fprintf(err_file, "No lexical errors.\n");
    fclose(f_pif); fclose(f_st); fclose(err_file);

    printf("%s: %d tokens, %d ST entries, %d lexical errors -> %s.pif / %s.st / %s.err\n",
           argv[1], pif.size, symtab.count, error_count, prefix, prefix, prefix);

    pif_free(&pif);
    st_free(&symtab);
    fclose(yyin);
    return error_count == 0 ? 0 : 2;
}
