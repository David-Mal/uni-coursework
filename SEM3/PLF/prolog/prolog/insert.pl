insert_at(X, 1, L, [X|L]).
insert_at(X, N, [H|T], [H|R]) :-
    N > 1,
    N1 is N - 1,
    insert_at(X, N1, T, R).

% insert_at(X,N,l1,...,ln)={
%                   X,l1,...,ln, daca N=1
%                   l1 U insert_at(X,N-1,l2,...ln), daca N>1
%                  }
