(defun replace-node (tree old new)
  (cond
    ((eq tree old) new)
    ((listp tree)
     (mapcar (lambda (x) 
               (replace-node x old new)) 
             tree))
    (t tree)))
    
    (print (replace-node '(a (b (c)) (d) (e (f))) 'b 'g))


; replace-node (t1,...,tn, old, new) = {
  
;   n = 0 => nil
;   tn = old => new
  
;   n > 0 (tree is list) => replace-node(t1, old, new) U replace-node(l2,...,ln, olde new)
;   otherwise, tn
; }