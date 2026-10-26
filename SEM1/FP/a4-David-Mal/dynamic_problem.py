"""
Problem 6
Given an array of integers A, maximize the value of the expression
A[m] - A[n] + A[p] - A[q], where m, n, p, q are array indices with m > n > p > q.
For A = [30, 5, 15, 18, 30, 40], the maximum value is 32, obtained as 40 - 18 + 15 - 5.
Display both the maximum value as well as the expression used to calculate it.

We will use Dynamic Programming to solve this problem.
For this we create four 1-Dimensional DP tables.
Let us say there are four DP tables as – table1[], table2[], table3[], table4[]
Then to find the maximum value of A[m] – A[n] + A[p] – A[q], such that q < p < n < m

table1[] should store the maximum value of A[m]
table2[] should store the maximum value of A[m] – A[n]
table3[] should store the maximum value of A[m] – A[n] + A[p]
table4[] should store the maximum value of A[m] - A[n] + A[p] - A[q]
Then the maximum value would be present in index 0 of table4 which will be our required answer.
"""
import random
#O(dim)
def dp(arr):
    dim = len(arr)
    if dim < 4:
        return None, "Array does not contain enough elements."

    # Initialize tables for maximum values and corresponding indices
    table1 = [float('-inf')] * dim  # For A[m]
    table2 = [float('-inf')] * dim  # For A[m] - A[n]
    table3 = [float('-inf')] * dim  # For A[m] - A[n] + A[p]
    table4 = [float('-inf')] * dim  # For A[m] - A[n] + A[p] - A[q]

    # Initialize lists to store the indices for m, n, p, and q
    m_indices = [-1] * dim
    n_indices = [-1] * dim
    p_indices = [-1] * dim
    q_indices = [-1] * dim

    # Step 1: Populate table1 with the maximum values for A[m] and track m's index
    table1[dim - 1] = arr[dim - 1]
    m_indices[dim - 1] = dim - 1
    for i in range(dim - 2, -1, -1):
        if arr[i] >= table1[i + 1]:
            table1[i] = arr[i]
            m_indices[i] = i
        else:
            table1[i] = table1[i + 1]
            m_indices[i] = m_indices[i + 1]

    # Step 2: Populate table2 with the maximum values for A[m] - A[n] and track n's index
    table2[dim - 2] = table1[dim - 1] - arr[dim - 2]
    m_indices_table2 = [m_indices[dim - 1]] * dim
    n_indices[dim - 2] = dim - 2
    for i in range(dim - 3, -1, -1):
        current_value = table1[i + 1] - arr[i]
        if current_value >= table2[i + 1]:
            table2[i] = current_value
            m_indices_table2[i] = m_indices[i + 1]
            n_indices[i] = i
        else:
            table2[i] = table2[i + 1]
            m_indices_table2[i] = m_indices_table2[i + 1]
            n_indices[i] = n_indices[i + 1]

    # Step 3: Populate table3 with the maximum values for A[m] - A[n] + A[p] and track p's index
    table3[dim - 3] = table2[dim - 2] + arr[dim - 3]
    m_indices_table3 = [m_indices_table2[dim - 2]] * dim
    n_indices_table3 = [n_indices[dim - 2]] * dim
    p_indices[dim - 3] = dim - 3
    for i in range(dim - 4, -1, -1):
        current_value = table2[i + 1] + arr[i]
        if current_value >= table3[i + 1]:
            table3[i] = current_value
            m_indices_table3[i] = m_indices_table2[i + 1]
            n_indices_table3[i] = n_indices[i + 1]
            p_indices[i] = i
        else:
            table3[i] = table3[i + 1]
            m_indices_table3[i] = m_indices_table3[i + 1]
            n_indices_table3[i] = n_indices_table3[i + 1]
            p_indices[i] = p_indices[i + 1]

    # Step 4: Populate table4 with the maximum values for A[m] - A[n] + A[p] - A[q] and track q's index
    table4[dim - 4] = table3[dim - 3] - arr[dim - 4]
    m_indices_table4 = [m_indices_table3[dim - 3]] * dim
    n_indices_table4 = [n_indices_table3[dim - 3]] * dim
    p_indices_table4 = [p_indices[dim - 3]] * dim
    q_indices[dim - 4] = dim - 4
    for i in range(dim - 5, -1, -1):
        current_value = table3[i + 1] - arr[i]
        if current_value >= table4[i + 1]:
            table4[i] = current_value
            m_indices_table4[i] = m_indices_table3[i + 1]
            n_indices_table4[i] = n_indices_table3[i + 1]
            p_indices_table4[i] = p_indices[i + 1]
            q_indices[i] = i
        else:
            table4[i] = table4[i + 1]
            m_indices_table4[i] = m_indices_table4[i + 1]
            n_indices_table4[i] = n_indices_table4[i + 1]
            p_indices_table4[i] = p_indices_table4[i + 1]
            q_indices[i] = q_indices[i + 1]

    # Retrieve the indices from the tables
    m = m_indices_table4[0]
    n = n_indices_table4[0]
    p = p_indices_table4[0]
    q = q_indices[0]

    # Verify that indices satisfy m > n > p > q
    if not (m > n > p > q):
        return None, "Could not find indices satisfying m > n > p > q."

    # Construct the expression
    indices = f"A[{m}] - A[{n}] + A[{p}] - A[{q}]"
    max_value = table4[0]
    computed_value = arr[m] - arr[n] + arr[p] - arr[q]
    expression = f"{arr[m]} - {arr[n]} + {arr[p]} - {arr[q]}"

    return max_value, expression, computed_value, indices

def naive(arr):
    # Initialize variables to keep track of the maximum value and the best expression
    max_value = float('-inf')
    best_expression = None

    # Naive solution: nested for loops to check all valid index combinations
    dim = len(arr)
    for m in range(len(arr) - 1, 2, -1):
        for n in range(m - 1, 1, -1):
            for p in range(n - 1, 0, -1):
                for q in range(p - 1, -1, -1):
                    # Calculate the expression for the current indices
                    current_value = arr[m] - arr[n] + arr[p] - arr[q]
                    # Update max value and expression if the current value is greater
                    if current_value > max_value:
                        max_value = current_value
                        best_expression = (m, n, p, q)

    m, n, p, q = best_expression
    expression = f"{arr[m]} - {arr[n]} + {arr[p]} - {arr[q]}"
    indices = f"A[{m}] - A[{n}] + A[{p}] - A[{q}]"
    computed_value = arr[m] - arr[n] + arr[p] - arr[q]
    return max_value, expression, computed_value, indices




def main():
    n = random.randint(4, 12)
    A = random.sample(range(1, 100), n)

    #option = int(input("1 - for the dynamic solution\n2 - for the naive solution\n"))

    #if option == 1:
    print(f"Array size: {n}")
    print("Array:", A)
    max_value, expression, computed_value, indices = dp(A)
    print("The maximum value of A[m] - A[n] + A[p] - A[q] is:", max_value)
    print("Indices used:", indices, " = ", computed_value)
    print("Expression used:", expression, " = ", computed_value)

   # elif option == 2:
    print(f"Array size: {n}")
    print("Array:", A)
    max_value, expression, computed_value, indices = naive(A)
    print("The maximum value of A[m] - A[n] + A[p] - A[q] is:", max_value)
    print("Indices used:", indices, " = ", computed_value)
    print("Expression used:", expression, " = ", computed_value)


def test():
    A = [30, 5, 15, 18, 30, 40]
    max_value, expression, computed_value, indices = dp(A)
    assert max_value == 32, "Test failed for A = [30, 5, 15, 18, 30, 40]"

    A = [4, 8, 9, 2, 20]
    max_value, expression, computed_value, indices = dp(A)
    assert max_value == 23, "Test failed for A = [4, 8, 9, 2, 20]"

    A = [30, 5, 15, 18, 30, 40]
    max_value, expression, computed_value, indices = naive(A)
    assert max_value == 32, "Test failed for A = [30, 5, 15, 18, 30, 40]"

    A = [4, 8, 9, 2, 20]
    max_value, expression, computed_value, indices = naive(A)
    assert max_value == 23, "Test failed for A = [4, 8, 9, 2, 20]"

if __name__ == '__main__':
    test()
    main()
