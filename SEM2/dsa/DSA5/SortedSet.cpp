#include "SortedSet.h"
#include "SortedSetIterator.h"
#include <iostream>

// Complexity: Theta(1)
SortedSet::SortedSet(Relation r) {
    relation = r;
    setSize = 0;
    bst.capacity = 10;
    bst.firstEmpty = 0;
    bst.root = -1;

    bst.info = new TElem[bst.capacity];
    bst.left = new int[bst.capacity];
    bst.right = new int[bst.capacity];
    bst.parent = new int[bst.capacity];

    for (int i = 0; i < bst.capacity; i++)
    {
        bst.info[i] = NULL_TELEM;
        bst.left[i] = i + 1;                    // for computing the next free value
        bst.right[i] = NULL_POS;
        bst.parent[i] = NULL_POS;
    }
    bst.left[bst.capacity - 1] = -1;            // to know when we don't have free positions anymore
}

// Complexity: Theta(capacity*2)
void SortedSet::resize() {
    auto* newInfo = new TElem[bst.capacity * 2];
    int* newRight = new int[bst.capacity * 2];
    int* newLeft = new int[bst.capacity * 2];
    int* newParent = new int[bst.capacity * 2];

    for (int i = 0; i < bst.capacity; i++)
    {
        newInfo[i] = bst.info[i];
        newRight[i] = bst.right[i];
        newLeft[i] = bst.left[i];
        newParent[i] = bst.parent[i];
    }

    for (int i = bst.capacity; i < bst.capacity * 2; i++)
    {
        newInfo[i] = NULL_TELEM;
        newRight[i] = NULL_POS;
        newLeft[i] = i + 1;
        newParent[i] = NULL_POS;
    }

    newLeft[bst.capacity * 2 - 1] = -1; // we've reached the last free element

    bst.firstEmpty = bst.capacity;
    bst.capacity *= 2;

    delete[] bst.info;
    delete[] bst.right;
    delete[] bst.left;
    delete[] bst.parent;

    bst.info = newInfo;
    bst.right = newRight;
    bst.left = newLeft;
    bst.parent = newParent;
}

// Complexity:
    //      BC : Theta(1) - when search is theta(1) and we do not need to add again the element/ when the BST is empty
    //      WC : Theta(log setSize + 2*capacity) - when we need to resize
    //      AC: O(setSize)
bool SortedSet::add(TComp elem) {
    if (search(elem))
        return false;

    if (bst.root == -1)       // the BST is empty
    {
        bst.info[bst.firstEmpty] = elem;
        bst.parent[bst.firstEmpty] = -1;
        bst.root = bst.firstEmpty;
        bst.firstEmpty = bst.left[bst.firstEmpty];
        bst.left[bst.root] = NULL_POS;
        setSize++;
        return true;
    }

    int current = bst.root;
    int parent = -1;

    while (current != NULL_POS)
    {
        parent = current;
        if (!relation(bst.info[current], elem))
            current = bst.left[current];
        else
            current = bst.right[current];
    }

    if (bst.firstEmpty == -1)
        resize();

    bst.info[bst.firstEmpty] = elem;
    bst.parent[bst.firstEmpty] = parent;

    if (!relation(bst.info[parent], elem))
        bst.left[parent] = bst.firstEmpty;
    else
        bst.right[parent] = bst.firstEmpty;

    int oldEmpty = bst.firstEmpty;
    bst.firstEmpty = bst.left[bst.firstEmpty];
    bst.left[oldEmpty] = NULL_POS;
    setSize++;
    return true;
}

// Complexity:
    //      BC : Theta(1)
    //      WC : Theta(log setSize)
    //      =>  total: O(log setSize)
bool SortedSet::remove(TComp elem) {
    if (bst.root == -1)
        return false;

    int current = bst.root;
    int parent = -1;

    while (current != NULL_POS)
    {
        if (bst.info[current] == elem)
        {
            if (bst.left[current] == NULL_POS && bst.right[current] == NULL_POS)
            {
                if (current == bst.root)
                {
                    bst.left[current] = bst.firstEmpty;
                    bst.firstEmpty = current;
                    bst.root = -1;
                }
                else
                {
                    if (bst.left[parent] == current)
                        bst.left[parent] = NULL_POS;
                    else
                        bst.right[parent] = NULL_POS;

                    bst.left[current] = bst.firstEmpty;
                    bst.firstEmpty = current;
                }
                setSize--;
            }
            else if (bst.left[current] == NULL_POS && bst.right[current] != NULL_POS)
            {
                if (current == bst.root)
                {
                    bst.root = bst.right[current];
                    bst.left[current] = bst.firstEmpty;
                    bst.right[current] = NULL_POS;
                    bst.info[current] = NULL_TELEM;
                    bst.firstEmpty = current;
                }
                else
                {
                    if (bst.left[parent] == current)
                        bst.left[parent] = bst.right[current];
                    else
                        bst.right[parent] = bst.right[current];
                    bst.left[current] = bst.firstEmpty;
                    bst.right[current] = NULL_POS;
                    bst.info[current] = NULL_TELEM;
                    bst.firstEmpty = current;
                }
                setSize--;
            }
            else if (bst.left[current] != NULL_POS && bst.right[current] == NULL_POS)
            {
                if (current == bst.root)
                {
                    bst.root = bst.left[current];
                    bst.left[current] = bst.firstEmpty;
                    bst.right[current] = NULL_POS;
                    bst.info[current] = NULL_TELEM;
                    bst.firstEmpty = current;
                }
                else
                {
                    if (bst.left[parent] == current)
                        bst.left[parent] = bst.left[current];
                    else
                        bst.right[parent] = bst.left[current];
                    bst.left[current] = bst.firstEmpty;
                    bst.right[current] = NULL_POS;
                    bst.info[current] = NULL_TELEM;
                    bst.firstEmpty = current;
                }
                setSize--;
            }
            else
            {
                int minimum = getMinimum(bst.right[current]);
                remove(bst.info[minimum]);
                bst.info[current] = bst.info[minimum];
            }
            return true;
        }

        parent = current;
        if (!relation(bst.info[current], elem))
            current = bst.left[current];
        else
            current = bst.right[current];
    }
    return false;
}

// Complexity
    //      BC : Theta(1) - when the BST is empty
    //      WC : Theta(log setSize) - when we need to look for a value that is stored in a leaf
    //      => total : O(log setSize)
bool SortedSet::search(TComp elem) const {
    if (bst.root == -1)
        return false;

    int current = bst.root;
    while (current != NULL_POS)
    {
        if (bst.info[current] == elem)
            return true;

        if (relation(bst.info[current], elem))
            current = bst.right[current];
        else
            current = bst.left[current];
    }
    return false;
}

// Complexity: Theta(1)
int SortedSet::size() const {
    return setSize;
}

// Complexity: Theta(1)
bool SortedSet::isEmpty() const {
    return (setSize == 0);
}


// Complexity: Theta(1)
SortedSetIterator SortedSet::iterator() const {
	return SortedSetIterator(*this);
}

// checks if the current set (this) is a subset of s(the set recieved as parameter)
//      BC : Theta(1) - when the current set is empty
//      WC : Theta(setSize) - when we need to check all elements of the current set
//      AC: O(setSize)
bool SortedSet::isSubsetOf(SortedSet& s) const {
    if (this->isEmpty()) {
        return true; // An empty set is always a subset of any set.
    }

    SortedSetIterator it = this->iterator();
    it.first();
    while (it.valid()) {
        if (!s.search(it.getCurrent())) {
            return false; // If any element is not found in `s`, return false.
        }
        it.next();
    }
    return true; // All elements are found in `s`.
}

// Complexity: Theta(1)
SortedSet::~SortedSet() {
    delete[] bst.info;
    delete[] bst.right;
    delete[] bst.left;
    delete[] bst.parent;
}

// Complexity: Theta(log setSize)
int SortedSet::getMinimum(int node) const {
    int current = node;
    while (bst.left[current] != NULL_POS)
        current = bst.left[current];
    return current;
}