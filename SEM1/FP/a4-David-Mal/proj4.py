# solve problem 6
"""
Generate all sequences
of n parentheses that close correctly. Example: for n=4 there are two solutions: (()) and ()()
"""
def display(x: list, n: int):
    for i in range(n):
        if x[i] == 0:
            print("(", end='')
        else:
            print(")", end='')
    print()



def backtrack(x: list, n: int, k: int, cnt1: int, cnt2: int):
    # If we've reached the end and have balanced brackets, display the sequence
    if k == n:
        if cnt1 == cnt2:    # the number of opened brackets is equal to the number of closed brackets
            arr = []
            display(x, n)
        return

    # Add an opening bracket if possible
    if cnt1 < n // 2:
        x[k] = 0
        backtrack(x, n, k + 1, cnt1 + 1, cnt2)

    # Add a closing bracket if it won't exceed the number of opening brackets
    if cnt2 < cnt1:
        x[k] = 1
        backtrack(x, n, k + 1, cnt1, cnt2 + 1)

def iterative_backtrack(n: int):
    stack = []
    result = []

    # Start with an empty sequence and counts set to 0
    stack.append(([], 0, 0))

    while stack:
        sequence, cnt1, cnt2 = stack.pop()

        # If we reach the target length and have balanced brackets
        if len(sequence) == n:
            if cnt1 == cnt2:
                result.append("".join('(' if x == 0 else ')' for x in sequence))
            continue

        # Add an opening bracket if possible
        if cnt1 < n // 2:
            stack.append((sequence + [0], cnt1 + 1, cnt2))

        # Add a closing bracket if it won't exceed the number of opening brackets
        if cnt2 < cnt1:
            stack.append((sequence + [1], cnt1, cnt2 + 1))

    return result



def main():

    option = int(input("1 - Iterative or 2 - recursive: "))
    if option == 2:
        x = [None] * 20
        n = int(input("Insert a natural number: "))
        if n % 2 == 0:  # Only even numbers can have balanced brackets
            backtrack(x, n, 0, 0, 0)
        else:
            print("No valid sequences for an odd number of brackets.")
    elif option == 1:
        x = [None] * 20
        n = int(input("Insert a natural number: "))
        if n % 2 == 0:
            for seq in iterative_backtrack(n):
                print(seq)
        else:
            print("No valid sequences for an even number of brackets.")
    else:
        print("Invalid option.")

def test():
    n = 2
    result = iterative_backtrack(n)
    expected_output = ["()"]
    assert result == expected_output, f"Test case for n={n} failed. Expected {expected_output}, got {result}"

    # Test case 2: n = 4 (2 pairs of brackets)
    n = 4
    result = iterative_backtrack(n)
    expected_output = ["()()", "(())"]
    assert result == expected_output, f"Test case for n={n} failed. Expected {expected_output}, got {result}"

    # Test case 3: n = 6 (3 pairs of brackets)
    n = 6
    result = iterative_backtrack(n)
    expected_output = ["()()()", "()(())", "(())()", "(()())", "((()))"]
    assert result == expected_output, f"Test case for n={n} failed. Expected {expected_output}, got {result}"


if __name__ == "__main__":
    test()
    main()
