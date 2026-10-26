#include "SortedSet.h"
#include "SortedSetIterator.h"
#include <climits>

// WC = BC = AC = Θ(m + n) | m buckets and n nodes
void SortedSet::clearTable() {
    for (int i = 0; i < this->capacity; ++i) {
        Node* current = this->table[i];
        while (current != nullptr) {
            Node* next = current->next;
            delete current;
            current = next;
        }
        this->table[i] = nullptr;
    }
}
// WC = BC = AC = theta(n)
void SortedSet::resize(int newCapacity) {
    Node** oldTable = this->table;
    int oldCapacity = this->capacity;

    this->capacity = newCapacity;
    this->table = new Node*[newCapacity];
    for (int i = 0; i < newCapacity; ++i)
        this->table[i] = nullptr;

    for (int i = 0; i < oldCapacity; ++i) {
        Node* current = oldTable[i];
        while (current != nullptr) {
            int position = hashFunction(current->value);
            Node* newNode = new Node{current->value, this->table[position]};
            this->table[position] = newNode;
            current = current->next;
        }
    }

    // clean up old table
    for (int i = 0; i < oldCapacity; ++i) {
        Node* current = oldTable[i];
        while (current != nullptr) {
            Node* next = current->next;
            delete current;
            current = next;
        }
    }
    delete[] oldTable;
}

// WC = BC = AC = theta(1)
SortedSet::SortedSet(Relation r) {
    this->rel = r;
    this->capacity = INITIAL_CAPACITY;
    this->nrElements = 0;
    this->table = new Node*[this->capacity];
    for (int i = 0; i < this->capacity; ++i)
        this->table[i] = nullptr;
}

// WC: theta(n) BC: theta(1)  AC: theta(1)
bool SortedSet::add(TComp elem) {
    if (search(elem))
        return false;

    if (this->requiresResize())
        this->resize(this->capacity * 2);

    int position = hashFunction(elem);
    Node* newNode = new Node{elem, this->table[position]};
    this->table[position] = newNode;
    ++this->nrElements;
    return true;
}

// WC: theta(n) BC: theta(1)  AC: theta(1)
bool SortedSet::remove(TComp elem) {
    int position = hashFunction(elem);
    Node* current = this->table[position];
    Node* prev = nullptr;

    while (current != nullptr && current->value != elem) {
        prev = current;
        current = current->next;
    }

    if (current == nullptr)
        return false;

    if (prev == nullptr) {
        this->table[position] = current->next;
    } else {
        prev->next = current->next;
    }

    delete current;
    --this->nrElements;

    if (this->requireDownsize() && this->capacity > INITIAL_CAPACITY)
        this->resize(this->capacity / 2);

    return true;
}

// WC: theta(n) BC: theta(1)  AC: theta(1)
bool SortedSet::search(TElem elem) const {
    int position = hashFunction(elem);
    Node* current = this->table[position];

    while (current != nullptr) {
        if (current->value == elem)
            return true;
        current = current->next;
    }
    return false;
}

// WC: theta(1) BC: theta(1)  AC: theta(1)
int SortedSet::size() const {
    return this->nrElements;
}

// WC: theta(1) BC: theta(1)  AC: theta(1)
bool SortedSet::isEmpty() const {
    return this->nrElements == 0;
}

SortedSetIterator SortedSet::iterator() const {
    return SortedSetIterator(*this);
}

// WC = BC = AC = theta(m + n)
SortedSet::~SortedSet() {
    clearTable();
    delete[] this->table;
}

// WC: theta(n) BC: theta(1)  AC: O(n)
int SortedSet::getRange() const {
    if (this->isEmpty()) {
        return -1;
    }

    int minElem = INT_MAX;
    int maxElem = INT_MIN;

    for (int i = 0; i < this->capacity; ++i) {
        Node* current = this->table[i];
        while (current != nullptr) {
            if (current->value < minElem) {
                minElem = current->value;
            }
            if (current->value > maxElem) {
                maxElem = current->value;
            }
            current = current->next;
        }
    }

    return maxElem - minElem;
}