#include "SortedSetIterator.h"
#include <exception>

using namespace std;

// BC(theta(1)), WC(theta(1)), AC(theta(1))
SortedSetIterator::SortedSetIterator(const SortedSet &m) : sortedSet(m) {
    this->currentNode = sortedSet.head; // Start at the head of the list
}

// BC(theta(1)), WC(theta(1)), AC(theta(1))
void SortedSetIterator::first() {
    this->currentNode = sortedSet.head; // Reset to the head of the list
}

// BC(theta(1)), WC(theta(1)), AC(theta(1))
void SortedSetIterator::next() {
    if (valid())
        this->currentNode = this->currentNode->next; // Move to the next node
    else
        throw std::exception();
}

// BC(theta(1)), WC(theta(1)), AC(theta(1))
TElem SortedSetIterator::getCurrent() {
    if (valid())
        return this->currentNode->data; // Return the data of the current node
    else
        throw std::exception();
}

// BC(theta(1)), WC(theta(1)), AC(theta(1))
bool SortedSetIterator::valid() const {
    return this->currentNode != nullptr; // Check if the current node is valid
}

// BC(theta(1)), WC(theta(n)), AC(O(n))
void SortedSetIterator::jumpBackward(int k) {
    if (!valid() || k <= 0) {
        throw std::exception(); // Invalid iterator or invalid k
    }

    Node* current = sortedSet.head;
    Node* previous = nullptr;
    int steps = 0;

    // Traverse the list to find the current node and track the previous nodes
    while (current != this->currentNode) {
        previous = current;
        current = current->next;
        ++steps;
    }

    // Check if there are enough elements to move k steps back
    if (steps < k) {
        this->currentNode = nullptr; // Make the iterator invalid
    } else {
        // Traverse again to find the node k steps back
        current = sortedSet.head;
        for (int i = 0; i < steps - k; ++i) {
            current = current->next;
        }
        this->currentNode = current; // Update the current node
    }
}