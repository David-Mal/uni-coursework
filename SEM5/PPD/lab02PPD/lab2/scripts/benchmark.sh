#!/bin/bash

set -e

project_dir="$(cd "$(dirname "$0")/.." && pwd)"
results_dir="$project_dir/results"

mkdir -p "$results_dir"

cpp_csv="$results_dir/cpp_results.csv"
java_csv="$results_dir/java_results.csv"

# CSV headers
echo "Matrix size,Allocation Type,Method,No threads,Execution Time,Speedup" > "$cpp_csv"
echo "Matrix size,Method,No threads,Execution Time,Speedup" > "$java_csv"

# Create build directories
mkdir -p "$project_dir/build/java"

# Compile C++
echo "Compiling C++..."
g++ -std=c++11 -O2 -pthread \
    "$project_dir/cpp/main.cpp" \
    -o "$project_dir/build/main"

# Compile Java
echo "Compiling Java..."
javac -d "$project_dir/build/java" \
    "$project_dir/java/Main.java"

# Matrix configurations
tests=(
    "10x10_k3"
    "1000x1000_k3"
    "10x10000_k5"
    "10000x10_k5"
    "10000x10000_k5"
)

methods=(
    "horizontal"
    "vertical"
    "block"
    "linear"
    "cyclic"
)

# Allow running only one selected test
if [ -n "$1" ]; then
    tests=("$1")
fi

# Run a configuration and return its average time
run_test() {
    local language=$1
    local method=$2
    local threads=$3
    local allocation=$4
    local test_name=$5

    bash "$project_dir/scripts/run_process.sh" \
        "$language" "$method" "$threads" "$allocation" "$test_name"
}

# Calculate speedup
calculate_speedup() {
    local sequential_time=$1
    local parallel_time=$2

    awk -v seq="$sequential_time" -v par="$parallel_time" \
        'BEGIN {
            if (par > 0)
                printf "%.4f", seq/par
            else
                printf "0"
        }'
}

for test_name in "${tests[@]}"; do

    echo ""
    echo "===================================="
    echo "Testing $test_name"
    echo "===================================="

    # Thread counts
    if [ "$test_name" = "10x10_k3" ]; then
        thread_counts=(4)
    else
        thread_counts=(2 4 8 16)
    fi

    # -------------------------
    # C++
    # -------------------------

    for allocation in static dynamic; do

        echo ""
        echo "C++ - $allocation"

        # Sequential baseline
        echo "Running sequential..."

        seq_time=$(run_test cpp sequential 1 "$allocation" "$test_name")

        echo "$test_name,$allocation,sequential,1,$seq_time,1.0000" \
            >> "$cpp_csv"

        # Parallel methods
        for method in "${methods[@]}"; do
            for threads in "${thread_counts[@]}"; do

                echo "Running $method with $threads threads..."

                par_time=$(run_test cpp "$method" "$threads" "$allocation" "$test_name")

                speedup=$(calculate_speedup "$seq_time" "$par_time")

                echo "$test_name,$allocation,$method,$threads,$par_time,$speedup" \
                    >> "$cpp_csv"
            done
        done
    done

    # -------------------------
    # Java
    # -------------------------

    echo ""
    echo "Java"

    # Sequential baseline
    echo "Running sequential..."

    seq_time=$(run_test java sequential 1 none "$test_name")

    echo "$test_name,sequential,1,$seq_time,1.0000" \
        >> "$java_csv"

    # Parallel methods
    for method in "${methods[@]}"; do
        for threads in "${thread_counts[@]}"; do

            echo "Running $method with $threads threads..."

            par_time=$(run_test java "$method" "$threads" none "$test_name")

            speedup=$(calculate_speedup "$seq_time" "$par_time")

            echo "$test_name,$method,$threads,$par_time,$speedup" \
                >> "$java_csv"
        done
    done

done

echo ""
echo "===================================="
echo "Benchmark completed!"
echo "===================================="
echo "C++ results: $cpp_csv"
echo "Java results: $java_csv"