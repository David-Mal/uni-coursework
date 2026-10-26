from random import randint

#
# Write the implementation for A5 in this file
#

#
# Write below this comment
# Functions to deal with complex numbers -- list representation
# -> There should be no print or input statements in this section
# -> Each function should do one thing only
# -> Functions communicate using input parameters and their return values
#
"""
Write methods to create a new complex number, 
to get and set each number's real and imaginary parts 
as well as to transform a number into its str representation.
"""
# Getters for LIST implementation
def get_real_part_LIST(complex_number: list):
    return complex_number[0]

def get_imaginary_part_LIST(complex_number: list):
    return complex_number[1]

# Getters for DICT implementation
def get_real_part_DICT(complex_number: dict):
    return complex_number['real_part']

def get_imaginary_part_DICT(complex_number: dict):
    return complex_number['imaginary_part']

# Setters for LIST implementation
def set_real_part_LIST(complex_number: list, real_part: int):
    complex_number[0] = real_part

def set_imaginary_part_LIST(complex_number: list, real_part: int):
    complex_number[1] = real_part

# Setters for DICT implementation
def set_real_part_DICT(complex_number: dict, real_part: int):
    complex_number['real_part'] = real_part

def set_imaginary_part_DICT(complex_number: dict, imaginary_part: int):
    complex_number['imaginary_part'] = imaginary_part


def convert_complex_number_tostring_LIST(complex_number: list) -> str:
    """
       Converts a complex number from list representation into string representation.

       :param complex_number: the complex number to be converted
       :returns a string representing the complex number
    """
    real_part = get_real_part_LIST(complex_number)
    imaginary_part = get_imaginary_part_LIST(complex_number)

    if imaginary_part > 0:
        return f"{real_part} + {imaginary_part}i"

    else:
        return f"{real_part} - {-imaginary_part}i"


def random_complex_number_LIST() -> list:
    """
       Generates a random complex numbers

       :returns: a list containing the real part and the imaginary part.
    """

    real_part = randint(-100, 100)
    imaginary_part = randint(-100, 100)

    return [real_part, imaginary_part]


"""
10 complex numbers already available at program startup. 
This is to ensure functionalities can be checked easily
"""


# Generates random complex numbers. The representation will be a list of lists.
def random_list_of_complex_numbers_LIST() -> list:
    """
       Generates a list of complex numbers, using the previous function to generate each complex number.

       :returns: a list of lists.
    """
    return [random_complex_number_LIST() for _ in range(10)]


#
# Write below this comment
# Functions to deal with complex numbers -- dict representation
# -> There should be no print or input statements in this section
# -> Each function should do one thing only
# -> Functions communicate using input parameters and their return values
#

def convert_complex_number_tostring_DICT(complex_number: dict) -> str:
    """
       Converts a complex number from dictionary representation into string representation.

       :param complex_number: the complex number to be converted
       :returns a string representing the complex number
    """
    real_part = get_real_part_DICT(complex_number)
    imaginary_part = get_imaginary_part_DICT(complex_number)

    if imaginary_part > 0:
        return f"{real_part} + {imaginary_part}i"
    else:
        return f"{real_part} - {-imaginary_part}i"


def random_complex_number_DICT() -> dict:
    """
          Generates a random complex numbers

          :returns: a dictionary containing the real part and the imaginary part.
    """
    real_part = randint(-100, 100)
    imaginary_part = randint(-100, 100)

    return {"real_part": real_part, "imaginary_part": imaginary_part}


"""
10 complex numbers already available at program startup. 
This is to ensure functionalities can be checked easily
"""


def random_list_of_complex_numbers_DICT() -> list:
    """
      Generates a random complex numbers

      :returns: a list of dictionaries containing the real part and the imaginary part.
    """
    return [random_complex_number_DICT() for _ in range(10)]


#
# Write below this comment
# Functions that deal with subarray/subsequence properties
# -> There should be no print or input statements in this section
# -> Each function should do one thing only
# -> Functions communicate using input parameters and their return values
#

"""
 adaug element in array. ma mut pe urmatorul element ma asigur ca nu este egal cu celelalte elemente deja introduse
 if so, il adaug si continui procesul. Cand am gasit un element care este deja egal cu alt element din array(de ex arr[j] = arr[i])
 salvez array-ul curent intr-o variabila max si dupa reincep cu un nou array de la arr[i + 1] incolo.

"""


def function_setA(array_of_complex_numbers: list, representation: str):
    """
      Determines the length and the elements of:
      One of the longest subarrays of distinct numbers.

      :param array_of_complex_numbers: the list of complex numbers
      :param representation: the way in which we represent a complex number (list / dict)
      :returns the length of the longest subarray and the elements of it, an integer and a list
    """

    if representation not in {"list", "dict"}:
        return -1, []
    maximum_length = 0

    current_array = [array_of_complex_numbers[0]]
    longest_array = []
    for i in range(1, len(array_of_complex_numbers)):

        if array_of_complex_numbers[i] not in current_array:
            current_array.append(array_of_complex_numbers[i])

        else:
            new_current_array_index = 0  # we search the index in current_array at which we found  duplicate
            for index in range(len(current_array)):
                if array_of_complex_numbers[i] == current_array[index]:
                    new_current_array_index = index
                    break

            current_array = current_array[
                            new_current_array_index + 1:]  # we continue computing the new current subarray from the next element after the duplicate
            current_array.append(array_of_complex_numbers[
                                     i])  # we made sure we discarded the duplicate from the current array so now we can add this current element of array_of_complex_numbers to the current array

        if len(current_array) > maximum_length:
            maximum_length = len(current_array)
            longest_array = current_array[:]
    longest_array_toString = []
    for x in longest_array:
        if representation == "list":
            longest_array_toString.append(convert_complex_number_tostring_LIST(x))
        elif representation == "dict":
            longest_array_toString.append(convert_complex_number_tostring_DICT(x))
    return maximum_length, longest_array_toString


def function_setB(array_of_complex_numbers: list,
                  representation: str):  # O(n^2) - 2 nested for loops each having n iterations
    """
          Determines the length and the elements of:
          One of the longest alternating subsequences, when considering each number's real part

          :param array_of_complex_numbers: the list of complex numbers
          :param representation: the way in which we represent a complex number (list / dict)
          :returns the length of the longest alternating subsequences and the elements of it, an integer and a list
    """

    if representation not in {"list", "dict"}:
        return -1, []

    n = len(array_of_complex_numbers)
    if n == 0:
        return 0, []

    # Extract real parts for easier reference
    if representation == "list":
        real_parts = [c[0] for c in array_of_complex_numbers]  # real_parts will be a list of the real parts of all the complex numbers from array_of_complex_numbers

    else:
        real_parts = [c["real_part"] for c in array_of_complex_numbers]

    # Initialize DP arrays

    # inc[i] = the longest alternating sequence ending in i with an increasing trend.
    inc = [1] * n

    # dec[i] = the length of the longest alternating subsequence ending at each position with a decreasing trend.
    dec = [1] * n

    # Track the previous indices in the subsequence for reconstruction later.
    prev_inc = [-1] * n  # Stores the previous index for an increasing trend
    prev_dec = [-1] * n  # Stores the previous index for a decreasing trend

    # Fill DP arrays
    for i in range(1, n):
        for j in range(i):

            if real_parts[i] > real_parts[j]:
                if dec[j] + 1 > inc[i]:  # Update only if this increases the subsequence length
                    inc[i] = dec[j] + 1
                    prev_inc[i] = j

            elif real_parts[i] < real_parts[j]:
                if inc[j] + 1 > dec[i]:  # Update only if this increases the subsequence length
                    dec[i] = inc[j] + 1
                    prev_dec[i] = j

    # Find the maximum length and the last index of the longest alternating subsequence
    max_length = 0
    last_index = 0
    is_increasing = True

    for i in range(n):
        if inc[i] > max_length:
            max_length = inc[i]
            last_index = i
            is_increasing = True

        if dec[i] > max_length:
            max_length = dec[i]
            last_index = i
            is_increasing = False

    # Reconstruct the sequence using the prev arrays
    sequence = []
    while last_index != -1:
        if representation == "list":
            sequence.append(convert_complex_number_tostring_LIST(array_of_complex_numbers[last_index]))  # we build the array of elements from the longest alternating sequence starting from the end

        elif representation == "dict":
            sequence.append(convert_complex_number_tostring_DICT(array_of_complex_numbers[last_index]))

        if is_increasing:
            last_index = prev_inc[last_index]
            is_increasing = False

        else:
            last_index = prev_dec[last_index]
            is_increasing = True

    sequence.reverse()  # Reverse to get the sequence in the correct order
    return max_length, sequence


#
# Write below this comment
# UI section
# Write all functions that have input or print statements here
# Ideally, this section should not contain any calculations relevant to program functionalities
#
def read_one_complex_number():
    """
          Function used to read the real and imaginary parts of a complex number
          :returns 2 integers, the real part and the imaginary part of a complex numbber
    """

    complex_number_console = input("Enter a complex number of the form a+ib: ")

    # Identify the position of the '+' or '-' for the imaginary part
    if '+' in complex_number_console[1:]:  # start from the second character to avoid the sign in the real part
        split_index = complex_number_console[1:].index('+') + 1
    elif '-' in complex_number_console[1:]:
        split_index = complex_number_console[1:].index('-') + 1
    else:
        raise ValueError("Invalid format. Please enter in the form a+ib.")

    # Split the string into real and imaginary parts
    real_part = complex_number_console[:split_index]
    imaginary_part = complex_number_console[split_index:-1]  # exclude the last 'i' character

    # Convert to integers
    real_part = int(real_part)
    imaginary_part = int(imaginary_part)
    return real_part, imaginary_part


def Option1_read_complex_number(array_of_complex_numbers, representation: str) -> None:
    """
          Reads a number n and n complex numbers and accordingly to the representation parameter
          the complex numbers are saved in either a list or a dictionary

          :param array_of_complex_numbers: the list of complex numbers
          :param representation: the way in which we represent a complex number (list / dict)
    """

    dim = int(input("Enter the amount of complex numbers you want to read from console: "))
    for i in range(dim):
        real_part, imaginary_part = read_one_complex_number()

        if representation == "list":
            array_of_complex_numbers.append([real_part, imaginary_part])

        elif representation == "dict":
            array_of_complex_numbers.append({"real_part": real_part, "imaginary_part": imaginary_part})


def Option2_display_complex_number(array_of_complex_numbers, representation: str) -> None:
    """
          Displays the list of complex numbers in the list or dict representation

          :param array_of_complex_numbers: the list of complex numbers
          :param representation: the way in which we represent a complex number (list / dict)
    """

    if representation == "list":
        for complex_number in array_of_complex_numbers:
            print(convert_complex_number_tostring_LIST(complex_number))

    elif representation == "dict":
        for complex_number in array_of_complex_numbers:
            print(convert_complex_number_tostring_DICT(complex_number))


def print_menu() -> None:
    """
        Function used to print the choices the user is able to make

    """
    print("\n---------------\n")
    print("1. Read a list of complex numbers from the console.")
    print("2. Display the entire list of numbers.")
    print("3. Display the following properties:")
    print("   3.1 A longest subarray of distinct numbers.")
    print(
        "   3.2 A longest alternating subsequence, when considering each number's real part ."
    )
    print("4. Exit.")
    print("\n---------------\n")


def generate_array():
    """
          Generates the random list of complex numbers, and decides between the 2 possible representations
    """

    try:
        representation = input("Choose the data structure you want to use for this program:")

        if representation == "list":
            array_of_complex_numbers = random_list_of_complex_numbers_LIST()

        elif representation == "dict":
            array_of_complex_numbers = random_list_of_complex_numbers_DICT()

        return array_of_complex_numbers, representation

    except ValueError as exception_error:
        print(exception_error)
        return [], ""


def main():
    array_of_complex_numbers, representation = generate_array()

    while True:
        print_menu()
        choice = input("Enter your choice: ")
        if choice == "1":
            Option1_read_complex_number(array_of_complex_numbers, representation)

        elif choice == "2":
            Option2_display_complex_number(array_of_complex_numbers, representation)

        elif choice == "3":
            length_function_setA, elements_function_setA = function_setA(array_of_complex_numbers, representation)
            length_function_setB, elements_function_setB = function_setB(array_of_complex_numbers, representation)
            print("\n3.1:")
            print(f"Length of the longest subarray of distinct complex numbers: {length_function_setA}")
            print(f"Elements of the longest subarray of distinct complex numbers: {elements_function_setA}")
            print("\n3.2:")
            print(f"Length of the longest alternating subsequence: {length_function_setB}")
            print(f"Elements of the longest alternating subsequence: {elements_function_setB}")

        elif choice == "4":
            print("Exiting...")
            return

        else:
            print("Invalid choice. Please try again.")


def test_get_set_real_imaginary_LIST():
    # Test getters and setters for list representation
    complex_number = [0, 0]
    set_real_part_LIST(complex_number, 5)
    set_imaginary_part_LIST(complex_number, 10)
    assert get_real_part_LIST(complex_number) == 5
    assert get_imaginary_part_LIST(complex_number) == 10


def test_get_set_real_imaginary_DICT():
    # Test getters and setters for dict representation
    complex_number = {"real_part": 0, "imaginary_part": 0}
    set_real_part_DICT(complex_number, 5)
    set_imaginary_part_DICT(complex_number, 10)
    assert get_real_part_DICT(complex_number) == 5
    assert get_imaginary_part_DICT(complex_number) == 10


def test_convert_complex_number_tostring():
    # Test string conversion for both representations
    complex_number_list = [5, -10]
    complex_number_dict = {"real_part": 5, "imaginary_part": -10}
    assert convert_complex_number_tostring_LIST(complex_number_list) == "5 - 10i"
    assert convert_complex_number_tostring_DICT(complex_number_dict) == "5 - 10i"


def test_random_complex_number():
    # Test generation of random complex numbers
    complex_number_list = random_complex_number_LIST()
    complex_number_dict = random_complex_number_DICT()
    assert isinstance(complex_number_list, list) and len(complex_number_list) == 2
    assert isinstance(complex_number_dict, dict) and "real_part" in complex_number_dict and "imaginary_part" in complex_number_dict


def test_function_setA():
    # Test finding the longest subarray of distinct numbers
    complex_numbers_list = [[1, 2], [3, 4], [5, 6], [1, 2]]
    complex_numbers_dict = [
        {"real_part": 1, "imaginary_part": 2},
        {"real_part": 3, "imaginary_part": 4},
        {"real_part": 5, "imaginary_part": 6},
        {"real_part": 1, "imaginary_part": 2},
    ]
    length, elements = function_setA(complex_numbers_list, "list")
    assert length == 3
    assert elements == ["1 + 2i", "3 + 4i", "5 + 6i"]

    length, elements = function_setA(complex_numbers_dict, "dict")
    assert length == 3
    assert elements == ["1 + 2i", "3 + 4i", "5 + 6i"]


def test_function_setB():
    # Test finding the longest alternating subsequence
    complex_numbers_list = [[1, 2], [3, 4], [2, 5], [4, 6], [1, -1]]
    complex_numbers_dict = [
        {"real_part": 1, "imaginary_part": 2},
        {"real_part": 3, "imaginary_part": 4},
        {"real_part": 2, "imaginary_part": 5},
        {"real_part": 4, "imaginary_part": 6},
        {"real_part": 1, "imaginary_part": -1},
    ]
    length, elements = function_setB(complex_numbers_list, "list")
    assert length > 1  # Exact length depends on the test data; check for more than 1
    assert isinstance(elements, list) and all(isinstance(e, str) for e in elements)

    length, elements = function_setB(complex_numbers_dict, "dict")
    assert length > 1
    assert isinstance(elements, list) and all(isinstance(e, str) for e in elements) # isinstance(elements, list) checks if elements is a list


def test_all():
    test_get_set_real_imaginary_LIST()
    test_get_set_real_imaginary_DICT()
    test_convert_complex_number_tostring()
    test_random_complex_number()
    test_function_setA()
    test_function_setB()

if __name__ == "__main__":
    main()
    test_all()


