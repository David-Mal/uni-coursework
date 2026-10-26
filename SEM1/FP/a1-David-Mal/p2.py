# Solve the problem from the second set here
"""
Find the smallest number m from the Fibonacci sequence,
defined by f[0]=f[1]=1, f[n]=f[n-1] + f[n-2], for n > 2,
larger than the given natural number n. (e.g. for n = 6, m = 8)
"""

def fibo_sequence(nr):
    f = [1, 1]  # Initial Fibonacci sequence list, f[0] = f[1] = 1
    count = 1
    while count < nr:
        f.append(f[-1] + f[-2])
        count += 1
    return f


try:
    n = int(input("Enter a natural number: "))
    if n < 0:
        raise ValueError

    fibo = fibo_sequence(n)
    index = n
    m = fibo[index]  # The smallest Fibonacci number greater than n
    while fibo[index - 1] > n:
        index -= 1
        m = fibo[index]
    if m > n:
        print("The smallest number m from the Fibonacci sequence larger than the given natural number n is", m)
    else:
        print("there is no number m, larger than the given natural")

except ValueError:
    print("The input value must be a natural number.")
