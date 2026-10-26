#pragma once

#include "SortedSet.h"

class SortedSetIterator {
    friend class SortedSet;

private:
    const SortedSet &sortedSet;
    TElem* sortedValues;
    int length;
    int index;
    SortedSetIterator(const SortedSet &m);

public:
    void collectAndSort();
    void first();
    void next();
    TElem getCurrent();
    bool valid() const;
};
