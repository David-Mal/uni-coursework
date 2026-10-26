bits 32
global start

extern exit, fopen, fread, printf
import exit msvcrt.dll
import fopen msvcrt.dll
import fread msvcrt.dll
import printf msvcrt.dll

segment data use32 class=data

    propozitie times 100 db 0
    format db "%s ", 0
    filename db "fisier_citire.txt", 0
    access_mode db "r", 0
    file_descriptor dd 0
    

segment code use32 class=code
start:
; open the file for reading
push dword access_mode
push dword filename
call [fopen]
add esp, 8

mov [file_descriptor], EAX

; read from file
push dword[file_descriptor]
push dword 100
push dword 1
push dword propozitie
call [fread]
add esp, 4 * 4

; reverse each word from the sentence
mov esi, propozitie ; esi va reprezenta inceputul fiecarui cuvant
mov edi, esi ; edi va traversa fiecare cuvant

loop_parcurgere:
    cmp byte[edi], 10 ; check for new line (the end of sentence)
    je finish
    
    cmp byte[edi], ' ' ; check for space (end of our current word)
    je reverse_word
    
    inc edi
jmp loop_parcurgere


reverse_word:
    ; ecx start of our current word
    ; edi-1 end of out current word
    mov ebx, edi
    dec ebx
reverse_loop:
    cmp ecx, ebx
    jge next_word ; if ecx >= ebx that means that we completed looping the word
    
    mov al, byte[ecx]   ; the character at position ecx
    mov ah, byte[ebx]   ; the character at position ebx
    ; swap the two characters
    mov [ecx], ah
    mov [ebx], al
    inc ecx
    dec ebx
jmp reverse_loop

next_word:
    inc edi
    mov ecx, edi
    jmp loop_parcurgere
    
    
finish:

; print the content from the file
push dword format
push dword propozitie
call [printf]    

push dword 0
call [exit]

;Se citeste o propozitie dintr-un fisier. Sa se inverseze fiecare cuvant si sa se afiseze pe ecran.