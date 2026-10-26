#pragma once
#include "SortedSet.h"

class SortedSetIterator {
    friend class SortedSet;

private:
    const SortedSet& sortedSet;
    Node* currentNode; // Pointer to the current node in the linked list

public:
    // Constructor
    SortedSetIterator(const SortedSet& m);

    // Resets the iterator to the first element
    void first();

    // Advances the iterator to the next element
    void next();

    // Returns the current element
    TElem getCurrent();

    // Checks if the iterator is valid
    bool valid() const;

    void jumpBackward(int);
};