bits 32
global start

extern exit, scanf, fopen, fprintf
import exit msvcrt.dll
import fopen msvcrt.dll
import scanf msvcrt.dll
import fprintf msvcrt.dll

segment data use32 class=data
    
    format_string db "%s", 0
    fisier times 100 db 0
    access_mode db "w", 0
    s db "Ana si Maria merg in Franta", 0
    noul_sir times 100 db 0
    file_descriptor dd 0

segment code use32 class=code
    start:
    
    ;citesc string
    push dword fisier
    push dword format_string
    call [scanf]
    add esp, 8
    
    ; deschid fisierul al carui nume l-am citit
    push dword access_mode
    push dword fisier
    call [fopen]
    add esp, 8
    mov [file_descriptor], EAX
    
    mov esi, s
    mov edi, noul_sir
    
    ; adaug majuscule in fisier
parcurg_sir_majuscule:
    mov al, byte[esi]
    test al, al
    jz stop_parcurgere_majuscule

    cmp al, 'A'
    jl not_uppercase
    
    cmp al, 'Z'
    jg not_uppercase
    
    ; here we know for sure that our current character is uppercase
    mov byte[edi], al
    inc edi
    
not_uppercase:
    inc esi
    jmp parcurg_sir_majuscule
    
stop_parcurgere_majuscule:

    mov byte[edi], ' '
    inc edi
    mov esi, s
    
; adaug minuscule in fisier
parcurg_sir_minuscule:
    mov al, byte[esi]
    test al, al
    jz stop_parcurgere_minuscule

    cmp al, 'a'
    jl not_lowercase
    
    cmp al, 'z'
    jg not_lowercase
    
    ; here we know for sure that our current character is lowercase
    mov byte[edi], al
    inc edi
    
not_lowercase:
    inc esi
    jmp parcurg_sir_minuscule
    
stop_parcurgere_minuscule:

    push dword noul_sir
    push dword format_string
    push dword [file_descriptor]
    call [fprintf]
    add esp, 4 * 3
    
push dword 0
call [exit]

;Citesc string tastatura, creez fisier string.txt, adaugam in fisier prima data majusculele apoi minusculele ;invers de cum apar in sir.