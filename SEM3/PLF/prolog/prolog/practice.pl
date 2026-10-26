gcd(A, 0, A).

gcd(A, B, R):-
    X is A mod B,
    gcd(B, X, R).

lcm(A, B, R):-
    gcd(A, B, X),
    R is A * B / X.

lcm_list([], 1).
lcm_list([H|T], R):-
    lcm_list(T, Aux),
    lcm(H, Aux, R).

is_power(1).
is_power(X):-
    X > 1,
    0 is X mod 2,
    Y is X / 2,
    is_power(Y).

insert([], _, _, []).
insert([H|T], Pos, V, [H, V|R]):-
    is_power(Pos),
    !,
    NewPos is Pos + 1,
    insert(T, NewPos, V, R).

insert([H|T], Pos, V, [H|R]):-
    NewPos is Pos + 1,
    insert(T, NewPos, V, R).
