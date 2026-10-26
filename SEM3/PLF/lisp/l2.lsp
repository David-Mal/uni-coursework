(defun get-max (a b)
  (if (> a b) a b))

(defun get-subtree-info (current-list)
  (list (first current-list) (second current-list)))

; advance-list-by-size(l1,...,ln, size)
; l1,...,ln, if size = 0 
; advance-list-by-size(l2,...,ln, size - 1), if size > 0

(defun advance-list-by-size (lst size)
  (if (zerop size)
      lst
      (advance-list-by-size (cdr lst) (1- size))))
      
      
; get-max-level-from-subtrees(l1,..,ln, num, max-depth)
; max-depth if num = 0
; 
;
;
(defun get-max-level-from-subtrees (list-of-subtrees num-remaining max-depth)
  (if (zerop num-remaining)
      max-depth
      (let* ((subtree-size (get-tree-list-size list-of-subtrees))
             (current-depth (tree-levels-type1 list-of-subtrees))
             (new-max-depth (get-max max-depth current-depth))
             (remaining-list (advance-list-by-size list-of-subtrees subtree-size)))
        (get-max-level-from-subtrees remaining-list (1- num-remaining) new-max-depth))))

(defun get-tree-list-size (tree)
  (let ((num-subtrees (second tree)))
    (if (zerop num-subtrees)
        2 ; Node + Count
        (get-children-size (cddr tree) num-subtrees 0 2)))) ; 2 for the current Node + Count

(defun get-children-size (children-list num-subtrees current-size initial-overhead)
  (if (zerop num-subtrees)
      (+ initial-overhead current-size)
      (let* ((subtree-size (get-tree-list-size children-list))
             (new-children-list (advance-list-by-size children-list subtree-size)))
        (get-children-size new-children-list (1- num-subtrees) (+ current-size subtree-size) initial-overhead))))

(defun tree-levels-type1 (tree)
  (let ((num-subtrees (second tree)))
    (if (zerop num-subtrees)
        1
        (1+ (get-max-level-from-subtrees (cddr tree) num-subtrees 0)))))

;; --- Execution ---
(format t "The number of levels is: ~a~%" (tree-levels-type1 '(A 2 B 0 C 2 D 0 E 0)))