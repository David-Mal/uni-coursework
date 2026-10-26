bits 32 

global start        


extern exit, fopen, fprintf, fread, scanf          
import exit msvcrt.dll
import fopen msvcrt.dll
import fprintf msvcrt.dll
import fread msvcrt.dll
import scanf msvcrt.dll



segment data use32 class=data
    
    file_name times 51 dd 0  ; the name of the input file
    access_mode db "r", 0
    
    destination times 51 dd 0  ; the name of the output file
    size_int dd 1
    count dd 101
    s times 101 db 0
    
    format db "%s", 0
    
    acces_mode2 db "w", 0
    fh_i db 0
    fh_o db 0

    formatttt db "%s", 0
    
segment code use32 class=code
    start:
        
        push file_name
        push formatttt
        call [scanf]
        add esp, 8
        
        push destination
        push formatttt
        call [scanf]
        add esp, 8
        
        
        push access_mode
        push file_name
        call [fopen]
        add ESP, 8
        
        mov dword[fh_i], EAX
        
        push dword [fh_i]
        push dword [count]
        push dword [size_int]
        push dword s
        call [fread]
        add ESP, 4 * 4
        
        
        push acces_mode2
        push destination
        call [fopen]
        add ESP, 8
        
        mov dword[fh_o], EAX
        
        push s
        push dword format
        push dword [fh_o]
        call [fprintf]
        add ESP, 8
        
        
        push    dword 0 
        call    [exit] 
