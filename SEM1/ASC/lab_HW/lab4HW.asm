bits 32

global start        

extern exit               
import exit msvcrt.dll    

segment data use32 class=data

    A dw 0x12AB
    B dw 0x1AB2
    C dw 0x2E1C
    D db 0  ; the sum of  the integers represented by the bits 5-9 of B + the bits 0-4 of A
    E db 0  ; the bits 10-14 of C
    F db 0  ; the result of D - E
    
    ; the bits 0-4 of A  =>  01011  => 11
    ; the bits 5-9 of B  =>  10101  => 21
    ; in D we should have 11 + 21 = 32
    
    ; the byte E is the integer represented by the bits 10-14 of C. Obtain the byte F by computing the subtraction D-E.
    ; the bits 10-14 of C  =>  01011  =>  11
    ;  F = D - E should be 32 - 11 = 21

segment code use32 class=code
    start:
        
        mov AX, [A]
        mov BX, [B]
        
        shl AX, 11
        shr AX, 11 ; the bits 0-4 of A
        
        shr BX, 5
        shl BX, 11
        shr BX, 11 ; the bits 5-9 of B
        
        ADD byte[D], AL
        ADD byte[D], BL
        mov AL, [D] ; the sum of the previously obtained numbers
        
        mov BX, [C]
        shl BX, 1
        shr BX, 11 ; E = the bits 10-14 of C
        
        SUB AL, BL 
        mov [F], AL

        push    dword 0
        call    [exit]
