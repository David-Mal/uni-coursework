bits 32 ; assembling for the 32 bits architecture

; declare the EntryPoint (a label defining the very first instruction of the program)
global start        

; declare external functions needed by our program
extern exit               ; tell nasm that exit exists even if we won't be defining it
import exit msvcrt.dll    ; exit is a function that ends the calling process. It is defined in msvcrt.dll
                          ; msvcrt.dll contains exit, printf and all the other important C-runtime specific functions

; our data is declared here (the variables needed by our program)
segment data use32 class=data
    ; ...
    ;d+[(a+b)*5-(c+c)*5]
    ;5+[25 - 20] = 10
    d dw 5
    a db 2
    b db 3
    c db 2
    a10 dw 0
    a20 dw 0

; our code starts here
segment code use32 class=code
    start:
        ; ...
    
        ; exit(0)
        
        
        MOV AL, [a]
        ADD AL, [b]
        MOV BL, 5
        MUL BL
        MOV [a10], AX
        
        MOV AL, [c]
        ADD AL, [c]
        ;MOV BL, 5
        MUL BL
        
        MOV [a20], AX
        MOV AX, [a10]
        
        SUB AX, [a20]
        ADD AX, [d]
        
        
        
        
        
        
        push    dword 0      ; push the parameter for exit onto the stack
        call    [exit]       ; call exit to terminate the program
