# 7. Algorithm for determining all Carmichael numbers less than a given bound.

def carmichael_numbers(bound):

    spf = list(range(bound))
    for i in range(2, int(bound ** 0.5) + 1):
        if spf[i] == i:
            for j in range(i * i, bound, i):
                if spf[j] == j:
                    spf[j] = i

    result = []
    for n in range(3, bound, 2):
        if spf[n] == n:
            continue
        m = n
        ok = True
        while m > 1:
            p = spf[m]
            m //= p
            if m % p == 0:
                ok = False
                break
            if (n - 1) % (p - 1) != 0:
                ok = False
                break
        if ok:
            result.append(n)
    return result


print(carmichael_numbers(10000))
# [561, 1105, 1729, 2465, 2821, 6601, 8911]