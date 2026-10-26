remove_first_three(List, Element, NewList) :-
    remove_first_three_aux(List, Element, 3, NewList).

remove_first_three_aux([], _, _, []).

remove_first_three_aux([Element|Rest], Element, Count, NewList) :-
    Count > 0,
    NewCount is Count - 1,
    remove_first_three_aux(Rest, Element, NewCount, NewList).

remove_first_three_aux([Element|Rest], Element, 0, [Element|NewList]) :-
    remove_first_three_aux(Rest, Element, 0, NewList).
    
remove_first_three_aux([Head|Rest], Element, Count, [Head|NewList]) :-
    Head \= Element,
    remove_first_three_aux(Rest, Element, Count, NewList).