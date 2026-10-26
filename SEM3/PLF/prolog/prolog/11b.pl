% check if something is a list
is_list_custom([]).
is_list_custom([_|_]).

% find greater of two numbers
greater(A, B, A) :- A >= B, !.
greater(_, B, B).

% find maximum of a simple list of numbers
max_number([X], X).
max_number([H|T], M) :-
    max_number(T, TM),
    greater(H, TM, M).

% find maximum number in a heterogeneous list (ignore sublists)
max_heter_list([], -999999).
max_heter_list([H|T], M) :-
    is_list_custom(H), !,
    max_heter_list(T, M).
max_heter_list([H|T], M) :-
    max_heter_list(T, TM),
    greater(H, TM, M).


% transform heterogeneous list
transform([], _, []).
transform([H|T], GlobalMax, [NewH|R]) :-
    is_list_custom(H), !,
    max_number(H, SubMax),
    replace_elem(H, GlobalMax, SubMax, NewH),
    transform(T, GlobalMax, R).
transform([H|T], GlobalMax, [H|R]) :-
    transform(T, GlobalMax, R).

% main predicate
solve(List, Result) :-
    max_heter_list(List, GlobalMax),
    transform(List, GlobalMax, Result).


%max_heter_list(l1,...,ln) = {
%    -999999, daca n = 0
%    max_heter_list(l2,...,ln), daca l1 = lista
%    max(l1, max_heter_list(l2,...,ln)), daca l1 = numar
%}

%transform(l1,...,ln, M) = {
%    [], daca n = 0
%    replace_all(l1, M, max(l1)) U transform(l2,...,ln, M), daca l1 e lista
%    l1 U transform(l2,...,ln,M), daca l1 e numar
%}
