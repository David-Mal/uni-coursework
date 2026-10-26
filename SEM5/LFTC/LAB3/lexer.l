%{
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

/* --- Implementare Tabela de Simboluri (Hash Table) --- */
#define HASH_SIZE 211

typedef struct HashNode {
    char* token;
    int id;
    struct HashNode* next;
} HashNode;

HashNode* hashTable[HASH_SIZE];
int st_counter = 0; // ID unic pentru fiecare constanta/identificator

// Functie de hash (djb2)
int hash(char* str) {
    unsigned long hash = 5381;
    int c;
    while ((c = *str++))
        hash = ((hash << 5) + hash) + c;
    return hash % HASH_SIZE;
}

// Inserare si cautare in ST
int insertST(char* token) {
    int index = hash(token);
    HashNode* current = hashTable[index];
    
    // Cauta daca exista deja
    while (current != NULL) {
        if (strcmp(current->token, token) == 0) {
            return current->id;
        }
        current = current->next;
    }
    
    // Altfel, insereaza la inceputul listei inlantuite
    HashNode* newNode = (HashNode*)malloc(sizeof(HashNode));
    newNode->token = strdup(token);
    newNode->id = st_counter++;
    newNode->next = hashTable[index];
    hashTable[index] = newNode;
    
    return newNode->id;
}

/* --- Implementare Forma Interna a Programului (PIF) --- */
typedef struct {
    char token[50];
    int st_pos;
} PIF_Entry;

PIF_Entry pif[10000];
int pif_size = 0;

void addPIF(char* token, int st_pos) {
    strcpy(pif[pif_size].token, token);
    pif[pif_size].st_pos = st_pos;
    pif_size++;
}

int line_num = 1;
%}

%option noyywrap

/* Clase de caractere si definitii regex */
LETTER          [a-zA-Z]
DIGIT           [0-9]
NON_ZERO        [1-9]
ID              {LETTER}({LETTER}|{DIGIT}|_)*
NUMBER          0|-?{NON_ZERO}{DIGIT}*
STRING          \"[^\"]*\"

%%

"Squad"|"if"|"else"|"end"|"for"|"in"|"and"|"or"|"announce"|"scout" { addPIF(yytext, -1); }

"=="|"!="|">"|"<"|">="|"<="|"="|"\?"|"\+"|"\-"|"\->"|"\("|"\)"|"\["|"\]"|"\{"|"\}"|","|":" { addPIF(yytext, -1); }

{ID} {
    int pos = insertST(yytext);
    addPIF("id", pos);
}

{NUMBER} {
    int pos = insertST(yytext);
    addPIF("const", pos);
}

{STRING} {
    int pos = insertST(yytext);
    addPIF("const", pos);
}

#[^\n]* { /* ignora comentariile (pana la finalul liniei) */ }

[ \t\r]+ { /* ignora spatiile albe */ }

\n { line_num++; }

. { printf("Lexical error at line %d: Unrecognized token '%s'\n", line_num, yytext); }

%%

void printST() {
    printf("\n=== Symbol Table (Hash Table) ===\n");
    for (int i = 0; i < HASH_SIZE; i++) {
        HashNode* current = hashTable[i];
        while (current != NULL) {
            printf("ID: %d \t| Token: %s\n", current->id, current->token);
            current = current->next;
        }
    }
}

void printPIF() {
    printf("\n=== Program Internal Form (PIF) ===\n");
    for (int i = 0; i < pif_size; i++) {
        printf("(%s, %d)\n", pif[i].token, pif[i].st_pos);
    }
}

int main(int argc, char** argv) {
    if (argc > 1) {
        FILE *file = fopen(argv[1], "r");
        if (!file) {
            printf("Error opening file.\n");
            return 1;
        }
        yyin = file;
    }
    
    printf("Starting Lexical Analysis...\n");
    yylex();
    
    printPIF();
    printST();
    
    return 0;
}