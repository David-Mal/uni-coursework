# Solve the problem from the first set here
'''
First Set
Generate the first prime number larger than a given natural number n.
'''
from logging import raiseExceptions


# problem 1
def is_prime(x):
    if x < 2:
        return False
    if x == 2:
        return True
    if x % 2 == 0:
        return False
    else:
        for i in range(3, int(x**0.5)+1, 2):
            if x % i == 0:
                return False
    return True


try:
    n = int(input("Enter natural number n: "))
    if n < 0:
        raise ValueError
    x = n + 1
    while is_prime(x) == False:
        x = x + 1
    print(x, "is the first prime natural number larger than", n)
except ValueError:
    print("n must be a natural number!")

