#include <chrono>
#include <fstream>
#include <iostream>
#include <string>
#include <thread>
#include <vector>

using namespace std;

// Hard limits matching the largest test inputs
const int GRID_ROWS  = 10000;
const int GRID_COLS  = 10000;
const int FILTER_DIM = 5;

// Actual dimensions read from file
int numRows, numCols, filterSize;

// Stack-allocated storage (used when useHeap is false)
int       staticSrc   [GRID_ROWS][GRID_COLS];
int       staticFilter[FILTER_DIM][FILTER_DIM];
long long staticDst   [GRID_ROWS][GRID_COLS];

// Heap-allocated storage (used when useHeap is true)
int*       heapSrc    = nullptr;
int*       heapFilter = nullptr;
long long* heapDst    = nullptr;

bool useHeap = false;


// ======================================================
// I/O: read source matrix and convolution filter
// ======================================================

bool loadData() {
    ifstream srcFile("MatrixF.txt");
    ifstream filterFile("ConvolutionM.txt");

    if (!srcFile || !filterFile) {
        cout << "Cannot open input files.\n";
        return false;
    }

    if (!(srcFile >> numRows >> numCols) ||
        numRows <= 0 || numRows > GRID_ROWS ||
        numCols <= 0 || numCols > GRID_COLS) {
        cout << "Grid dimensions out of range.\n";
        return false;
    }

    if (!(filterFile >> filterSize) ||
        filterSize <= 0 || filterSize > FILTER_DIM || filterSize % 2 == 0) {
        cout << "Filter size must be a positive odd number within range.\n";
        return false;
    }

    if (useHeap) {
        heapSrc    = new int      [numRows * numCols];
        heapFilter = new int      [filterSize * filterSize];
        heapDst    = new long long[numRows * numCols];
    }

    for (int r = 0; r < numRows; r++) {
        for (int c = 0; c < numCols; c++) {
            int val;
            if (!(srcFile >> val)) {
                cout << "Unexpected end of source matrix.\n";
                return false;
            }
            if (useHeap) heapSrc[r * numCols + c] = val;
            else         staticSrc[r][c]           = val;
        }
    }

    for (int r = 0; r < filterSize; r++) {
        for (int c = 0; c < filterSize; c++) {
            int val;
            if (!(filterFile >> val)) {
                cout << "Unexpected end of filter data.\n";
                return false;
            }
            if (useHeap) heapFilter[r * filterSize + c] = val;
            else         staticFilter[r][c]              = val;
        }
    }

    return true;
}


// ======================================================
// I/O: write result grid to output.txt
// ======================================================

void saveData() {
    ofstream out("output.txt");

    out << numRows << " " << numCols << "\n";

    for (int r = 0; r < numRows; r++) {
        for (int c = 0; c < numCols; c++) {
            if (c > 0) out << " ";
            long long v = useHeap
                ? heapDst[r * numCols + c]
                : staticDst[r][c];
            out << v;
        }
        out << "\n";
    }
}


// ======================================================
// Accessors: hide stack/heap choice from compute code
// ======================================================

inline int srcAt(int r, int c) {
    return useHeap ? heapSrc[r * numCols + c] : staticSrc[r][c];
}

inline int filterAt(int r, int c) {
    return useHeap ? heapFilter[r * filterSize + c] : staticFilter[r][c];
}

inline void dstSet(int r, int c, long long v) {
    if (useHeap) heapDst[r * numCols + c] = v;
    else         staticDst[r][c]          = v;
}


// ======================================================
// Utility: clamp an index to stay within [0, limit)
// ======================================================

inline int clampBound(int val, int limit) {
    if (val < 0)      return 0;
    if (val >= limit) return limit - 1;
    return val;
}


// ======================================================
// Core: apply the convolution filter at one grid cell
// ======================================================

void computePixel(int row, int col) {
    long long acc  = 0;
    int       half = filterSize / 2;

    for (int dr = 0; dr < filterSize; dr++) {
        for (int dc = 0; dc < filterSize; dc++) {
            int sr = clampBound(row + dr - half, numRows);
            int sc = clampBound(col + dc - half, numCols);
            acc += (long long)srcAt(sr, sc) * filterAt(dr, dc);
        }
    }

    dstSet(row, col, acc);
}


// ======================================================
// Strategy: single thread covers the entire grid
// ======================================================

void runSerial() {
    for (int r = 0; r < numRows; r++)
        for (int c = 0; c < numCols; c++)
            computePixel(r, c);
}


// ======================================================
// Strategy: split grid into horizontal row bands
// ======================================================

void taskByRows(int rLo, int rHi) {
    for (int r = rLo; r < rHi; r++)
        for (int c = 0; c < numCols; c++)
            computePixel(r, c);
}

void distributeRows(int count) {
    vector<thread> pool;
    pool.reserve(count);

    for (int t = 0; t < count; t++) {
        int lo = t       * numRows / count;
        int hi = (t + 1) * numRows / count;
        pool.emplace_back(taskByRows, lo, hi);
    }

    for (auto& th : pool)
        th.join();
}


// ======================================================
// Strategy: split grid into vertical column bands
// ======================================================

void taskByCols(int cLo, int cHi) {
    for (int c = cLo; c < cHi; c++)
        for (int r = 0; r < numRows; r++)
            computePixel(r, c);
}

void distributeCols(int count) {
    vector<thread> pool;
    pool.reserve(count);

    for (int t = 0; t < count; t++) {
        int lo = t       * numCols / count;
        int hi = (t + 1) * numCols / count;
        pool.emplace_back(taskByCols, lo, hi);
    }

    for (auto& th : pool)
        th.join();
}


// ======================================================
// Entry point
// ======================================================

int main(int argc, char* argv[]) {

    if (argc != 4) {
        cout << "Usage: main <method> <threads> <allocation>\n";
        cout << "  method:     sequential | horizontal | vertical\n";
        cout << "  allocation: static | dynamic\n";
        return 1;
    }

    string strategy  = argv[1];
    string allocMode = argv[3];
    int    threadCount;

    try {
        threadCount = stoi(argv[2]);
    } catch (...) {
        cout << "Thread count must be an integer.\n";
        return 1;
    }

    if (threadCount < 1) {
        cout << "Thread count must be at least 1.\n";
        return 1;
    }

    if (allocMode != "static" && allocMode != "dynamic") {
        cout << "Allocation must be 'static' or 'dynamic'.\n";
        return 1;
    }

    useHeap = (allocMode == "dynamic");

    if (!loadData())
        return 1;

    auto t0 = chrono::high_resolution_clock::now();

    if      (strategy == "sequential") runSerial();
    else if (strategy == "horizontal") distributeRows(threadCount);
    else if (strategy == "vertical")   distributeCols(threadCount);
    else {
        cout << "Unknown method: " << strategy << "\n";
        return 1;
    }

    auto t1 = chrono::high_resolution_clock::now();

    double elapsed = chrono::duration<double, milli>(t1 - t0).count();

    saveData();

    cout << "Execution time: " << elapsed << " ms\n";

    // Safe even when useHeap is false — delete[] nullptr is a no-op
    delete[] heapSrc;
    delete[] heapFilter;
    delete[] heapDst;

    return 0;
}
