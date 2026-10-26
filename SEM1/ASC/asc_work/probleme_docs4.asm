bits 32
global start

extern exit, fopen, fread, printf
import exit msvcrt.dll
import fopen msvcrt.dll
import fread msvcrt.dll
import printf msvcrt.dll

segment data use32 class=data

    fisier_citire db "fisier_citire.txt", 0
    access_mode db "r", 0
    s times 100 db 0
    format1 db "%d ", 10, 0
    format2 db "%s ", 10, 0
    file_descriptor dd 0
    nr_bytes dd 0

segment code use32 class=code
    start:
    
    push dword access_mode
    push dword fisier_citire
    call [fopen]
    add ESP, 8
    
    mov [file_descriptor], EAX
    ; reading from file
    push dword [file_descriptor]
    push dword 100
    push dword 1
    push dword s
    call [fread]
    add esp, 4 * 4
    
    mov [nr_bytes], EAX
      
    
    push dword [nr_bytes]
    push dword format1
    call [printf]
    add ESP, 4 * 2
    
    ; aflam numarul de spatii din propozitie
    mov esi, s
    mov edi, esi
    mov ecx, [nr_bytes]
    xor EAX, EAX ; here we store the number of spaces
    
start_parcurgere:
    cmp ecx, 0
    je stop_parcurgere
    
    cmp byte [esi], ' '         ; Check if current character is a space
    jne no_space                ; If not, skip space counting
    
    inc EAX                     ; Increment space count

no_space:
    inc esi                     ; Move to the next character
    dec ecx                     ; Decrement counter
    jmp start_parcurgere        ; Repeat loop

stop_parcurgere:
    push EAX
    push dword format1
    call [printf]
    add esp, 8
    
; literele mici le facem majuscule si afisam noul sir
    mov esi, s
    mov ecx, [nr_bytes]
    
loop_sir:
    cmp ecx, 0
    je loop_end
    
    mov AL, byte[esi]
    cmp al, 'a'
    jl not_lowercase
    cmp al, 'z'
    jg not_lowercase
    
    ;rn we know that our current character is lowercase
    sub AL, 32
    mov byte[esi], AL
    
not_lowercase:
    inc esi
    dec ecx
    jmp loop_sir
    
loop_end:
    
    push dword s
    push dword format2
    call [printf]
    add esp, 8

push dword 0
call [exit]