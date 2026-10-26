#pragma once
//DO NOT INCLUDE SETITERATOR

//DO NOT CHANGE THIS PART
typedef int TElem;
typedef TElem TComp;
typedef bool(*Relation)(TComp, TComp);
#define NULL_TELEM -11111
#define INIT_CAPACITY 2
class SortedSetIterator;


class SortedSet {
	friend class SortedSetIterator;
private:
		Relation relation;
        int capacity, sizeOfArray;
        int firstEmpty;
public:
		TComp* elements;
        int *next;
        int head;
private:
  		void resize();
		void findCurrentAndPrevious(TComp elem, int &current, int &previous);

public:
	//constructor
	SortedSet(Relation r);

	//adds an element to the sorted set
	//if the element was added, the operation returns true, otherwise (if the element was already in the set) 
	//it returns false
	bool add(TComp e);

	
	//removes an element from the sorted set
	//if the element was removed, it returns true, otherwise false
	bool remove(TComp e);

	//checks if an element is in the sorted set
	bool search(TElem elem) const;


	//returns the number of elements from the sorted set
	int size() const;

	//checks if the sorted set is empty
	bool isEmpty() const;

	//returns an iterator for the sorted set
	SortedSetIterator iterator() const;

	// destructor
	~SortedSet();

	// keeps only the elemnts which appear in s as well (assume both SortedSets use the same relation)
	void intersection(const SortedSet& s);
};
