"""
1. Implement a menu
2. Function for Interpolation search
3. Function for Selection sort
4. Function for Gnome sort
"""
import random

def menu_options():
    print("1 - Generate a list of n random natural numbers. Generated numbers must be between 0 and 1000")
    print("2 - Interpolation search")
    print("3 - Sort the list using the Selection sort")
    print("4 - Sort the list using the Gnome sort")
    print("5 - Print the list")
    print("0 - Exit")

def InterpolationSearch(arr, low, high, x):
    if low <= high and arr[low] <= x <= arr[high]:
        pos = int(low + (x - arr[low]) * (high - low) / (arr[high] - arr[low]))

        if arr[pos] == x:
            return pos

        if arr[pos] < x:
            return InterpolationSearch(arr, pos + 1, high, x)

        if arr[pos] > x:
            return InterpolationSearch(arr, low, pos - 1, x)
    return -1

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
            if count % step == 0 and count != 0 and step != 0:
                print(arr)

    return arr


def menu():
    generated = 0
    sorted = 0
    while True:
        menu_options()
        options = int(input("Enter your choice: "))
        if options == 1:
            n = int(input("Enter a number: "))
            mylist = random.sample(range(1001), n)
            generated = 1
            sorted = 0

        elif options == 2:
            if generated == 0 or sorted == 0:
                print("Could not proceed! Make sure your list is generated and sorted.")
            else:
                value = int(input("Input a number to search it in the array: "))
                position = InterpolationSearch(mylist, 0, n - 1, value)
                if position == -1:
                    print("Sorry, the value is not in the list")
                else:
                    print("Your value is at the position ", position + 1)

        elif options == 3:
            if generated == 0:
                print("Could not proceed! Make sure your list is generated.")
            else:
                step = int(input("Enter a step: "))
                mylist = SelectionSort(mylist, n, step)
                sorted = 1
        elif options == 4:
            if generated == 0:
                print("Could not proceed! Make sure your list is generated.")
            else:
                step = int(input("Enter a step: "))
                mylist = GnomeSort(mylist, n, step)
                sorted = 1
        elif options == 0:
            print("You exited the program")
            break
        elif options == 5:
            print(mylist)
        else:
            print("Invalid option")

def tests():
    numbers = [0, 23, 14, 16, 9, 10, 5, 999]
    assert SelectionSort(numbers, len(numbers), 0) == sorted(numbers)
    assert GnomeSort(numbers, len(numbers), 0) == sorted(numbers)
    assert GnomeSort([], 0, 0) == []
    numbers2 = [0, 10, 25, 57, 92, 104, 421]
    assert InterpolationSearch(numbers2, 0, len(numbers2) - 1, 57) == 3
    assert InterpolationSearch(numbers2, 0, len(numbers2) - 1, 200) == -1

tests()
menu()



