#include <iostream>
#include "SortedSet.h"
#include "SortedSetIterator.h"


SortedSet::SortedSet(Relation r) {
    this->relation = r;
    this->head = nullptr;
    this->sizeOfArray = 0;
}
//BC(theta(1), WC(theta(n)), AC(theta(n))
bool SortedSet::add(TComp elem) {
    if (!head) {
        // If the list is empty, add the first element
        head = new Node{elem, nullptr};
        ++sizeOfArray;
        return true;
    }

    if (head->data == elem) {
        // Element already exists at the head
        return false;
    }

    if (!relation(head->data, elem)) {
        // Insert at the head if the relation is not satisfied
        Node* newNode = new Node{elem, head};
        head = newNode;
        ++sizeOfArray;
        return true;
    }

    Node* current = head;
    while (current->next && relation(current->next->data, elem)) {
        if (current->next->data == elem) {
            // Element already exists in the list
            return false;
        }
        current = current->next;
    }

    // Insert the new element in the correct position
    Node* newNode = new Node{elem, current->next};
    current->next = newNode;
    ++sizeOfArray;
    return true;
}
//BC(theta(1)), WC(theta(n)), AC(theta(n))
bool SortedSet::remove(TComp elem) {
    if (!head) return false;

    if (head->data == elem) {
        Node* toDelete = head;
        head = head->next;
        delete toDelete;
        --sizeOfArray;
        return true;
    }

    Node* current = head;
    while (current->next && current->next->data != elem) {
        current = current->next;
    }

    if (!current->next) return false;

    Node* toDelete = current->next;
    current->next = current->next->next;
    delete toDelete;
    --sizeOfArray;
    return true;
}
//BC(theta(1)), WC(theta(n)), AC(theta(n))
bool SortedSet::search(TComp elem) const {
    Node* current = head;
    while (current && relation(current->data, elem)) {
        if (current->data == elem) return true;
        current = current->next;
    }
    return false;
}
//BC(theta(1)), WC(theta(1)), AC(theta(1))
int SortedSet::size() const {
    return sizeOfArray;
}
//BC(theta(1)), WC(theta(1)), AC(theta(1))
bool SortedSet::isEmpty() const {
    return sizeOfArray == 0;
}
//BC(theta(1)), WC(theta(1)), AC(theta(1))
SortedSetIterator SortedSet::iterator() const {
    return SortedSetIterator(*this);
}
//BC(theta(1)), WC(theta(n)), AC(theta(n))
SortedSet::~SortedSet() {
    while (head) {
        Node* toDelete = head;
        head = head->next;
        delete toDelete;
    }
}