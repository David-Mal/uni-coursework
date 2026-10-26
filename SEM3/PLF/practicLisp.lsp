(defun inmultire-recursiva (lista cifra transport)
  (cond 
    ((null lista) 
     (cond ((> transport 0) (list transport))
           (t nil)))
    (t (cons (mod (+ (* (car lista) cifra) transport) 10)
             (inmultire-recursiva (cdr lista) 
                                  cifra 
                                  (floor (+ (* (car lista) cifra) transport) 10))))))

(defun inmultire-mare (lista cifra)
  (reverse (inmultire-recursiva (reverse lista) cifra 0)))

(print (inmultire-mare '(1 9 3 5 9 9) 2))