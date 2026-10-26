#include "SortedSetIterator.h"
#include <exception>

using namespace std;

// Constructor
SortedSetIterator::SortedSetIterator(const SortedSet &m) : sortedSet(m) {
    this->sortedValues = nullptr;
    this->length = 0;
    this->index = 0;
    collectAndSort();
    first();
}

// WC: theta(n) BC: theta(n^2)  AC: O(n^2)
void SortedSetIterator::collectAndSort() {
    int count = this->sortedSet.nrElements;
    this->sortedValues = new TElem[count];
    int k = 0;

    for (int i = 0; i < this->sortedSet.capacity; ++i) {
        SortedSet::Node* node = this->sortedSet.table[i];
        while (node != nullptr) {
            this->sortedValues[k++] = node->value;
            node = node->next;
        }
    }

    // simple insertion sort using the relation
    for (int i = 1; i < count; ++i) {
        TElem key = this->sortedValues[i];
        int j = i - 1;
        while (j >= 0 && !this->sortedSet.rel(this->sortedValues[j], key)) {
            this->sortedValues[j + 1] = this->sortedValues[j];
            --j;
        }
        this->sortedValues[j + 1] = key;
    }

    this->length = count;
}

// Set iterator to first valid node
// WC = BC = AC = theta(1)
void SortedSetIterator::first() {
    this->index = 0;
}

// Move to next node
// WC = BC = AC = theta(1)
void SortedSetIterator::next() {
    if (!valid())
        throw exception();
    ++this->index;
}

// Get current element
// WC = BC = AC = theta(1)
TElem SortedSetIterator::getCurrent() {
    if (!valid())
        throw exception();
    return this->sortedValues[this->index];
}

// Check validity
// WC = BC = AC = theta(1)
bool SortedSetIterator::valid() const {
    return this->index < this->length;
}