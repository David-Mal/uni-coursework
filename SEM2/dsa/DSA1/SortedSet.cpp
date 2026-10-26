#include "SortedSet.h"
#include "SortedSetIterator.h"
#include <iostream>
// theta(1)
SortedSet::SortedSet(Relation r) {
    this->relation = r;
    this->capacity = INIT_CAPACITY;
    this->elements = new TComp[this->capacity];
    this->sizeOfArray = 0;
}
// O(n)
bool SortedSet::add(TComp elem) {
    if (search(elem))  // Element already exists
        return false;

    if (sizeOfArray == capacity)  // Resize if needed
        resize();

    // Find the correct position to insert (maintaining sorted order)
    int pos = 0;
    while (pos < sizeOfArray && relation(elements[pos], elem))
        pos++;

    // Shift elements to the right to make space
    for (int i = sizeOfArray; i > pos; i--)
        elements[i] = elements[i - 1];

    elements[pos] = elem;
    sizeOfArray++;
    return true;
}
// O(n)
bool SortedSet::remove(TComp elem) {
    int pos = -1;
    for (int i = 0; i < sizeOfArray; i++) {
        if (elements[i] == elem) {
            pos = i;
            break;
        }
    }
    if (pos == -1) return false;  // Element not found

    // Shift elements to the left to remove the element
    for (int i = pos; i < sizeOfArray - 1; i++)
        elements[i] = elements[i + 1];

    sizeOfArray--;
    return true;
}
// O(log n)
bool SortedSet::search(TComp elem) const {
    // Binary search for efficiency
    int left = 0, right = sizeOfArray - 1;
    while (left <= right) {
        int mid = left + (right - left) / 2;
        if (elements[mid] == elem)
            return true;
        else if (relation(elements[mid], elem))
            left = mid + 1;
        else
            right = mid - 1;
    }
    return false;
}
// theta(n)
void SortedSet::resize() {
    int newCapacity = capacity * 2;
    TComp* newElements = new TComp[newCapacity];
    for (int i = 0; i < sizeOfArray; i++)
        newElements[i] = elements[i];
    delete[] elements;
    elements = newElements;
    capacity = newCapacity;
}
// theta(1)
int SortedSet::size() const {
    return sizeOfArray;
}
// theta(1)
bool SortedSet::isEmpty() const {
    return sizeOfArray == 0;
}
// theta(1)
SortedSetIterator SortedSet::iterator() const {
    return SortedSetIterator(*this);
}
// theta(1)
SortedSet::~SortedSet() {
    delete[] elements;
}