# Solve the problem from the third set here
"""
Generate the largest perfect number smaller than a given natural number n. If such a number does not exist, a message should be displayed.
A number is perfect if it is equal to the sum of its divisors, except itself.
(e.g. 6 is a perfect number, as 6=1+2+3).
"""

'''
The function sum_of_divisors computes the sum of divisors of a given number n.
The function uses the algorithm of prime factorization and the formula for geometric progression sum
If a number n = p1^e1, where p1 is a prime number then,  n's divisors sum would be:
f = (p1^0 + p1^1 + p1^2 + ... + p1^e1) = p1^(e1+1) - 1 / (p1 - 1)
If n = p1^e1 * p2^e2, meaning n has two distinct prime factors then the sum of n's divisors would be:
f = (p1^0 + p1^1 + p1^2 + ... + p1^e1) * (p2^0 + p2^1 + p2^2 + ... + p2^e2) = p1^(e1+1) - 1 / (p1 - 1) * p2^(e2+1) - 1 / (p2 - 1)
and so on...

So let "p" be the current prime divisor of n and "e" the power to which p appears in n's prime factorization.
In "f" we will compute the geometric progression sum for each prime number "p" found.
In "sd" we will compute the sum of n's divisors by multiplying each f.
'''
def sum_of_divisors(n):
    p = 2
    sd = 1
    while n > 1:
        e = 0
        f = 1
        while n % p == 0:
            n = int(n / p)
            f = f * p
            e = e + 1
        if e != 0:
            f = f * p
            f = f - 1
            f = f / (p - 1)
            sd = sd * f
        if p == 2:
            p = 3
        else:
            p = p + 2
        if p * p >= n:
            p = n
    return sd

try:
    number = int(input("Enter a natural number: "))

    if number <= 6:
        raise ValueError
    x = number - 1
    while sum_of_divisors(x) != x * 2:
        x = x - 1
    print("The largest perfect number smaller than ", number, " is ", x)
except ValueError:
    print("n must be a natural number larger than 6!")
'''
sum_of_divisors(x) gives us the sum of x's divisors, also including x. But in order for x to be a perfect number,
the sum of x's divisors without the number x itself should be equal to x. So: sum_of_divisors(x) - x = x which
is the same with sum_of_divisors(x) = 2 * x
'''