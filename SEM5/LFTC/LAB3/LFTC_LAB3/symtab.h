#ifndef SYMTAB_H
#define SYMTAB_H

#include <stdio.h>

#define ST_SIZE 97

typedef enum { ST_IDENTIFIER, ST_CONST_NUMBER, ST_CONST_STRING } StKind;

typedef struct StNode {
    char *lexeme;          
    StKind kind;
    struct StNode *next;
} StNode;

typedef struct {
    StNode *bucket[ST_SIZE];
    int count;                 
} SymTab;

typedef struct {
    int bucket;                    
    int index;
} StPos;

void   st_init(SymTab *st);
unsigned st_hash(const char *lexeme);
StPos  st_insert(SymTab *st, const char *lexeme, StKind kind);
StPos  st_lookup(const SymTab *st, const char *lexeme);        
void   st_print(const SymTab *st, FILE *out);
void   st_free(SymTab *st);

#endif
