#!/bin/bash
# Usage: bash run_java.sh <n>

if [ $# -ne 1 ]; then
    echo "Usage: $0 <n>"
    exit 1
fi

N=$1
CLASS=VectorAdd

if [ ! -f "${CLASS}.class" ]; then
    echo "Class file ${CLASS}.class not found. Compile first with: javac VectorAdd.java"
    exit 1
fi

echo "Running Java vector addition (n=$N) 10 times..."
echo "-----------------------------------------------"

times=""
for i in $(seq 1 10); do
    time_ms=$(java $CLASS $N)
    echo "Run $i: ${time_ms} ms"
    times="$times $time_ms"
done

avg=$(echo $times | awk '{s=0; for(i=1;i<=NF;i++) s+=$i; printf "%.6f", s/NF}')
echo "-----------------------------------------------"
echo "Average execution time: ${avg} ms"