bits 32 

global start        

extern exit
import exit msvcrt.dll

segment data use32 class=data
    
    ; Two strings of bytes A and B are given. Parse the shortest string of those two and build a third string C as follows:
    ; up to the lenght of the shortest string C contains the largest element of the same rank from the two strings
    ; then, up to the length of the longest string C will be filled with 1 and 0, alternatively.

    A db 3, 7, 2
    B db 6, 4, 9, 1, 1, 1
    ; 6 7 9 1 0 1
    lenA equ $ - A
    lenB equ $ - B
    
    C times lenA + lenB db 0
   
        
segment code use32 class=code
    start:
        
        ;The approach is to store the elements from A in C and then parse B and if an element from B is greater we swap with the one in C
        ; assume A is the smaller string
        mov ESI, A
        mov EDX, B
        
        mov EAX, lenA
        mov EBX, lenB
        cmp EAX, EBX
        jle change  ; if A is shorter, it jumps
            
            ;If it entered here this means B is shorter
            xchg EAX, EBX   ; in EAX is the size of the shorter string
            xchg ESI, EDX   ; in ESI is the buffer of the shorter string
        
        change:
        
        mov CL, byte[len1]
        mov EDI, C
        
        jecxz finish
            
            repeat1:
                movsb   ; we store in C each element of A
            loop repeat1
                
        finish:
        
        mov ECX, [len1]
        mov ESI, EDX ; we parse through the other string
        mov EDI, C
        
        jecxz finish2
            
            repeat2:
                lodsb ; in AL the element of B
                mov BL, [EDI]
                cmp AL, BL
                jg swaped
                    mov [EDI], AL
                swaped:
                inc EDI
                dec ECX
            loop repeat2
        finish2:
        push    dword 0
        call    [exit] 