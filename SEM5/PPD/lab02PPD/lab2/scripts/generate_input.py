import random
from pathlib import Path


# Matrix dimensions and kernel sizes
tests = [
    (10, 10, 3),
    (1000, 1000, 3),
    (10, 10000, 5),
    (10000, 10, 5),
    (10000, 10000, 5)
]

# Main project directory
project_dir = Path(__file__).resolve().parent.parent
inputs_dir = project_dir / "inputs"


def generate_matrix(folder, n, m):
    file_path = folder / "MatrixF.txt"

    with open(file_path, "w") as file:
        file.write(f"{n} {m}\n")

        for i in range(n):
            row = [str(random.randint(0, 9)) for _ in range(m)]
            file.write(" ".join(row) + "\n")


def generate_kernel(folder, k):
    file_path = folder / "ConvolutionM.txt"

    with open(file_path, "w") as file:
        file.write(f"{k}\n")

        for i in range(k):
            row = [str(random.randint(0, 9)) for _ in range(k)]
            file.write(" ".join(row) + "\n")


def main():
    random.seed(42)

    for n, m, k in tests:
        folder = inputs_dir / f"{n}x{m}_k{k}"
        folder.mkdir(parents=True, exist_ok=True)

        print(f"Generating {n}x{m}...")

        generate_matrix(folder, n, m)
        generate_kernel(folder, k)

        print(f"Saved in inputs/{n}x{m}_k{k}/")

    print("All input files generated!")


if __name__ == "__main__":
    main()