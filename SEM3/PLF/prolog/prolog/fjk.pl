remove3(List, X, R):-
    remove_aux(List, X, 3, R).

remove_aux([], _, _, []).

remove_aux([X|T], X, Count, R):-
    Count > 0,
    NewCount is Count - 1,
    remove_aux(T, X, NewCount, R).

remove_aux([X|T], X, 0, [X|R]):-
    remove_aux(T, X, 0, R).

remove_aux([H|T], X, Count, [H|R]):-
    H \= X,
    remove_aux(T, X, Count, R).

% remove3([1,4,1,3,2,6,1,7,1,2], 1, R).

% (i, i, i, o)
% remove_aux(l1,l2,...,ln, X, Count) = {
%
%    [], if n = 0
%
%   remove_aux(l2,l3,...,ln, X, Count - 1), if n > 0 and Count > 0 and l1 == X
%
%   X U remove_aux(l2,l3,..., ln, X, 0), if n > 0 and Count == 0 and l1 == X
%
%   l1 U remove_aux(l2,l3,...,ln, X, Count), if n > 0 and l1 != X
%
% }