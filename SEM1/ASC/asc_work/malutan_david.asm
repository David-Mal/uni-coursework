bits 32
global start

extern exit, fopen, fread, fprintf
import exit msvcrt.dll
import fopen msvcrt.dll
import fread msvcrt.dll
import fprintf msvcrt.dll

segment data use32 class=data

    fisier_citire db "input.txt", 0
    access_mode db "r", 0
    s times 100 db 0
    format1 db "%d", 10, 0
    format2 db "%s", 10, 0
    format3 db "%c", 0
    file_descriptor dd 0
    nr_bytes dd 0
    nume_persoana times 50 db 0
    ultima_litera_din_nume dd 0
    
    access_mode2 db "w", 0
    file_name_output db "output.txt", 0
    file_descriptor_output dd 0

segment code use32 class=code
    start:
    ; open input.txt
    push dword access_mode
    push dword fisier_citire
    call [fopen]
    add ESP, 8
    
    mov [file_descriptor], EAX
    
    ;open output.txt
    push dword access_mode2
    push dword file_name_output
    call [fopen]
    add ESP, 8
    mov [file_descriptor_output], EAX
    
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
    push dword [file_descriptor_output]
    call [fprintf]
    add ESP, 4 * 3
    
    ; the name of the person
    mov esi, s
    mov edi, nume_persoana
    
parcurgere_sir:
    cmp byte[esi], ' '
    je stop_parcurgere
    
    mov al, byte[esi]
    mov byte[edi], al
    
    inc edi
    inc esi
    jmp parcurgere_sir

stop_parcurgere:
    dec esi
    mov al, byte[esi]
    mov [ultima_litera_din_nume], al
    
    push dword nume_persoana
    push dword format2
    push dword [file_descriptor_output]
    call [fprintf]
    add ESP, 4 * 3
    
   ; 48 = 0    57 = 9
   mov esi, s
   
parcurgere_sir2:
    cmp byte[esi], '0'
    jl not_digit
    cmp byte[esi], '9'
    jg not_digit
    
    ;right now we know for sure we are dealing with a digit
    mov AL, byte[esi]
    sub AL, 48
    add AL, 10
    jmp stop_parcurgere2
    
not_digit:
    inc esi
    jmp parcurgere_sir2
stop_parcurgere2:
    movzx EAX, AL
    push EAX
    push dword format1
    push dword [file_descriptor_output]
    call [fprintf]
    add ESP, 4 * 3
    
    cmp byte[ultima_litera_din_nume], 'a'
    jne masculin
    
    push dword 'F'
    push dword format3
    push dword [file_descriptor_output]
    call [fprintf]
    add ESP, 4 * 3
    jmp finish
masculin:
    push dword 'M'
    push dword format3
    push dword [file_descriptor_output]
    call [fprintf]
    add ESP, 4 * 3
finish:
push dword 0
call [exit]