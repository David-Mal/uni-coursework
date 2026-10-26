bits 32 

global start        


extern exit, fopen, fprintf, fread           
import exit msvcrt.dll
import fopen msvcrt.dll
import fprintf msvcrt.dll
import fread msvcrt.dll



segment data use32 class=data
    
    file_name db "input.txt", 0
    access_mode db "r", 0
    
    ;FREAD(char *destination,  size_int, int count, void *file_handle);
    destination db "output.txt", 0
    size_int dd 1
    count dd 101
    s times 101 db 0
    
    ;FPRINTF(void *file_handle, char *format);
    format db "%s", 0
    
    acces_mode2 db "w", 0
    fh_i db 0
    fh_o db 0


segment code use32 class=code
    start:
        
        
        
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
