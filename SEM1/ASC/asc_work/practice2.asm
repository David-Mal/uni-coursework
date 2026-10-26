bits 32
global start

extern exit, printf
import exit msvcrt.dll
import printf msvcrt.dll

;determina prima cifra a unui numar

segment data use32 class=data
    a db 76, 32, 45
    la equ $-a
    b times la db 0
    format db '%d',10, 0
    cifra_minima db 10
    space dd 10
    value db 0
segment code use32 class=code
    start:
    
    mov ECX, la
    mov ESI, a
    mov EDI, b
    jecxz stop_loop
    array_loop: ;----------------------------------
    lodsb ; AL = element din a
    
    movzx AX, AL
    mov BL, 10
    mov byte[cifra_minima], 10
    cat_timp:
    mov AH, 0
    div BL ; AH = cifra curenta
    cmp AH, byte[cifra_minima]
    jnb skipp
    mov byte[cifra_minima], AH
    skipp:
    cmp AL, 0
    jne cat_timp
    
    mov AL, byte[cifra_minima]
    stosb
    loop array_loop ;----------------------------------
    stop_loop:
    
    ; afisare valori b
    mov ESI, b
    mov ECX, la
    jecxz stop
    
    array_loop2:
    push ecx
    push esi
    lodsb ; AL = element din b
    mov byte[value], AL
    push dword[value]
    push dword format
    call [printf]
    add ESP, 8
    pop esi
    inc esi
    pop ecx
    loop array_loop2
    stop:
    
    push dword 0
    call [exit]