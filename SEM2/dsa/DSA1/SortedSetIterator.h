#pragma once
#include "SortedSet.h"

//DO NOT CHANGE THIS PART
class SortedSetIterator
{
	friend class SortedSet;
private:
	const SortedSet& sortedSet;
	SortedSetIterator(const SortedSet& m);
	int currentIndex = sortedSet.head;

public:
	void first();
	void next();
	void previous();
	TElem getCurrent();
	bool valid() const;
};

