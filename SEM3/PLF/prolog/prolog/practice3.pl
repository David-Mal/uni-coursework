is_member(X, [X|T]).

is_member(X, [H|T]):-
    is_member(X, T).

keep_uniq([], []).
keep_uniq([H|T], [H|R]):-
    \+ is_member(H, T),
    keep_uniq(T, R).

keep_uniq([H|T], R):-
    is_member(H, T),
    keep_uniq(T, R).




rem(List, Res) :-
  rem(List, 1, 1, Res).

rem([], _, _, []).
rem([_|T], Curr, Nxt, Res) :-
  Curr =:= Nxt,
  Curr1 is Curr + 1,
  Nxt1 is Nxt + Curr1,
  rem(T, Curr1, Nxt1, Res).
rem([H|T], Curr, Nxt, [H|Res]) :-
  Curr =\= Nxt,
  Curr1 is Curr + 1,
  rem(T, Curr1, Nxt, Res).    