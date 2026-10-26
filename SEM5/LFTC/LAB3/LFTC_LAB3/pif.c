#include <stdlib.h>
#include <string.h>
#include "pif.h"

void pif_init(Pif *p) {
    p->items = NULL;
    p->size = 0;
    p->capacity = 0;
}

void pif_add(Pif *p, const char *token, StPos pos) {
    if (p->size == p->capacity) {
        p->capacity = p->capacity ? p->capacity * 2 : 64;
        p->items = realloc(p->items, p->capacity * sizeof(PifEntry));
        if (!p->items) { fprintf(stderr, "out of memory\n"); exit(1); }
    }
    p->items[p->size].token = malloc(strlen(token) + 1);
    if (!p->items[p->size].token) { fprintf(stderr, "out of memory\n"); exit(1); }
    strcpy(p->items[p->size].token, token);
    p->items[p->size].pos = pos;
    p->size++;
}

void pif_print(const Pif *p, FILE *out) {
    fprintf(out, "PROGRAM INTERNAL FORM (%d tokens)\n", p->size);
    fprintf(out, "%-10s %s\n", "token", "ST position (bucket, index)");
    for (int i = 0; i < p->size; i++) {
        const PifEntry *e = &p->items[i];
        if (e->pos.bucket < 0)
            fprintf(out, "%-10s -\n", e->token);
        else
            fprintf(out, "%-10s (%d, %d)\n", e->token, e->pos.bucket, e->pos.index);
    }
}

void pif_free(Pif *p) {
    for (int i = 0; i < p->size; i++) free(p->items[i].token);
    free(p->items);
    p->items = NULL;
    p->size = p->capacity = 0;
}
