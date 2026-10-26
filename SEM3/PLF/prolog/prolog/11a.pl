replace_elem([], _, _, []).

replace_elem([X|T], X, E, [E|R]) :-
    replace_elem(T, X, E, R).

replace_elem([H|T], X, E, [H|R]) :-
    H \= X,
    replace_elem(T, X, E, R).

% replace_elem(l1,...,ln, X, E) = {
%    [], daca n = 0
%    E U replace_elem(l2,...,ln, X, E) daca l1 = X
%    l1 U replace_elem(l2,...,ln,X,E) daca l1 != X
%}