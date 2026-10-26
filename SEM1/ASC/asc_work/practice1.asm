bits 32 

global start        

extern exit               
extern printf              
import exit msvcrt.dll
import printf msvcrt.dll

segment data use32 class=data
    
    ; define string s1 of quadwords
    s1 dq 4567891011121314h, 5151515151515h, 6041455012345678h, 8049556789ABCDEFh
    l1 equ ($-s1)/8
    ; s2 the string of doublewords that have word high > word low in signed interpretation
    s2 resd l1
    dublu_cuvant dw 0
    format db "number of vowels: %d", 0
    all_vowels db "aeiouAEIOU", 0
    
    
segment code use32 class=code

vowels:
; this function takes as parameter the string s2 and returns the number of those doublewords that have the property that the byte high of the word low of that double word is equal to the ascii code of a vowel
mov esi, [ESP + 4] ; array of dw S2
mov ecx, [ESP + 8]
xor eax, eax ; the number of vowels

jecxz finish
parcurg_S2:
mov edi, all_vowels
mov bx, word[esi] ; word low from dw of s2

;check if bh is vowel
parcurg_vocale:
    cmp byte[edi], 0
    je next_dw
    cmp bh, byte[edi]
    je gasit_vocala
    inc edi
    jmp parcurg_vocale
    
gasit_vocala:
inc EAX
next_dw:
add esi, 4
loop parcurg_S2

finish:
ret


    start:
    
    ; we build the string s2 of Dwords high which respect the condition that word high > word low in signed     interpretation
    
    ; step1: loop through s1
    mov ecx, l1
    mov esi, s1
    mov edi, s2
    
jecxz stop_parcurgere1
parcurg1:
    ; dword high from quadword (ex: 60414550)
    mov EBX, dword[esi + 4] ; EBX = dword high din qw
    jmp check_property
    
    check_finished:
    ; whatever was the outcome of the verfication we go to analize the next dw
    add esi, 8
loop parcurg1
    
check_property:
    mov dx, word[dublu_cuvant + 2] ; word high din dw high din qw
    mov ax, word[dublu_cuvant]
    cmp dx, ax
    jng check_finished
    ; if we get here, we have a dw that respects the given condition, we can add it to s2
    mov [edi], dword[dublu_cuvant]
    add edi, 4
jmp check_finished

stop_parcurgere1:
; call function vowels for string s2
push dword s2
push l1
call vowels
add esp, 8

;print the result
push EAX
push dword format
call [printf]
add esp, 8

    push dword 0
    call [exit]

    