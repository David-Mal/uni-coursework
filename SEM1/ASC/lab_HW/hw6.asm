; 17.asm
bits 32
global start

; a file name is given (defined in the data segment).
; create a file with the given name, then read the numbers
; from the keyboard and write only the numbers divisible by 7 to the file,
; until the value '0' is read from the keyboard.

extern exit, fopen, fclose, scanf, fprintf
import exit msvcrt.dll
import fopen msvcrt.dll
import fclose msvcrt.dll
import scanf msvcrt.dll
import fprintf msvcrt.dll

segment data use32 class=data
    file_name db "17.txt", 0
    access_mode db "a", 0
    file_descriptor dd -1
    format db "%d", 0
    formatRead db "%d ", 0
    sapte dw 7
    numar dw 0

segment code use32 class=code
start:
    push dword access_mode
    push dword file_name
    call [fopen]
    add esp, 4*2

    cmp eax, 0
    je final
    mov [file_descriptor], eax

    xor eax, eax

    citire:
        push dword numar
        push dword format
        call [scanf]
        add esp, 4*2

        mov ax, [numar]
        cmp ax, 0
        je close

        mov dx, 0
        div word [sapte]
        cmp dx, 0
        jne citire

    divizibil:
        movsx eax, word [numar]
        push eax
        push dword formatRead
        push dword [file_descriptor]
        call [fprintf]
        add esp, 4*3

        jmp citire

    close:
        push dword [file_descriptor]
        call [fclose]
        add esp, 4

final:
    push dword 0
    call [exit]