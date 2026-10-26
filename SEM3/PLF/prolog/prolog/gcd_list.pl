gcd(A, 0, A) :- A > 0, !.
gcd(A, B, G) :-
    B > 0,
    R is A mod B,
    gcd(B, R, G).

gcd_list([X], X).
gcd_list([H|T], G) :-
    gcd_list(T, G1),
    gcd(H, G1, G).


%gcd(A,B) = {
%            A, daca B=0 si A>0
%            gcd(B,A mod B), daca B>0
%            }

%gcd_list(l1,...,ln)= {
%            l1, daca n=1
%            gcd(l1,gcd_list(l2,...ln)), daca n > 1
%            }