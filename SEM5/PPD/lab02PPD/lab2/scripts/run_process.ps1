param(
    [string] $lang,
    [string] $strat,
    [int]    $thr,
    [string] $alloc,
    [string] $testCase
)

$ErrorActionPreference = "Stop"

$runs       = 10
$scriptsDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectDir = Split-Path -Parent $scriptsDir
$inputDir   = "$projectDir\inputs\$testCase"
$workDir    = "$projectDir\build\$lang"

New-Item -ItemType Directory -Force -Path $workDir | Out-Null

# Stage input files into the working directory
Copy-Item "$inputDir\MatrixF.txt"      "$workDir\" -Force
Copy-Item "$inputDir\ConvolutionM.txt" "$workDir\" -Force

# Build the executable path and argument lists for the chosen language
if ($lang -eq "cpp") {
    $exe     = "$projectDir\build\main.exe"
    $runArgs = @($strat, $thr, $alloc)
    $seqArgs = @("sequential", 1, $alloc)
} elseif ($lang -eq "java") {
    $exe     = "java"
    $runArgs = @("-cp", "$projectDir\build\java", "Main", $strat,       $thr)
    $seqArgs = @("-cp", "$projectDir\build\java", "Main", "sequential", 1)
} else {
    Write-Error "Unsupported language: $lang"
    exit 1
}

# Run the sequential version once to produce the reference output
Push-Location $workDir
try   { & $exe @seqArgs | Out-Null }
finally { Pop-Location }

Move-Item "$workDir\output.txt" "$workDir\expected.txt" -Force

$total = 0.0

for ($i = 1; $i -le $runs; $i++) {

    # Run the target configuration from the working directory
    $output = $null
    Push-Location $workDir
    try   { $output = & $exe @runArgs }
    finally { Pop-Location }

    # Parse "Execution time: X ms" from program output
    $timeLine = $output | Where-Object { $_ -match "Execution time:" }
    if ($timeLine -match "Execution time:\s+([\d.]+)") {
        $elapsed = [double]$Matches[1]
    } else {
        Write-Error "Could not parse execution time for $lang $strat $thr"
        exit 1
    }

    # Verify result matches the sequential reference via hash comparison
    $refHash = (Get-FileHash "$workDir\expected.txt" -Algorithm MD5).Hash
    $outHash = (Get-FileHash "$workDir\output.txt"   -Algorithm MD5).Hash
    if ($refHash -ne $outHash) {
        Write-Error "Result mismatch: $lang $strat $thr $alloc"
        exit 1
    }

    $total += $elapsed
    Write-Host "  Run $i`: $elapsed ms"
}

$avg = [math]::Round($total / $runs, 6)
Write-Output $avg
