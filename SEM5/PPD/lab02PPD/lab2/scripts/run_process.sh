#!/bin/bash

set -e

language=$1
method=$2
threads=$3
allocation=$4
test_name=$5
runs=10

project_dir="$(cd "$(dirname "$0")/.." && pwd)"
input_dir="$project_dir/inputs/$test_name"

# Temporary working directory
work_dir="$project_dir/build/$language"
mkdir -p "$work_dir"

# Copy inputs into the temporary directory
cp "$input_dir/MatrixF.txt" "$work_dir/"
cp "$input_dir/ConvolutionM.txt" "$work_dir/"

# Prepare the command
if [ "$language" = "cpp" ]; then
    command=("$project_dir/build/main" "$method" "$threads" "$allocation")
elif [ "$language" = "java" ]; then
    command=(java -cp "$project_dir/build/java" Main "$method" "$threads")
else
    echo "Invalid language!" >&2
    exit 1
fi

# Generate the expected result
if [ "$language" = "cpp" ]; then
    (cd "$work_dir" && "$project_dir/build/main" sequential 1 "$allocation") > /dev/null
else
    (cd "$work_dir" && java -cp "$project_dir/build/java" Main sequential 1) > /dev/null
fi

mv "$work_dir/output.txt" "$work_dir/expected.txt"

total=0

for ((i=1; i<=runs; i++)); do

    result=$(cd "$work_dir" && "${command[@]}")

    time=$(echo "$result" | awk '/Execution time:/ {print $3}')

    if [ -z "$time" ]; then
        echo "Could not read execution time!" >&2
        exit 1
    fi

    # Verify correctness
    if ! cmp -s "$work_dir/expected.txt" "$work_dir/output.txt"; then
        echo "Incorrect result: $language $method $threads $allocation" >&2
        exit 1
    fi

    total=$(awk -v a="$total" -v b="$time" 'BEGIN {print a+b}')

    echo "Run $i: $time ms" >&2
done

average=$(awk -v total="$total" -v runs="$runs" \
    'BEGIN {printf "%.6f", total/runs}')

echo "$average"