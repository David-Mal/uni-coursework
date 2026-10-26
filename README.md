# 🎓 Computer Science — Babeș-Bolyai University

**David Măluțan** · Bachelor of Science in Computer Science
Faculty of Mathematics and Computer Science · [Babeș-Bolyai University](https://www.ubbcluj.ro/) · Cluj-Napoca, Romania

![Semesters](https://img.shields.io/badge/semesters-1%E2%80%936-blue)
![Status](https://img.shields.io/badge/status-semester%205%20in%20progress-orange)
![Languages](https://img.shields.io/badge/languages-Python%20%7C%20C%2B%2B%20%7C%20Java%20%7C%20C%23%20%7C%20TypeScript%20%7C%20PHP-informational)

---

This repository collects the coursework, laboratory assignments, practical exams and study materials I produced during my undergraduate studies in Computer Science at Babeș-Bolyai University (UBB). It is organized chronologically by semester, with one folder per course.

The work spans the full breadth of the curriculum: low-level programming in x86 assembly, algorithms and data structures, object-oriented design in C++ and Java, systems programming in C and shell, networking, functional and logic programming, artificial intelligence, web and database development, parallel computing, formal languages and compilers, cryptography and applied large language models.

##  Table of Contents

- [Repository Structure](#-repository-structure)
- [Semester 1](#semester-1)
- [Semester 2](#semester-2)
- [Semester 3](#semester-3)
- [Semester 4](#semester-4)
- [Semester 5](#semester-5)
- [Semester 6](#semester-6)
- [Highlighted Projects](#-highlighted-projects)
- [Technology Overview](#-technology-overview)
- [Running the Projects](#-running-the-projects)
- [Academic Integrity](#-academic-integrity)

---

##  Repository Structure

```
UNI/
├── SEM1/
│   ├── ASC/              Computer Systems Architecture (x86 Assembly)
│   └── FP/               Fundamentals of Programming (Python)
├── SEM2/
│   ├── dsa/              Data Structures and Algorithms (C++)
│   ├── oop/              Object-Oriented Programming (C++ / Qt)
│   ├── os/               Operating Systems (C, Shell, UNIX)
│   ├── dynSys/           Dynamical Systems (Maple)
│   └── Geometry/         Geometry
├── SEM3/
│   ├── MAP/              Advanced Programming Methods (Java / JavaFX)
│   ├── networks/         Computer Networks (C, Python, Cisco Packet Tracer)
│   ├── PLF/              Functional and Logic Programming (Lisp, Prolog)
│   └── Prob&Statistics/  Probability and Statistics (MATLAB)
├── SEM4/
│   ├── AI_Labs/          Artificial Intelligence (Python / Jupyter)
│   ├── dbsm/             Database Management Systems (C# / SQL Server)
│   ├── MPP/              Design and Programming Environments — team project
│   └── Web/              Web Programming (HTML/CSS/JS, PHP, Angular, Java, ASP.NET, Node.js)
├── SEM5/
│   ├── Cripto/           Cryptography (Python)
│   ├── LFTC/             Formal Languages and Compiler Design (Flex, C)
│   ├── LLM/              Large Language Models (Python)
│   ├── PPD/              Parallel and Distributed Programming (C++, Java)
│   ├── MOBILE/           Mobile Application Development
│   └── PROJ. COLECTIV/   Collective (Team) Project
└── SEM6/
```

---

## Semester 1

###  Computer Systems Architecture — `SEM1/ASC`

Introduction to computer organization and low-level programming using **x86 assembly (NASM)** on Windows.

| Folder | Contents |
|---|---|
| `ASC_Lectures/`, `ASC_theory/` | Lecture slides and theory notes (number representation, registers, addressing modes, memory, flags) |
| `lab_asc/` | Laboratory programs and the assembler toolchain (NASM, OllyDbg, Notepad++) |
| `lab_HW/` | Homework solutions — arithmetic expressions, bitwise operations, strings, loops and file I/O (`lab4HW` – `LAB8_HW`) |
| `asc_work/` | Additional practice programs |

**Key topics:** signed/unsigned arithmetic, byte/word/doubleword conversions, bit manipulation, string instructions, multi-module programs, calling C library functions (`printf`, `scanf`, `fopen`) from assembly.

###  Fundamentals of Programming — `SEM1/FP`

A progressive series of **Python** assignments delivered through GitHub Classroom, moving from procedural code to a fully layered, tested object-oriented application.

| Assignment | Topic |
|---|---|
| `a1` | Basic problem solving with functions; separating I/O from computation |
| `a2` | Searching and sorting algorithms with step-by-step visualization |
| `a3` | Algorithm complexity — best / average / worst case timing benchmarks |
| `a4` | Backtracking (iterative and recursive) and dynamic programming (naive vs. memoized) |
| `a5` | Complex numbers with two interchangeable representations (list and dictionary) |
| `a6` | Modular programming, command-based UI, exceptions, `texttable` output |
| `a7` | Classes and objects — memory, text-file and binary (Pickle) repositories |
| `a8` | **Layered architecture I** — book rental library (books, clients, rentals), custom exceptions, `settings.properties` configuration, PyUnit tests |
| `a9` | **Layered architecture II** — unlimited cascading undo/redo with the *Command* pattern, statistics |
| `a10` | **Connect Four** — human vs. computer game with a strategic AI opponent |
| `t1` – `t3` | Laboratory tests |
| `retake` | Practical exam retake |
| `exam samples/` | Practice exam problems |

---

## Semester 2

###  Data Structures and Algorithms — `SEM2/dsa`

Implementations of abstract data types in **C++** (CMake), each validated against provided short and extended test suites.

| Folder | Description |
|---|---|
| `DSA1` – `DSA5` | **Sorted Set** ADT implemented over different underlying representations (e.g. singly linked list on arrays with a `next` array and free-space management), with iterators and a generic ordering relation |
| `DSA_2025&complexities/` | Complexity analysis exercises |
| `DSA_EXAM/` | Exam preparation |
| `Flaschards/` | Anki flashcard decks (`.apkg`) for revision |

###  Object-Oriented Programming — `SEM2/oop`

**C++** applications following a layered architecture (Domain → Repository → Service → UI), culminating in **Qt** GUI applications.

| Project | Description |
|---|---|
| `project1/` | Song playlist manager — repository, service, custom exceptions, utilities |
| `EventPlanner/` | Qt application using the **Observer** pattern (`Subject` / `Observer`) with multiple synchronized windows (per-person view, popular events view) and file persistence |
| `ExamExample/`, `Example725/`, `Practic/`, `PracticExample/` | Practical exam preparation |
| `test1practice2/`, `test2_practice*/` | Laboratory test practice |
| `oopTheory.pdf`, `theo_*.jpg` | Theory exam materials |

###  Operating Systems — `SEM2/os`

UNIX systems programming with **C**, **Bash** and standard command-line tools.

- Shell scripting (`a.sh`, `b.sh`, `c.sh`, `arguments.sh`) — argument processing, file system traversal
- Process management with `fork`, `wait` and process trees (`kids.c`, `pb3.c`)
- File I/O in C — converting matrices between text and binary formats
- Threads and synchronization exercises (`osThreadsEx.txt`)
- Regular expressions with `grep`, `sed`, `awk` (`regex.txt`)

###  Dynamical Systems — `SEM2/dynSys`

**Maple** worksheets covering ordinary differential equations, phase portraits and numerical methods (Euler and improved Euler), together with lab reports (`redo_lab1` – `redo_lab6`).

###  Geometry — `SEM2/Geometry`

Seminar and exam exercise material.

---

## Semester 3

###  Advanced Programming Methods — `SEM3/MAP`

The central project is a **Toy Language Interpreter** written in **Java** (Maven), developed incrementally throughout the semester.

- **Language features:** variable declarations, arithmetic / logical / relational expressions, `if`, `while`, `repeat … as`, compound statements, `print`
- **Types and values:** `int`, `bool`, `string`, and reference types
- **Heap management:** `new`, `rH` (read heap), `wH` (write heap) with a garbage collector
- **File operations:** `openRFile`, `readFile`, `closeRFile`
- **Concurrency:** `fork` statement with per-thread execution stacks and symbol tables, executed via a thread pool
- **Static type checking** before execution
- **Custom ADTs:** `MyStack`, `MyDict`, `MyList`, `MyHeap`
- **Interfaces:** text menu (Command pattern) and a **JavaFX** GUI (`MainWindow.fxml`, `ProgramList.fxml`) showing the execution stack, symbol table, heap, output and file table at each step
- Execution logs written to `log*.txt`

Also includes `JavaMVCProject/` (vehicle inventory in MVC style) and theory materials.

###  Computer Networks — `SEM3/networks`

- **Socket programming** in C and Python — TCP and UDP client/server applications: number guessing, hangman, trivia, string reversal, merge sort over the network, file transfer, UDP broadcast, `select()`-based multiplexing (`networks test/`)
- **Cisco Packet Tracer** topologies (`exam-2.pkt`) — subnetting, routing, VLANs
- **`cn_exam-main/`** — a quiz web application for exam preparation built with **React + TypeScript + Vite + Tailwind CSS**, backed by a JSON question bank

###  Functional and Logic Programming — `SEM3/PLF`

- **Common Lisp** (`lisp/`) — recursive list processing, tree representations, map functions
- **Prolog** (`prolog/`) — list operations, GCD, insertion, backtracking, collecting all solutions with `findall`

###  Probability and Statistics — `SEM3/Prob&Statistics`

**MATLAB** labs — discrete and continuous distributions, simulations, descriptive statistics, confidence intervals and hypothesis testing.

---

## Semester 4

###  Artificial Intelligence — `SEM4/AI_Labs`

**Jupyter notebooks** in Python (NumPy, pandas, scikit-learn, matplotlib):

| Lab | Topic |
|---|---|
| 1 – 2 | Data exploration and regression (salary and Telco customer-churn datasets) |
| 3 | Classification on the Iris dataset |
| 4 – 5 | Search algorithms and optimization |
| 6 – 7 | Neural networks and model training |
| 9 | **Evolutionary multi-objective optimization** — aircraft design problem |
| 10 | **Fuzzy logic** — Mamdani rule-based inference in uncertain environments |

### 🗄 Database Management Systems — `SEM4/dbsm`

**C# WinForms** application on **SQL Server** implementing a generic **master–detail** form. Parent/child tables, labels and all SQL commands (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) are loaded from `scenarios.xml`, so new scenarios (e.g. *Authors → Books*) can be added without recompiling.

###  Web Programming — `SEM4/Web`

The same domain is reimplemented across successive technology stacks:

| Lab | Stack | Description |
|---|---|---|
| `lab1`, `lab2 web` | HTML, CSS | Static pages and responsive layout |
| `lab3` | JavaScript | DOM manipulation |
| `lab4_JQ` | jQuery | Dynamic UI and effects |
| `lab5-php` | PHP, MySQL, Docker | News portal with authentication, admin panel and AJAX article loading |
| `lab6-news-angular-app` | Angular | News SPA |
| `lab7-xo-game2` | Java Servlets / JSP, Tomcat | Tic-tac-toe web game |
| `lab8-aspnet` | ASP.NET Core + Angular | News application with a .NET REST backend |
| `lab9-nodejs` | Node.js + Angular, Docker | News application with a Node backend and SQL initialization script |
| `web-exam/` | PHP, MySQL | Practical exam solutions — session authentication, CRUD, multi-step workflows |

###  Design and Programming Environments — `SEM4/MPP`

**Estethis** — a team e-commerce project for a clothing store. The specification (`projectX.txt`) covers guest / customer / administrator roles, authentication with email verification and password reset, catalog search and filtering, cart and checkout with online payment integration, order history, wishlist, and a state-machine-driven discount engine. The folder also contains the project presentation.

---

## Semester 5

>  *In progress.*

###  Cryptography — `SEM5/Cripto`

**Python** implementations of number-theoretic algorithms used in cryptography:

- **Lab 1** — GCD algorithms compared on very large integers: brute force, Euclid, and Stein's binary GCD, with timing benchmarks
- **Lab 2** — Generating all **Carmichael numbers** below a bound, using a smallest-prime-factor sieve and Korselt's criterion

###  Formal Languages and Compiler Design — `SEM5/LFTC`

Design and implementation of **BarcaScript**, a domain-specific language for querying FC Barcelona squad data (filter players, add or drop fields, select columns, print results).

| Lab | Deliverable |
|---|---|
| `LAB1` | Language description and initial BNF grammar |
| `LAB2` | Revised BNF, sample programs (scouting report, position finder) and grammar validation |
| `LAB3` | **Lexical analyzer** built with **Flex** (`lexer.l`) and C, with documentation |

###  Large Language Models — `SEM5/LLM`

Python labs built on a shared lab kit (`udllm_labkit`):

- **Lab 01** — Structured-output classification of student requests using schema-validated LLM responses, with an evaluation script and tests
- **Lab 02** — Tool-calling workflows: defining tools and orchestrating multi-step LLM agent workflows

###  Parallel and Distributed Programming — `SEM5/PPD`

Performance-focused multithreading in **C++** (`std::thread`) and **Java**:

- **Lab 1** — Parallel vector addition with linear and cyclic work distribution, analyzing when parallelism overcomes thread overhead
- **Lab 2** — **Parallel 2D convolution** in C++ and Java, with input generators, an automated PowerShell benchmark suite and Python result analysis
- **Seminars** — in-class exercises and measurements

### 📱 Mobile Application Development — `SEM5/MOBILE` ·  Collective Project — `SEM5/PROJ. COLECTIV`

*To be added.*

---

## Semester 6

*Upcoming.*

---

##  Highlighted Projects

| Project | Location | Stack | Why it stands out |
|---|---|---|---|
| Toy Language Interpreter | `SEM3/MAP/A1` | Java, JavaFX, Maven | Full interpreter with type checker, heap with garbage collection, file I/O and concurrent `fork` execution |
| BarcaScript DSL | `SEM5/LFTC` | BNF, Flex, C | A language designed from scratch: grammar, sample programs and lexer |
| Parallel 2D Convolution | `SEM5/PPD/lab02PPD` | C++, Java, PowerShell, Python | Cross-language benchmarking with automated analysis |
| Library Rental System | `SEM1/FP/a8`, `a9` | Python | Layered architecture, configurable persistence, Command-pattern undo/redo, unit tests |
| Event Planner | `SEM2/oop/EventPlanner` | C++, Qt | Observer pattern keeping multiple GUI windows in sync |
| News Platform (×5 stacks) | `SEM4/Web` | PHP, Angular, ASP.NET, Node.js | One domain implemented across several web architectures |
| Master–Detail DB Client | `SEM4/dbsm` | C#, SQL Server | XML-configured, scenario-driven generic CRUD forms |
| Networks Exam Quiz App | `SEM3/networks/cn_exam-main` | React, TypeScript, Tailwind | Interactive study tool |
| Connect Four | `SEM1/FP/a10` | Python | Game with a computer opponent that wins and blocks strategically |

---

##  Technology Overview

| Category | Technologies |
|---|---|
| **Languages** | Python, C, C++, Java, C#, JavaScript, TypeScript, PHP, SQL, x86 Assembly, Common Lisp, Prolog, MATLAB, Maple, Bash |
| **Frameworks & libraries** | Qt, JavaFX, Angular, React, ASP.NET Core, Node.js, jQuery, Tailwind CSS, WinForms, scikit-learn, pandas, NumPy |
| **Tooling** | CMake, Maven, Vite, Docker / Docker Compose, Apache Tomcat, Flex, NASM, Jupyter, Cisco Packet Tracer |
| **Databases** | MySQL, Microsoft SQL Server |
| **Concepts** | Layered architecture, MVC, design patterns (Command, Observer, Repository), unit testing, concurrency, socket programming, formal grammars, evolutionary algorithms, fuzzy logic, LLM tool use |

---

##  Running the Projects

Each project is self-contained. General guidelines:

| Type | How to run |
|---|---|
| **Python** (FP, AI, Cripto, LLM) | `python start.py` (or the module's entry point); notebooks with `jupyter notebook`. Install dependencies from `requirements.txt` where provided. |
| **C++** (DSA, OOP) | `cmake -S . -B build && cmake --build build`, or open the folder in CLion. Qt projects require a Qt installation. |
| **Java** (MAP, Web lab 7) | `mvn compile exec:java` or open in IntelliJ IDEA. JavaFX projects require the JavaFX SDK. |
| **C#** (DBMS) | Open the `.sln` file in Visual Studio and update the SQL Server connection string. |
| **Web** | PHP / Node labs: `docker compose up`. Angular: `npm install && ng serve`. React: `npm install && npm run dev`. |
| **Assembly** | Assemble and link with the bundled NASM toolchain in `SEM1/ASC/lab_asc/ASM_tools`. |
| **Flex** (LFTC) | `flex lexer.l && gcc lex.yy.c -o lexer` |
| **PPD** | Run the provided `run*.sh` / `benchmark.ps1` scripts; see `lab02PPD/lab2/documentation.md`. |

---

##  Academic Integrity

This repository is a personal archive of my own academic work, shared for portfolio and reference purposes. Assignment statements and some starter code (e.g. test harnesses and lab kits) belong to the respective course instructors at Babeș-Bolyai University.

If you are a current student, please use this material to learn and do not submit it as your own work. Plagiarism violates the UBB academic code of conduct.

---

<p align="center">
  <sub>Babeș-Bolyai University · Faculty of Mathematics and Computer Science · Cluj-Napoca, Romania</sub>
</p>
