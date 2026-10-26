/* pif.h - Program Internal Form: the list of tokens in source order.
 * Keywords, operators and separators are stored with no ST position.
 * Identifiers and constants are stored as "id" / "const" plus the position
 * of their entry in the Symbol Table. */
#ifndef PIF_H
#define PIF_H

#include <stdio.h>
#include "symtab.h"

typedef struct {
    char *token;                   /* "if", "=", "->", "id", "const", ... */
    StPos pos;                     /* ST position, or {-1,-1} when none */
} PifEntry;

typedef struct {
    PifEntry *items;
    int size;
    int capacity;
} Pif;

void pif_init(Pif *p);
void pif_add(Pif *p, const char *token, StPos pos);
void pif_print(const Pif *p, FILE *out);
void pif_free(Pif *p);

#endif
