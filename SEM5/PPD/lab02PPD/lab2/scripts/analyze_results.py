import csv
from pathlib import Path


project_dir = Path(__file__).resolve().parent.parent
results_dir = project_dir / "results"


def read_csv(filename):
    with open(results_dir / filename, newline="") as file:
        return list(csv.DictReader(file))


def print_top(title, rows, count=5):
    print(f"\n{title}")
    print("-" * 65)

    for i, row in enumerate(rows[:count], 1):
        language = row["Language"]
        matrix = row["Matrix size"]
        method = row["Method"]
        threads = row["No threads"]
        allocation = row.get("Allocation Type", "-")
        time = float(row["Execution Time"])
        speedup = float(row["Speedup"])

        print(
            f"{i}. {language:5} | {matrix:16} | "
            f"{allocation:7} | {method:12} | "
            f"p={threads:2} | {time:.4f} ms | "
            f"Speedup={speedup:.2f}x"
        )


def main():
    cpp = read_csv("cpp_results.csv")
    java = read_csv("java_results.csv")

    for row in cpp:
        row["Language"] = "C++"

    for row in java:
        row["Language"] = "Java"
        row["Allocation Type"] = "-"

    results = cpp + java

    # Analyze each matrix separately
    matrices = sorted(set(row["Matrix size"] for row in results))

    for matrix in matrices:
        matrix_results = [
            row for row in results
            if row["Matrix size"] == matrix
        ]

        fastest = sorted(
            matrix_results,
            key=lambda row: float(row["Execution Time"])
        )

        best_speedup = sorted(
            matrix_results,
            key=lambda row: float(row["Speedup"]),
            reverse=True
        )

        print(f"\n{'=' * 65}")
        print(f"MATRIX: {matrix}")
        print(f"{'=' * 65}")

        print_top("FASTEST IMPLEMENTATIONS", fastest)
        print_top("BEST SPEEDUPS", best_speedup)

    # Best parallel method for each language
    for language in ["C++", "Java"]:
        parallel = [
            row for row in results
            if row["Language"] == language
            and row["Method"] != "sequential"
        ]

        best = sorted(
            parallel,
            key=lambda row: float(row["Speedup"]),
            reverse=True
        )

        print_top(f"TOP SPEEDUPS - {language}", best, 10)


if __name__ == "__main__":
    main()