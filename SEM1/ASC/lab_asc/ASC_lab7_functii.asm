bits 32 


global start        

; declare external functions needed by our program
extern exit               ; tell nasm that exit exists even if we won't be defining it
extern printf
extern scanf
import exit msvcrt.dll    ; exit is a function that ends the calling process. It is defined in msvcrt.dll
                          ; msvcrt.dll contains exit, printf and all the other important C-runtime specific functions

import printf msvcrt.dll
import scanf msvcrt.dll

;FOR READING
;format db "%d", 0    /// format db "x=%d",0 (for printing)
;x dd 0
;push dword x         /// push dword [x] (for printing)
;push format
;call [scanf]        /// call [printf] (for printing)
;ADD ESP, 4 * 2                          

; our data is declared here (the variables needed by our program)
segment data use32 class=data
    ; ...
    number1 dd 0
    format1 dd "%d", 0
    
    number2 dd 0
    format2 dd "%d", 0
    
    
    result dd 0
    format3 dd "x = %d", 0
    
    
; our code starts here
segment code use32 class=code
    sum:
        mov EAX, [ESP + 4]
        mov EBX, [ESP + 8]
        ADD EAX, EBX
        ret
    start:
        
        push dword number1
        push dword format1
        call [scanf]
        add ESP, 4 * 2
        
        push dword number2
        push dword format2
        call [scanf]
        add ESP, 4 * 2
        
        push dword [number1]
        push dword [number2]
        call sum
        
        mov dword[result], EAX
        push dword[result]
        push dword format3 
        call [printf]
        add ESP, 4 * 2
        
        
        
        push    dword 0      ; push the parameter for exit onto the stack
        call    [exit]       ; call exit to terminate the program
