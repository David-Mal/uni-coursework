%{

#include <stdio.h>
#include <string.h>
#include "symtab.h"
#include "pif.h"

extern SymTab symtab;                      
extern Pif pif;
void lex_error(int line, const char *message, const char *text);

static const StPos NO_POS = { -1, -1 };

static void add_token(const char *token) { pif_add(&pif, token, NO_POS); }

static void add_symbol(const char *pif_token, StKind kind) {
    StPos pos = st_insert(&symtab, yytext, kind);
    pif_add(&pif, pif_token, pos);
}
%}

%option noyywrap nounput noinput yylineno

LETTER      [A-Za-z]
DIGIT       [0-9]
NONZERO     [1-9]

%%

[ \t\r]+                                   { /* whitespace: ignored */ }
\n                                         { /* line counted by yylineno */ }
#[^\n]*                                    { /* comment: ignored */ }

Squad|if|else|end|for|in|and|or|announce|scout   { add_token(yytext); }

"=="|"!="|">="|"<="|">"|"<"|"->"|"="|"?"|"+"|"-"   { add_token(yytext); }
"("|")"|"["|"]"|"{"|"}"|","|":"                    { add_token(yytext); }

{LETTER}({LETTER}|{DIGIT}|_)*              { add_symbol("id", ST_IDENTIFIER); }

0|-?{NONZERO}{DIGIT}*                      { add_symbol("const", ST_CONST_NUMBER); }

\"[ !#-~]*\"                               { add_symbol("const", ST_CONST_STRING); }

 /* ---------------- lexical errors ---------------- */
0{DIGIT}+                                  { lex_error(yylineno, "number with leading zero", yytext); }
{DIGIT}+({LETTER}|_)({LETTER}|{DIGIT}|_)*  { lex_error(yylineno, "identifier cannot start with a digit", yytext); }
\"[^\"\n]*\"                               { lex_error(yylineno, "illegal character inside string", yytext); }
\"[^\"\n]*                                 { lex_error(yylineno, "unterminated string (missing closing quote)", yytext); }
.                                          { lex_error(yylineno, "invalid character", yytext); }

%%
