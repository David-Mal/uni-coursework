"""
1. Implement a menu
2. Function for Interpolation search
3. Function for Selection sort
4. Function for Gnome sort
"""
import random
import time
import texttable


def menu_options():
    print("1 - Generate a list of n random natural numbers. Generated numbers must be between 0 and 1000")
    print("2 - Interpolation search")
    print("3 - Sort the list using the Selection sort")
    print("4 - Sort the list using the Gnome sort")
    print("5 - Print the list")
    print("6 - Worst Case Complexity")
    print("7 - Average case complexity")
    print("8 - Best Case Complexity")
    print("0 - Exit")

'''
The position estimate (pos) is calculated based on the target's value relative 
to the range A[low] to A[high]. Due to uniform distribution, 
this estimate is close to the actual position, effectively dividing the array into 
segments much smaller than half. The same logic applies recursively to the new subarray, 
leading to a double-logarithmic reduction in search space.

----------------------------------------------------------------------------------------
Each iteration reduces the search space by a 
factor related to the logarithm of the remaining elements, not just by half.
----------------------------------------------------------------------------------------

The best case scenario is when the key is located at the first estimated position. 
This scenario occurs when the interpolation formula precisely predicts the target's location immediately. O(1)

When the elements are uniformly distributed, the estimated position 
rapidly converges to the target, resulting in logarithmic growth of the search steps. O(log log(n))
When the data is uniformly distributed, Interpolation Search can achieve an average-case 
time complexity of O(log log n) because each probe significantly 
reduces the search space more efficiently than Binary Search's fixed halving.

Worst case such as in cases of highly skewed or non-uniform distributions, 
the interpolation may poorly estimate positions, degrading the performance to linear time O(n)
'''
def InterpolationSearch(arr, low, high, x):
    if low <= high and arr[low] <= x <= arr[high]:

        if arr[high] == arr[low]:   #check for the case when arr[high] and arr[low] are equal in order to avoid a division by 0 in the formula for computing the value for "pos"
            if arr[low] == x:
                return low
            else:
                return -1

        pos = int(low + (x - arr[low]) * (high - low) / (arr[high] - arr[low]))

        if arr[pos] == x:
            return pos

        if arr[pos] < x:
            return InterpolationSearch(arr, pos + 1, high, x)

        if arr[pos] > x:
            return InterpolationSearch(arr, low, pos - 1, x)
    return -1


'''
Even if the array is already sorted, Selection Sort still goes through all the elements 
to find the minimum in each pass. It performs the same number of comparisons regardless of the initial 
order of elements.

On average, Selection Sort performs a quadratic number of comparisons and a linear number of swaps

In the worst-case scenario (e.g., when the array is sorted in reverse order), 
Selection Sort still performs the same number of comparisons and swaps as in the other cases. 
The time complexity remains quadratic.
'''

def SelectionSort(arr, n, step):
    count_swaps = 0
    for i in range(n):
        min_index = i
        for j in range(i + 1, n):
            if arr[j] < arr[min_index]:
                min_index = j
        if arr[i] != arr[min_index]:
            arr[i], arr[min_index] = arr[min_index], arr[i]
            count_swaps += 1
            if step != 0 and count_swaps % step == 0:
                print(arr)

    return arr

'''
In best case scenario we have O(n) when the array is already sorted. 
The algorithm only makes a single pass without swaps, thus moving through the whole list.

Average case:
For randomly ordered elements, the algorithm performs a quadratic number of comparisons/swaps.
The nested nature of comparing and potentially swapping elements leads to a quadratic 
number of operations relative to the number of elements.

Worst case:
Happens when the array is sorted in reverse order, requiring the maximum number of swaps.
'''
def GnomeSort(arr, n, step):
    ind = 0
    count = 0
    while ind < n:
        if ind == 0:
            ind = ind + 1
        if arr[ind] >= arr[ind - 1]:
            ind = ind + 1
        elif arr[ind] < arr[ind - 1]:
            arr[ind], arr[ind - 1] = arr[ind - 1], arr[ind]
            ind = ind - 1
            count += 1
            if count != 0 and step != 0 and count % step == 0:
                print(arr)

    return arr

def CheckExecutionTime_sort(SortingMethod, arr: list) -> str:
    startingMeasuringTime = time.time()
    SortingMethod(arr, len(arr), 0)
    endingMeasuringTime = time.time()
    elapsed_time = endingMeasuringTime - startingMeasuringTime
    return f"{elapsed_time:.6f}s"

def CheckExecutionTime_search(SearchingMethod, arr: list, value: int) -> str:
    startingMeasuringTime = time.perf_counter()
    SearchingMethod(arr, 0, len(arr) - 1, value)
    endingMeasuringTime = time.perf_counter()
    elapsed_time = endingMeasuringTime - startingMeasuringTime
    return f"{elapsed_time:.6f}s"

def PrintComplexityTable():
    tableObj = texttable.Texttable()
    tableObj.set_cols_align(["c", "c", "c", "c"])
    tableObj.header(["Algorithm", "Best Case", "Average Case", "Worst Case"])

    # Adding complexities for each algorithm
    tableObj.add_row([
        "Selection Sort",
        "O(n^2)",
        "O(n^2)",
        "O(n^2)"
    ])
    tableObj.add_row([
        "Gnome Sort",
        "O(n)",
        "O(n^2)",
        "O(n^2)"
    ])
    tableObj.add_row([
        "Interpolation Search",
        "O(1)",
        "O(log log n)",
        "O(n)"
    ])

    print("\nTheoretical Complexities:")
    print(tableObj.draw())


def CheckMethodTime(case: str):
    PrintComplexityTable()  # Print the complexities table first
    length = random.randint(200, 500)
    tableObj = texttable.Texttable()
    tableObj.set_cols_align(["c", "c", "c", "c"])
    tableObj.header(["LENGTH OF THE LIST", "SELECTION SORT", "GNOME SORT", "INTERPOLATION SEARCH"])

    for _ in range(5):
        randomList = []
        randomValue = 0
        if case == "average":
            randomList = random.sample(range(1, length * 2), length)
            randomValue = random.choice(randomList)
        elif case == "worst":
            randomList = list(range(length, 0, -1))
            randomValue = -1
        elif case == "best":
            randomList = list(range(length))
            randomValue = random.choice(randomList)

        # Use copies of the list for sorting to prevent in-place modifications
        list_for_selection_sort = randomList.copy()
        list_for_gnome_sort = randomList.copy()

        # For Interpolation Search, ensure the list is sorted
        sorted_list = sorted(randomList)
        InterpolationSearchTime = CheckExecutionTime_search(InterpolationSearch, sorted_list, randomValue)

        GnomeSortTime = CheckExecutionTime_sort(GnomeSort, list_for_gnome_sort)
        SelectionSortTime = CheckExecutionTime_sort(SelectionSort, list_for_selection_sort)

        tableObj.add_row([length, SelectionSortTime, GnomeSortTime, InterpolationSearchTime])
        length = length * 2 # Double the length for the next iteration

    print(tableObj.draw())

def menu():
    generated = False
    sorted_flag = False
    mylist = []
    while True:
        menu_options()
        try:
            options = int(input("Enter your choice: "))
        except ValueError:
            print("Invalid input. Please enter a number between 0 and 8.")
            continue

        if options == 1:
            try:
                n = int(input("Enter the number of elements to generate: "))
                if n <= 0:
                    print("Number must be greater than 0.")
                    continue
                mylist = random.sample(range(1001), n)
                generated = True
                sorted_flag = False
                print("List generated.")
            except ValueError:
                print("Invalid input. Please enter a positive integer.")
        elif options == 2:
            if not generated or not sorted_flag:
                print("Could not proceed! Make sure your list is generated and sorted.")
            else:
                try:
                    value = int(input("Input a number to search in the array: "))
                    position = InterpolationSearch(mylist, 0, len(mylist) - 1, value)
                    if position == -1:
                        print("Sorry, the value is not in the list.")
                    else:
                        print(f"Your value is at position {position + 1}")
                except ValueError:
                    print("Invalid input. Please enter an integer.")
        elif options == 3:
            if not generated:
                print("Could not proceed! Make sure your list is generated.")
            else:
                try:
                    step = int(input("Enter a step (0 for no intermediate outputs): "))
                    mylist = SelectionSort(mylist, len(mylist), step)
                    sorted_flag = True
                    print("List sorted using Selection Sort.")
                except ValueError:
                    print("Invalid input. Please enter an integer.")
        elif options == 4:
            if not generated:
                print("Could not proceed! Make sure your list is generated.")
            else:
                try:
                    step = int(input("Enter a step (0 for no intermediate outputs): "))
                    mylist = GnomeSort(mylist, len(mylist), step)
                    sorted_flag = True
                    print("List sorted using Gnome Sort.")
                except ValueError:
                    print("Invalid input. Please enter an integer.")
        elif options == 5:
            if not generated:
                print("List is empty. Please generate a list first.")
            else:
                print("Current List:")
                print(mylist)
        elif options == 6:
            print("WORST CASE COMPLEXITY")
            CheckMethodTime("worst")
        elif options == 7:
            print("AVERAGE CASE COMPLEXITY")
            CheckMethodTime("average")
        elif options == 8:
            print("BEST CASE COMPLEXITY")
            CheckMethodTime("best")
        elif options == 0:
            print("You exited the program.")
            break
        else:
            print("Invalid option. Please enter a number between 0 and 8.")

def tests():
    numbers = [0, 23, 14, 16, 9, 10, 5, 999]
    assert SelectionSort(numbers, len(numbers), 0) == sorted(numbers)
    assert GnomeSort(numbers, len(numbers), 0) == sorted(numbers)
    assert GnomeSort([], 0, 0) == []
    numbers2 = [0, 10, 25, 57, 92, 104, 421]
    assert InterpolationSearch(numbers2, 0, len(numbers2) - 1, 57) == 3
    assert InterpolationSearch(numbers2, 0, len(numbers2) - 1, 200) == -1


if __name__ == "__main__":
    tests()
    menu()



