bits 32 

global start        

extern exit               
import exit msvcrt.dll

segment data use32 class=data
    
    a db 8
    b dw 99
    d equ 10
segment code use32 class=code
    start:
    ;mov eax, 10
    mov [a], d
    
    push dword 0
    call [exit]
        
        
        
        
        
        
        
        
  