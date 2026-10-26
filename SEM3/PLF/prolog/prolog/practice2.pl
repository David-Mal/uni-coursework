removee([], _, []).
removee([H|T], X, New):-
    H == X,
    removee(T, X, New).

removee([H|T], X, [H|New]):-
    H \== X,
    removee(T, X, New).

