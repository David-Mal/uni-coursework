#pragma once
typedef int TElem;
typedef TElem TComp;
typedef bool (*Relation)(TComp, TComp);
#define NULL_TELEM -111111
#define INITIAL_CAPACITY 17

class SortedSetIterator;

class SortedSet
{
    friend class SortedSetIterator;

private:
    struct Node {
        TComp value;
        Node* next;
    };

    Relation rel;
    int capacity;
    int nrElements;
    Node** table;

    void resize(int newCapacity);

    int hashFunction(TComp elem) const {
        while (elem < 0)
            elem += this->capacity;
        return elem % this->capacity;
    }

    bool requiresResize() const {
        return this->nrElements == this->capacity;
    }

    bool requireDownsize() const {
        return this->nrElements < this->capacity / 4;
    }

    void clearTable();

public:
    SortedSet(Relation r);
    bool add(TComp e);
    bool remove(TComp e);
    bool search(TElem elem) const;
    int size() const;
    int getCapacity() const { return this->capacity; };
    Relation relation() const { return this->rel; };
    bool isEmpty() const;
    int getRange() const;
    SortedSetIterator iterator() const;
    ~SortedSet();
};
