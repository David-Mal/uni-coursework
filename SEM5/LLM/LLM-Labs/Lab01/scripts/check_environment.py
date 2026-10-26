from __future__ import annotations

import importlib.metadata
import platform
import sys


EXPECTED = {
    "httpx": "0.28.1",
    "ipykernel": "7.4.0",
    "jupyterlab": "4.6.4",
    "mcp": "2.2.0",
    "nbclient": "0.11.0",
    "pydantic": "2.13.5",
    "pytest": "9.1.1",
}


def main() -> int:
    print(f"Python: {platform.python_version()}")
    print(f"Platform: {platform.platform()}")

    if sys.version_info < (3, 10) or sys.version_info >= (3, 15):
        print("ERROR: use Python 3.10 through 3.14; Python 3.12 is recommended.")
        return 1

    ok = True
    for package, expected in EXPECTED.items():
        try:
            installed = importlib.metadata.version(package)
        except importlib.metadata.PackageNotFoundError:
            print(f"MISSING: {package}=={expected}")
            ok = False
            continue
        status = "OK" if installed == expected else "VERSION MISMATCH"
        print(f"{status}: {package} {installed} (expected {expected})")
        ok = ok and installed == expected

    if ok:
        print("Environment check passed.")
        return 0
    print('Run: python -m pip install -e ".[dev]"')
    return 1


if __name__ == "__main__":
    raise SystemExit(main())
