ascending_sublist(A, Sub) :-
    subsequence(A, Sub),
    strictly_ascending(Sub).

subsequence([], []).
subsequence([H|T], [H|S]) :-
    subsequence(T, S).
subsequence([_|T], S) :-
    subsequence(T, S).

strictly_ascending([]).
strictly_ascending([_]).
strictly_ascending([H1, H2 | T]) :-
    H1 < H2,
    strictly_ascending([H2 | T]).

% (i, o)
% subsequence(l1,...,ln, S) = {
%    [], if n = 0
%    H U subsequence(l2,...,ln, S)
%    subsequence(l2,...,ln, S)
% }

% (i)
% strictly_ascending(l1,...,ln) = {
%    True if n = 0
%    True if n = 1
%    strictly_ascending(l2,...,ln), if l1 < l2
% }