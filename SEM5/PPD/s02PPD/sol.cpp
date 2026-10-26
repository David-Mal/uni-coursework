#include <iostream>
#include <vector>
#include <thread>
#include <mutex>
#include <chrono>
#include <random>
#include <iomanip>

using namespace std;

double global_sum = 0.0;
mutex mtx;

void linear_task(int id, int p, const vector<double>& A, const vector<double>& B) {
    int n = A.size();
    int chunk = n / p;
    int rem = n % p;
    int start = id * chunk + min(id, rem);
    int end = start + chunk + (id < rem ? 1 : 0);
    
    double local_sum = 0.0;
    for (int i = start; i < end; ++i) {
        local_sum += A[i] * B[i];
    }
    
    lock_guard<mutex> lock(mtx);
    global_sum += local_sum;
}

void cyclic_task(int id, int p, const vector<double>& A, const vector<double>& B) {
    int n = A.size();
    double local_sum = 0.0;
    for (int i = id; i < n; i += p) {
        local_sum += A[i] * B[i];
    }
    
    lock_guard<mutex> lock(mtx);
    global_sum += local_sum;
}

void initialize(vector<double>& A, vector<double>& B, int n) {
    random_device rd;
    mt19937 gen(rd());
    uniform_real_distribution<> dis(1.0, 100.0);
    for (int i = 0; i < n; ++i) {
        A[i] = dis(gen);
        B[i] = dis(gen);
    }
}

int main() {
    int n = 10000000; 
    int p = 8;

    vector<double> A(n);
    vector<double> B(n);
    initialize(A, B, n);

    auto start_seq = chrono::high_resolution_clock::now();
    double seq_sum = 0.0;
    for(int i = 0; i < n; ++i) {
        seq_sum += A[i] * B[i];
    }
    auto end_seq = chrono::high_resolution_clock::now();
    chrono::duration<double> diff_seq = end_seq - start_seq;

    global_sum = 0.0;
    auto start_lin = chrono::high_resolution_clock::now();
    vector<thread> threads_lin;
    for(int i = 0; i < p; ++i) {
        threads_lin.emplace_back(linear_task, i, p, cref(A), cref(B));
    }
    for(auto& t : threads_lin) {
        t.join();
    }
    auto end_lin = chrono::high_resolution_clock::now();
    chrono::duration<double> diff_lin = end_lin - start_lin;
    double lin_sum = global_sum;

    global_sum = 0.0;
    auto start_cyc = chrono::high_resolution_clock::now();
    vector<thread> threads_cyc;
    for(int i = 0; i < p; ++i) {
        threads_cyc.emplace_back(cyclic_task, i, p, cref(A), cref(B));
    }
    for(auto& t : threads_cyc) {
        t.join();
    }
    auto end_cyc = chrono::high_resolution_clock::now();
    chrono::duration<double> diff_cyc = end_cyc - start_cyc;
    double cyc_sum = global_sum;

    cout << fixed << setprecision(2);
    cout << "Sequential Sum: " << seq_sum << " | Time: " << diff_seq.count() << " s\n";
    cout << "Linear Sum:     " << lin_sum << " | Time: " << diff_lin.count() << " s\n";
    cout << "Cyclic Sum:     " << cyc_sum << " | Time: " << diff_cyc.count() << " s\n";

    return 0;
}