$ErrorActionPreference = "Stop"

# Resolve paths relative to this script's location
$scriptsDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectDir = Split-Path -Parent $scriptsDir
$resultsDir = "$projectDir\results"

New-Item -ItemType Directory -Force -Path $resultsDir              | Out-Null
New-Item -ItemType Directory -Force -Path "$projectDir\build\java" | Out-Null

$cppCsv  = "$resultsDir\cpp_results.csv"
$javaCsv = "$resultsDir\java_results.csv"

# Write CSV headers
"Matrix size,Allocation Type,Method,No threads,Execution Time,Speedup" | Set-Content $cppCsv
"Matrix size,Method,No threads,Execution Time,Speedup"                  | Set-Content $javaCsv

# Compile C++
Write-Host "Compiling C++..."
g++ -std=c++11 -O2 -pthread "$projectDir\cpp\main.cpp" -o "$projectDir\build\main.exe"

# Compile Java
Write-Host "Compiling Java..."
javac -d "$projectDir\build\java" "$projectDir\java\Main.java"

# Test configurations (matrix size + kernel size)
$testCases = @(
    "10x10_k3"
    "1000x1000_k3"
    "10x10000_k5"
    "10000x10_k5"
    "10000x10000_k5"
)

# Parallelization strategies to benchmark
$strategies = @("horizontal", "vertical")

# Allow running a single test case via command-line argument
if ($args.Count -gt 0) {
    $testCases = @($args[0])
}

function Invoke-SingleRun {
    param(
        [string] $lang,
        [string] $strat,
        [int]    $thr,
        [string] $alloc,
        [string] $test
    )
    & "$scriptsDir\run_process.ps1" $lang $strat $thr $alloc $test
}

function Get-Speedup {
    param($seqTime, $parTime)
    $s = [double]$seqTime
    $p = [double]$parTime
    if ($p -gt 0) { return ("{0:F4}" -f ($s / $p)) }
    return "0.0000"
}

foreach ($testCase in $testCases) {

    Write-Host ""
    Write-Host "===================================="
    Write-Host "Testing $testCase"
    Write-Host "===================================="

    # Small matrix only needs one thread-count sample
    $threadCounts = if ($testCase -eq "10x10_k3") { @(4) } else { @(2, 4, 8, 16) }

    # --- C++ ---
    foreach ($alloc in @("static", "dynamic")) {

        Write-Host ""
        Write-Host "C++ - $alloc"
        Write-Host "Running sequential..."

        $baseTime = Invoke-SingleRun "cpp" "sequential" 1 $alloc $testCase
        "$testCase,$alloc,sequential,1,$baseTime,1.0000" | Add-Content $cppCsv

        foreach ($strat in $strategies) {
            foreach ($thr in $threadCounts) {
                Write-Host "Running $strat with $thr threads..."
                $parTime = Invoke-SingleRun "cpp" $strat $thr $alloc $testCase
                $spd     = Get-Speedup $baseTime $parTime
                "$testCase,$alloc,$strat,$thr,$parTime,$spd" | Add-Content $cppCsv
            }
        }
    }

    # --- Java ---
    Write-Host ""
    Write-Host "Java"
    Write-Host "Running sequential..."

    $baseTime = Invoke-SingleRun "java" "sequential" 1 "none" $testCase
    "$testCase,sequential,1,$baseTime,1.0000" | Add-Content $javaCsv

    foreach ($strat in $strategies) {
        foreach ($thr in $threadCounts) {
            Write-Host "Running $strat with $thr threads..."
            $parTime = Invoke-SingleRun "java" $strat $thr "none" $testCase
            $spd     = Get-Speedup $baseTime $parTime
            "$testCase,$strat,$thr,$parTime,$spd" | Add-Content $javaCsv
        }
    }
}

Write-Host ""
Write-Host "===================================="
Write-Host "Benchmark completed!"
Write-Host "===================================="
Write-Host "C++ results: $cppCsv"
Write-Host "Java results: $javaCsv"
