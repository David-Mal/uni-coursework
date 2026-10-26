# Parallel 2D Convolution — Technical Reference

## Quick start

**Requirements:** g++ (C++11), Java JDK, Python 3, PowerShell 5+

```powershell
# Generate input datasets
python scripts/generate_input.py

# Compile both implementations
g++ -std=c++11 -O2 -pthread cpp/main.cpp -o build/main.exe
javac -d build/java java/Main.java

# Run the full benchmark suite
powershell scripts/benchmark.ps1

# Analyse results
python scripts/analyze_results.py
```

---

## What the program computes

For every cell `(r, c)` in the output grid, the program computes:

```
result[r][c] = sum over (dr, dc) of:
    source[ clip(r + dr - half, height) ]
           [ clip(c + dc - half, width)  ]
  * kernel[dr][dc]
```

where `half = kernelSpan / 2` and `clip(v, limit)` clamps `v` into `[0, limit)`. This replaces the standard zero-padding convention with **border replication**: pixels outside the grid take the value of the nearest edge pixel.

The kernel must have an odd side length so that `half` is a whole number and the kernel has a well-defined centre.

---

## Project layout

```
lab2/
  cpp/          main.cpp       — C++ source
  java/         Main.java      — Java source
  inputs/       10x10_k3/      — pre-generated test data
                1000x1000_k3/
                10x10000_k5/
                10000x10_k5/
                10000x10000_k5/
  build/        main.exe       — compiled C++ binary
                java/          — compiled Java classes
  results/      cpp_results.csv
                java_results.csv
  scripts/      benchmark.ps1  — full benchmark driver (Windows)
                run_process.ps1 — single configuration runner
                generate_input.py
                analyze_results.py
```

---

## Command-line interface

### C++
```
build\main.exe <method> <threads> <allocation>
```

| Parameter | Values |
|---|---|
| `method` | `sequential` · `horizontal` · `vertical` |
| `threads` | any positive integer |
| `allocation` | `static` · `dynamic` |

The program reads `MatrixF.txt` and `ConvolutionM.txt` from the **current working directory** and writes `output.txt` to the same location. It prints `Execution time: X ms` to stdout.

### Java
```
java -cp build\java Main <method> <threads>
```

Same I/O convention. No allocation flag — Java always uses heap arrays.

---

## Parallelization strategies

### Sequential
One thread iterates over every `(row, col)` pair. Used as the baseline for speedup calculations.

### Horizontal partitioning
The grid is split into `p` horizontal bands of roughly `height / p` rows each. Thread `t` processes rows `[t * height/p, (t+1) * height/p)`. Threads never share rows, so no locking is required.

```
Thread 0  ████████████  rows 0 .. h/p-1
Thread 1  ████████████  rows h/p .. 2h/p-1
...
```

Row-major memory layout means each thread's band is contiguous in memory, making cache prefetching effective. This strategy works best when `height` is large relative to `width`.

### Vertical partitioning
The grid is split into `p` vertical bands of roughly `width / p` columns each. Thread `t` processes columns `[t * width/p, (t+1) * width/p)`. Each thread still scans its columns top-to-bottom.

```
Thread 0  Thread 1  Thread 2  Thread 3
  ████      ████      ████      ████
  ████      ████      ████      ████
  ...
```

Works best when `width` is large. On tall narrow matrices the column bands contain very few elements per row pass, which hurts cache efficiency.

---

## Memory allocation modes (C++ only)

| Mode | Storage | Access |
|---|---|---|
| `static` | Global BSS arrays: `staticSrc[10000][10000]`, `staticFilter[5][5]`, `staticDst[10000][10000]` | `staticSrc[r][c]` — direct 2D index |
| `dynamic` | Heap 1D arrays: `heapSrc`, `heapFilter`, `heapDst` | `heapSrc[r * numCols + c]` — flattened row-major |

Both modes expose the same interface through three inline accessors — `srcAt(r, c)`, `filterAt(r, c)`, `dstSet(r, c, v)` — so the compute functions are identical regardless of which mode is active. The mode is selected at startup via the `useHeap` flag and never changes during a run.

The static arrays occupy ~1.2 GB of BSS (400 MB for the source, 800 MB for the output). Windows maps this lazily, so pages are only committed on first access and the program starts quickly even for small matrices. The dynamic mode allocates exactly `numRows * numCols` integers (or `long long`s), which is more efficient for small test cases.

---

## Benchmark runner

`scripts/benchmark.ps1` drives the full experiment:

1. Compiles C++ and Java.
2. For each test case, for each allocation type (C++) and each method + thread count, calls `run_process.ps1`.
3. Writes one CSV row per configuration to `results/cpp_results.csv` and `results/java_results.csv`.

`scripts/run_process.ps1` handles one configuration:

1. Copies the relevant input files into `build/<lang>/`.
2. Runs the sequential version once to produce `expected.txt`.
3. Runs the target configuration 10 times, parsing `Execution time:` from stdout each time.
4. After each run, verifies the output via MD5 hash comparison against `expected.txt`.
5. Prints the arithmetic mean of the 10 times to stdout (captured by the benchmark script).

Run a single test case instead of all five:
```powershell
powershell scripts/benchmark.ps1 1000x1000_k3
```

---

## Performance summary

The numbers below come from a previous run. Results will vary with hardware.

**What consistently helps:**
- More threads always help on `10000x10000_k5` — speedup keeps rising from 2 threads all the way to 16.
- Horizontal partitioning beats vertical in most configurations, especially on square matrices, because it accesses memory sequentially.
- C++ with dynamic allocation matches or beats static allocation, likely because its 1D array avoids the fixed 10,000-column row stride for narrow matrices.

**What does not help:**
- Any parallel method on `10x10_k3` — thread creation costs more than the computation.
- Vertical partitioning on `10000x10_k5` — each thread's column strip holds only 10 elements per row, leading to poor cache utilization.
- More than 4–8 threads in Java on medium-sized matrices — JVM thread overhead and GC pressure outweigh the parallelism benefit.

**Best single result per dataset:**

| Dataset | Language | Method | Threads | Alloc | Time (ms) | Speedup |
|---|---|---|---:|---|---:|---:|
| 10x10_k3 | C++ | sequential | 1 | dynamic | 0.0009 | 1.00× |
| 1000x1000_k3 | C++ | horizontal | 16 | dynamic | 1.5801 | 5.41× |
| 10x10000_k5 | C++ | vertical | 16 | dynamic | 0.4433 | 4.53× |
| 10000x10_k5 | C++ | horizontal | 16 | dynamic | 0.4479 | 4.63× |
| 10000x10000_k5 | C++ | horizontal | 16 | static | 284.2060 | 7.12× |
| 10000x10000_k5 | Java | horizontal | 16 | — | 329.8750 | 5.95× |

---

## Input file format

**MatrixF.txt**
```
<height> <width>
<row 0 values, space-separated>
<row 1 values, space-separated>
...
```

**ConvolutionM.txt**
```
<kernelSpan>
<row 0 of kernel, space-separated>
...
```

`kernelSpan` must be a positive odd integer and must not exceed 5 (the static array limit). Values in both files are integers.

**output.txt** (written by the program)
```
<height> <width>
<row 0 of result, space-separated long longs>
...
```

---

## Correctness guarantee

Every parallel run is verified against the sequential result by the benchmark script. A hash mismatch causes the script to abort with an error message naming the failing configuration. The convolution function `computePixel` / `processPoint` is identical whether called sequentially or from a thread — there is no shared writable state between threads because each thread owns a distinct, non-overlapping region of the output grid.
