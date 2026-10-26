bits 32
global start

extern exit
import exit msvcrt.dll
segment data use32 class=data
    ; signed representation
    ; x-(a*a+b)/(a+c/a)
    ; 5 - (-5 * -5 - 11) / (-5 + 10 / -5) = 5 - 14 / (-7) = 5 + 2 = 7
    ; a,c-byte; b-doubleword; x-qword
    a db -5
    b dd -11
    c db 10
    x dq 5
    
segment code use32 class=code
start:
    
    MOV AL, [c]
    MOV AH, 0
    IDIV BYTE[a]    ; AL = c / a and AH = c % a
    cbw
    ADD AL, [a]     ; AX = a + c / a
    MOV CX, AX      ; CX = a + c / a        -7
    
    mov AL, 0
    mov AH, 0
    MOV AL, [a]
    MUL AL          ; AX = a*a (a*a is always positive)
    cwd             ; DX:AX = a*a
    
    push DX
    push AX
    pop EBX         ; EBX = a * a
    ADD EBX, dword[b]     ; EBX = (a*a+b)   14   
    
    push EBX
    pop AX
    pop DX                ; DX:AX = (a*a+b)
    IDIV CX         ; AX = (a*a+b) / (a+c/a) and DX = (a*a+b) % (a+c/a)
    
    
    push DX
    push AX
    pop EBX
    
    
    mov EAX, [x]
    cdq
    SUB EAX, EBX
    
    push dword 0
    call [exit]
    