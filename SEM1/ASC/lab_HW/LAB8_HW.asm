;Read a number in base 10 from the keyboard and display the value of that number in base 16
bits 32 


global start        


extern exit
extern scanf
extern printf 
         
import exit msvcrt.dll  
import scanf msvcrt.dll
import printf msvcrt.dll


segment data use32 class=data
   
    number dd 0
    formatInput db "Your number in base 10 = ", 0
    formatRead db "%d", 0
    formatWrite db "Your number in base 16 = %x", 0

segment code use32 class=code
    start:
        
        push dword formatInput
        call [printf]
        add ESP, 4
        
        push dword number
        push dword formatRead
        call [scanf]
        add ESP, 4*2
        
        mov EAX, [number]
        push dword [number]
        push dword formatWrite
        call [printf]
        add ESP, 4*2
        
        push    dword 0    
        call    [exit]     
