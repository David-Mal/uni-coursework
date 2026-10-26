;Given an array A of words, build two arrays of bytes:  
; - array B1 contains as elements the higher part of the words from A
; - array B2 contains as elements the lower part of the words from A

bits 32

global start        

extern exit               
import exit msvcrt.dll    

segment data use32 class=data
        
        a dw 0x1234, 0x2345, 0x3456, 0x4567
        lena equ ($-a) / 2
        b1 times lena db 0
        b2 times lena db 0


segment code use32 class=code
    start:
        ; ...
        
        mov esi, a
        mov edi, b1
        mov ecx, lena
        jecxz stop
        rep1:
            lodsw
            shr ax, 8
            stosb
        loop rep1
        stop:   

        mov esi, a
        mov ecx, lena        
        mov edi, b2
        jecxz stop2
        rep2:
            lodsw
            stosb
        loop rep2
        stop2:
        
        ; exit(0)
        push    dword 0      
        call    [exit]       