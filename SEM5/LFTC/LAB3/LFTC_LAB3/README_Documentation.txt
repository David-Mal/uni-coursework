BarcaScript - Lab 3: Lexical analyzer (flex + C)
=================================================

1. WHAT THE PROGRAM DOES
Input : a BarcaScript source file (e.g. Program 1 from Lab 2) and the list of
        tokens from the lexical part of Barca_DSL_BNF_v2.txt.
Output: - PIF (Program Internal Form): the tokens of the program in order;
        - ST  (Symbol Table): every distinct identifier and constant, once;
        - lexical errors, with the line number.
The three outputs are written to <prefix>.pif, <prefix>.st and <prefix>.err and
a one-line summary is printed on the screen.

2. FILES
  lexer.l      flex specification: one regular expression per token type
  symtab.h/.c  Symbol Table - hash table implemented from scratch (required)
  pif.h/.c     PIF - dynamic array of (token, ST position)
  main.c       driver: opens files, runs the lexer, writes the three outputs
  Makefile     build and test commands
  tests/       program1.barca, program2.barca (from Lab 2), errors.barca

3. TOOLS AND HOW TO RUN
Tools: flex (lexer generator, produces C code), gcc, make.
  Ubuntu / Debian / WSL:  sudo apt install flex gcc make
  macOS:                  xcode-select --install   and   brew install flex
  Windows:                use WSL (recommended), or MSYS2:
                          pacman -S flex gcc make
Build:   make
Run:     ./lexer tests/program1.barca out/program1
         (creates out/program1.pif, out/program1.st, out/program1.err;
          create the folder first with: mkdir -p out)
All tests:  make test          Clean:  make clean
How it works: flex reads lexer.l and generates lex.yy.c, a C function
yylex() that returns after the whole file is scanned; gcc compiles it
together with the other .c files. Exit code: 0 = no lexical errors,
2 = lexical errors were found.

4. HOW THE TOKENS ARE DETECTED (lexer.l)
flex tries all patterns at the current position and applies two rules:
(1) the LONGEST match wins; (2) on a tie, the pattern written FIRST wins.
The order of the rules in lexer.l is therefore important.

  Token class        Pattern in lexer.l                      Goes to
  -----------------  --------------------------------------  --------------
  whitespace         [ \t\r]+  and  \n                       ignored
  comment            #[^\n]*                                 ignored
  keywords           Squad|if|else|end|for|in|and|or|        PIF only
                     announce|scout
  operators          == != >= <= > < -> = ? + -              PIF only
  separators         ( ) [ ] { } , :                         PIF only
  identifier         [A-Za-z]([A-Za-z0-9]|_)*                PIF + ST
  integer constant   0 | -?[1-9][0-9]*                       PIF + ST
  string constant    "[ !#-~]*"  (printable ASCII, no ")     PIF + ST

4.1 Identifiers
The pattern is the BNF rule <identifier>: a letter followed by letters, digits
or "_". The keyword rule is written BEFORE the identifier rule, so for the
text "if" both match with the same length and the keyword wins; for "iff" the
identifier pattern is longer and wins. This is how reserved words are
excluded from identifiers (change C2 of grammar v2). Matching is case
sensitive: Squad is a keyword, squad is an identifier.

4.2 Constants
- Integers: 0, or an optional minus, a non-zero digit and more digits. Because
  of the longest-match rule, "-5" is one number token, while "- assists" (with
  a space) is the remove operator and "->" is the select operator.
- Strings: between double quotes, any printable ASCII character except the
  quote itself (this includes ":" and "=", change C1).
In the ST a string is stored WITH its quotes, so the string "10" and the
number 10 are different entries.

4.3 Lexical errors
  Input                  Rule                          Message
  ---------------------  ----------------------------  -------------------------
  007                    0[0-9]+                       number with leading zero
  2player                [0-9]+ letter...              identifier cannot start
                                                       with a digit
  "abc  (no closing ")   "[^"\n]*                      unterminated string
  "café"                 "[^"\n]*"  (not valid string) illegal character in string
  @   ;   !   _          .  (any other character)      invalid character
The error rules are written AFTER the valid rules, so they only win when no
valid token is longer or equal. After an error the lexer continues, so one run
reports all errors, not only the first.

5. DATA STRUCTURES
5.1 Symbol Table = hash table with separate chaining (symtab.c)
- An array of 97 buckets (a prime number); each bucket is a linked list of
  StNode {lexeme, kind, next}. One single table holds identifiers and
  constants; "kind" says which one is each entry.
- Hash function: djb2, h = h*33 + c for every character, then h mod 97.
- The position of an entry is the pair (bucket, index in the chain).
  New entries are appended at the END of the chain and the table never
  resizes, so a position never changes - this is why the PIF can store it.
- Operations:
    st_init(st)                 empty all buckets
    st_hash(lexeme)             bucket number of a lexeme
    st_lookup(st, lexeme)       position, or (-1,-1) if absent
    st_insert(st, lexeme, kind) lookup first: if present return the existing
                                position (no duplicate), else append a new node
    st_print(st, out)           write the table, bucket by bucket
    st_free(st)                 free all nodes and strings
- Complexity: lookup and insert are O(1) on average, O(chain length) in the
  worst case.

5.2 PIF = dynamic array (pif.c)
Entry {token, position}. For keywords, operators and separators the token is
the symbol itself and the position is "-". For identifiers the token is "id"
and for constants "const", with the ST position. The array doubles its
capacity when full (pif_init, pif_add, pif_print, pif_free).

6. EXAMPLE (checked by running the program)
Source:
    minGoals = 10
    if minGoals > 15
      announce "High"
    end
PIF:                              ST:
  id      (39, 0)                   bucket index kind        lexeme
  =       -                         22     0     string      "High"
  const   (58, 0)                   39     0     identifier  minGoals
  if      -                         58     0     number      10
  id      (39, 0)   <- same entry   63     0     number      15
  >       -
  const   (63, 0)
  announce -
  const   (22, 0)
  end     -
The identifier minGoals appears twice in the PIF but only once in the ST, with
the same position (39, 0).

7. TEST RESULTS
  tests/program1.barca  319 tokens, 58 ST entries, 0 errors
  tests/program2.barca  171 tokens,  34 ST entries, 0 errors
  tests/errors.barca     40 tokens,  14 ST entries, 6 errors (one per kind,
                                                     lines 3, 4, 5, 6, 7, 8)
Verification: the token counts of both programs are identical to those of an
independent Python lexer written for the grammar check in Lab 2; the program
was also run with AddressSanitizer without findings.

8. LIMITATIONS
- Only integers (no decimals) and no escape sequence for " inside a string,
  as decided in the grammar.
- The lexer does not check the order of tokens (e.g. "if if"); this is the job
  of the syntactic analysis (next laboratory), which will read this PIF.
- "_" at the start of a name and ";" are reported as invalid characters.
