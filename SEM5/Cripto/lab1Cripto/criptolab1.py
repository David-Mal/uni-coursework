"""
  1. gcd_bruteforce : try every candidate from min(a, b) down to 1
  2. gcd_euclid     : Euclid's algorithm (repeated remainders)
  3. gcd_binary     : Stein's binary GCD (only shifts and subtractions)
"""

import random
import time
import sys

# Allow printing / converting very large integers (Python >= 3.11 limit).
if hasattr(sys, "set_int_max_str_digits"):
    sys.set_int_max_str_digits(0)

def gcd_bruteforce(a, b):
    """Return gcd(a, b) by testing every possible divisor, largest first."""
    if a == 0:
        return b
    if b == 0:
        return a
    d = min(a, b)
    while d > 1:
        if a % d == 0 and b % d == 0:
            return d
        d -= 1
    return 1

def gcd_euclid(a, b):

    while b != 0:
        a, b = b, a % b
    return a



# Algorithm 3: Stein's binary GCD, O(log a + log b)

def gcd_binary(a, b):

    if a == 0:
        return b
    if b == 0:
        return a

    shift = ((a | b) & -(a | b)).bit_length() - 1
    a >>= (a & -a).bit_length() - 1
    while b != 0:
        b >>= (b & -b).bit_length() - 1
        if a > b:
            a, b = b, a
        b -= a
    return a << shift


def time_ns(func, a, b, repeats):
    """Average running time of func(a, b) in nanoseconds over `repeats` runs."""
    start = time.perf_counter_ns()
    for _ in range(repeats):
        result = func(a, b)
    end = time.perf_counter_ns()
    return (end - start) / repeats, result


def fmt(ns):
    """Format a time in ns using a readable unit (ns / us / ms / s)."""
    if ns is None:
        return "skipped"
    if ns < 1e3:
        return f"{ns:.0f} ns"
    if ns < 1e6:
        return f"{ns / 1e3:.2f} us"
    if ns < 1e9:
        return f"{ns / 1e6:.2f} ms"
    return f"{ns / 1e9:.2f} s"


def digits(n):
    return len(str(n))


def build_inputs():
    random.seed(42)                         # reproducible results
    inputs = [
        (48, 18),                           # tiny
        (1071, 462),
        (123456, 7890),
        (999983, 1000003),                  # two primes -> gcd = 1 (worst case)
        (2 ** 20, 2 ** 18 * 3),             # powers of two
        (10 ** 6 + 3, 10 ** 6 + 33),
        (9999991, 10000019),                # two ~10^7 primes
        (random.getrandbits(64), random.getrandbits(64)),
        (random.getrandbits(256), random.getrandbits(256)),
        (random.getrandbits(1024), random.getrandbits(1024)),
    ]
    # Big inputs with a known large common factor g
    g = random.getrandbits(500)
    inputs.append((g * random.getrandbits(500), g * random.getrandbits(500)))
    g = random.getrandbits(3000)
    inputs.append((g * random.getrandbits(3000), g * random.getrandbits(3000)))
    return inputs


BRUTE_LIMIT = 2 * 10 ** 7   # brute force is only run if min(a, b) <= this


def main():
    inputs = build_inputs()
    header = (f"{'#':>2} {'digits(a)':>9} {'digits(b)':>9} | "
              f"{'Brute force':>12} {'Euclid':>10} {'Binary':>10} | "
              f"{'gcd (shown truncated)':<22} ok")
    print(header)
    print("-" * len(header))

    for i, (a, b) in enumerate(inputs, 1):
        # Fewer repetitions for slow cases, more for fast ones
        reps_fast = 2000 if max(a, b) < 10 ** 300 else 200

        t_euc, g_euc = time_ns(gcd_euclid, a, b, reps_fast)
        t_bin, g_bin = time_ns(gcd_binary, a, b, reps_fast)

        if min(a, b) <= BRUTE_LIMIT:
            reps_slow = 3 if min(a, b) > 10 ** 5 else 50
            t_brt, g_brt = time_ns(gcd_bruteforce, a, b, reps_slow)
        else:
            t_brt, g_brt = None, g_euc      # infeasible: would run for ages

        # Correctness check: all algorithms must agree
        ok = (g_euc == g_bin == g_brt)

        g_txt = str(g_euc)
        if len(g_txt) > 18:
            g_txt = g_txt[:15] + "..."
        print(f"{i:>2} {digits(a):>9} {digits(b):>9} | "
              f"{fmt(t_brt):>12} {fmt(t_euc):>10} {fmt(t_bin):>10} | "
              f"{g_txt:<22} {'Y' if ok else 'N'}")


if __name__ == "__main__":
    main()