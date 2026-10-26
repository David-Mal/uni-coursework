bits 32
global start        

extern exit, scanf, printf
import scanf msvcrt.dll
import printf msvcrt.dll
import exit msvcrt.dll

segment data use32 class=data

    a dd 0
    b dd 0
    mesaj1 db "Citeste a: ", 0
    mesaj2 db "Citeste b: ", 0
    mesaj3 db "  Rezultatul a - b este: ", 0
    format db "%d", 0
     
segment code use32 class=code
    start:
        
        push dword mesaj1
        call [printf]
        add ESP, 4
     
        push dword a
        push dword format
        call [scanf]
        add ESP, 2 * 4
        
        push dword mesaj2
        call [printf]
        add ESP, 4
        
        push dword b
        push dword format
        call [scanf]
        add ESP, 2 * 4
        
        push dword mesaj3
        call [printf]
        add ESP, 4
        
        mov EAX, [a]
        mov EBX, [b]
        sub EAX, EBX
        
        push EAX
        push dword format
        call [printf]
        add ESP, 8
        
        mov ECX, [b]
        mov EAX, [a]
        
        jecxz stop_loop
        
        scadere:
            
            dec dword[a]
            
        loop scadere
        
        stop_loop:
        
        push dword mesaj3
        call [printf]
        add ESP, 4
        
        push dword [a]
        push dword format
        call [printf]
        add ESP, 8
        
        push    dword 0
        call    [exit]
