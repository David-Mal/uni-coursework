bits 32

global start        

extern exit               
import exit msvcrt.dll    

segment data use32 class=data
    
    ; A byte string S of length l is given. Obtain the string D of length l-1, such that each element of D is the quotient of two consecutive ; elements of S, i.e. D(i) = S(i) / S(i+1).
    S db 1, 6, 3, 1
    ls equ $-S
    D times ls-1 db 0
    
segment code use32 class=code
    start:
        
        mov ESI, S+1 ; we start the iteration from the second element
        mov ECX, ls-1
        mov EDI, D
        
        mov BL, [S] ; the first element of array S (the dividend) In BL we will always store the 'previous' value

        jecxz stop_loop
            
            repeat1:
                lodsb ; AL = element of S
                mov DL, AL  ; this will be the divisor
                mov AL, BL
                mov AH, 0
                DIV DL  ; AL = AX / DL  (the previous element divided by the actual element)
                mov BL, DL ; BL will store the actual element of the iteration to divide it by the next element and so on
                stosb ; D = takes the value from AL (the result of the division)
                
            loop repeat1
            
        stop_loop:
       

        push    dword 0
        call    [exit]
