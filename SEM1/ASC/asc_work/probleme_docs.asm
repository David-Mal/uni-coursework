bits 32
global start
extern exit, scanf, printf, fopen, fprintf
import exit msvcrt.dll
import scanf msvcrt.dll
import printf msvcrt.dll
import fprintf msvcrt.dll
import fopen msvcrt.dll

segment data use32 class=data

    N dd 0
    value dd 0
    format_read db "%d", 0
    spatiu db "", 10, 0
    file_name db "output.txt", 0
    access_mode db "w", 0
    
    suma_par dd 0
    suma_impar dd 0
    file_descriptor dd 0
    format_file db "%d %d %d", 0  ; Hexadecimal format with newline

segment code use32 class=code
    start:
    
    ; Read N
    push dword N
    push dword format_read
    call [scanf]
    add ESP, 8
    
    ; Initialize sums
    xor EAX, EAX
    xor EBX, EBX
    
    mov ecx, [N]
    test ecx, ecx
    jz stop_repeta  ; Skip loop if N is 0
    
repeta:
pushad
    ; Read next value
    push dword value
    push dword format_read
    call [scanf]
    add ESP, 8
popad
    ; Check if the value is even or odd
    mov EAX, [value]
    test EAX, 1
    jz pare  ; Jump if even
    
    ; Odd case
    add EBX, EAX
    jmp ho_ba
pare:
    ; Even case
    add EAX, dword[suma_par]
    mov dword[suma_par], EAX
ho_ba:
    loop repeta
    
stop_repeta:
    mov dword[suma_impar], EBX
    
    ; Open the file
    push dword access_mode
    push dword file_name
    call [fopen]
    add ESP, 8
    
    test EAX, EAX
    jz exit_program  ; Exit if file open failed
    
    mov [file_descriptor], EAX
    
    ; Calculate difference
    mov EAX, dword[suma_par]
    sub EAX, dword[suma_impar]
    
    ; Write results to the file
    push EAX
    push dword[suma_impar]
    push dword[suma_par]
    push dword format_file
    push dword[file_descriptor]
    call [fprintf]
    add ESP, 20
    
exit_program:
    push dword 0
    call [exit]
