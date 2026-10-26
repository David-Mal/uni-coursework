#!/bin/bash
# Usage: bash run_cpp.sh <n>

if [ $# -ne 1 ]; then
    echo "Usage: $0 <n>"
    exit 1
fi

N=$1

if [ -f /tmp/vector_add ]; then
    EXE=/tmp/vector_add
elif [ -f ./vector_add ]; then
    EXE=./vector_add
else
    echo "Executable not found. Compile with:"
    echo "  g++ -O2 -std=c++17 -o /tmp/vector_add vector_add.cpp"
    exit 1
fi

echo "Running C++ vector addition (n=$N) 10 times..."
echo "----------------------------------------------"

times=""
for i in $(seq 1 10); do
    time_ms=$($EXE $N)
    echo "Run $i: ${time_ms} ms"
    times="$times $time_ms"
done

avg=$(echo $times | awk '{s=0; for(i=1;i<=NF;i++) s+=$i; printf "%.6f", s/NF}')
echo "----------------------------------------------"
echo "Average execution time: ${avg} ms"