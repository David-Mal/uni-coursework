(format t "hello world")

(defun fib(n)
"print nth fibo number"
(if (< n 2)
  n
  (+ (fib(- n 1))
     (fib(- n 2))
  )
))

(print(fib 30))

;; Write a function to return the n-th element of a list, or NIL if such an element does not exist
(defun nth-elem(x n)
  (cond ((null x)
      NIL)
      ((= n 1) (car x))
  (t (nth-elem (cdr x) (- n 1)))))
  
(print (nth-elem '(1 2 3 4 5) 3))

;; Write a function to check whether an atom E is a member of a list which is not necessarily linear.
(defun f(x E)
  (cond
  ((null x) nil)
  ((atom x) (eq x E))
  (t (or (f (car x) E) (f (cdr x) E)))
  )
)

(if (f '(1 2 3 (2 4 (69 67)) (6 7) 8 9) 67)
(format t "Element was found in the list")
(format t "Element is not in the list"))

;; Write a function to determine the list of all sublists of a given list, on any level.
(defun g(x)
(cond 
  ((null x) nil)
  ((listp (car x)) (append (list (car x))
            (g (car x))
            (g (cdr x))))
  (t (g (cdr x)))
  )
)
(print (g '(1 2 (3 (4 5) (6 7)) 8 (9 10))))

;; Write a function to transform a linear list into a set.

(defun remov(x E)
  (cond 
    ((null x) nil)
    ((eq (car x) E) (remov(cdr x) E))
    (t (cons (car x) (remov (cdr x) E)))
  ))

(print (remov '(1 2 4 2 3 5 3 1) 2) )


(defun func(x)
  (cond 
    ((null x) nil)
    (t (cons (car x) (func (remov (cdr x) (car x)))))
  )
)

(print (func '(1 1 2 3 5 6 7 8 9 10 1 2 3 4 5 6 7 8 9 10)))
