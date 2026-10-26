#include <stdlib.h>
#include <string.h>
#include "symtab.h"

void st_init(SymTab *st) {
    for (int i = 0; i < ST_SIZE; i++) st->bucket[i] = NULL;
    st->count = 0;
}

unsigned st_hash(const char *lexeme) {
    unsigned long h = 5381;
    for (const unsigned char *p = (const unsigned char *)lexeme; *p; p++)
        h = h * 33 + *p;
    return (unsigned)(h % ST_SIZE);
}

StPos st_lookup(const SymTab *st, const char *lexeme) {
    StPos pos = { -1, -1 };
    unsigned b = st_hash(lexeme);
    int i = 0;
    for (StNode *n = st->bucket[b]; n != NULL; n = n->next, i++) {
        if (strcmp(n->lexeme, lexeme) == 0) {
            pos.bucket = (int)b;
            pos.index = i;
            return pos;
        }
    }
    return pos;
}

StPos st_insert(SymTab *st, const char *lexeme, StKind kind) {
    StPos pos = st_lookup(st, lexeme);
    if (pos.bucket != -1) return pos;
    StNode *node = malloc(sizeof(StNode));
    if (!node) { fprintf(stderr, "out of memory\n"); exit(1); }
    node->lexeme = malloc(strlen(lexeme) + 1);
    if (!node->lexeme) { fprintf(stderr, "out of memory\n"); exit(1); }
    strcpy(node->lexeme, lexeme);
    node->kind = kind;
    node->next = NULL;

    unsigned b = st_hash(lexeme);
    int i = 0;
    if (st->bucket[b] == NULL) {
        st->bucket[b] = node;
    } else {
        StNode *last = st->bucket[b];
        i = 1;
        while (last->next != NULL) { last = last->next; i++; }
        last->next = node;
    }
    st->count++;
    pos.bucket = (int)b;
    pos.index = i;
    return pos;
}

static const char *kind_name(StKind k) {
    switch (k) {
        case ST_IDENTIFIER:   return "identifier";
        case ST_CONST_NUMBER: return "number";
        default:              return "string";
    }
}

void st_print(const SymTab *st, FILE *out) {
    fprintf(out, "SYMBOL TABLE (hash table, %d buckets, %d entries)\n", ST_SIZE, st->count);
    fprintf(out, "%-8s %-6s %-12s %s\n", "bucket", "index", "kind", "lexeme");
    for (int b = 0; b < ST_SIZE; b++) {
        int i = 0;
        for (StNode *n = st->bucket[b]; n != NULL; n = n->next, i++)
            fprintf(out, "%-8d %-6d %-12s %s\n", b, i, kind_name(n->kind), n->lexeme);
    }
}

void st_free(SymTab *st) {
    for (int b = 0; b < ST_SIZE; b++) {
        StNode *n = st->bucket[b];
        while (n != NULL) {
            StNode *next = n->next;
            free(n->lexeme);
            free(n);
            n = next;
        }
        st->bucket[b] = NULL;
    }
    st->count = 0;
}
