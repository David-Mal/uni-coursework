#include <iostream>
#include <vector>
#include <random>
#include <chrono>
#include <cstdlib>

int main(int argc, char* argv[]) {
    if (argc != 2) {
        std::cerr << "Usage: " << argv[0] << " <n>\n";
        return 1;
    }

    const size_t n = static_cast<size_t>(std::atol(argv[1]));
    if (n == 0) {
        std::cerr << "n must be a positive integer\n";
        return 1;
    }

    std::mt19937_64 rng(42);
    std::uniform_real_distribution<double> dist(-1000.0, 1000.0);

    std::vector<double> A(n), B(n), C(n);
    for (size_t i = 0; i < n; ++i) {
        A[i] = dist(rng);
        B[i] = dist(rng);
    }

    auto start = std::chrono::high_resolution_clock::now();

    for (size_t i = 0; i < n; ++i) {
        C[i] = A[i] + B[i];
    }

    auto end = std::chrono::high_resolution_clock::now();
    std::chrono::duration<double, std::milli> elapsed = end - start;

    volatile double sink = C[0] + C[n - 1];
    (void)sink;

    std::cout << elapsed.count() << std::endl;

    return 0;
}