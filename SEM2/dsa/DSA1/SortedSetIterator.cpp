#include "SortedSetIterator.h"
#include <exception>

using namespace std;

// Constructor - theta(1)
SortedSetIterator::SortedSetIterator(const SortedSet& m) : sortedSet(m) {
	this->currentIndex = 0;
}

// Resets iterator to the first element - theta(1)
void SortedSetIterator::first() {
	this->currentIndex = 0;
}

// Moves to the next element - theta(1)
void SortedSetIterator::next() {
	if (!valid())
		throw std::exception();
	this->currentIndex++;
}

// Moves to the previous element - theta(1)
void SortedSetIterator::previous() {
	if (!valid())
		throw std::exception();
	if (this->currentIndex == 0)
		this->currentIndex = -1; // makes iterator invalid
	else
		this->currentIndex--;
}

// Returns the current element - theta(1)
TElem SortedSetIterator::getCurrent() {
	if (!valid())
		throw std::exception();
	return sortedSet.elements[this->currentIndex];
}

// Checks if iterator is valid - theta(1)
bool SortedSetIterator::valid() const {
	return this->currentIndex >= 0 && this->currentIndex < sortedSet.size();
}
