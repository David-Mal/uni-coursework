import java.io.*;
import java.util.Scanner;

public class Main {

    // Grid dimensions and filter span
    static int height, width, kernelSpan;

    // Data matrices
    static int[][]  inputMat;
    static int[][]  kernelMat;
    static long[][] resultMat;


    // ======================================================
    // I/O: parse source matrix and filter from disk
    // ======================================================

    static void parseInputs() throws FileNotFoundException {
        Scanner srcScan    = new Scanner(new File("MatrixF.txt"));
        Scanner filterScan = new Scanner(new File("ConvolutionM.txt"));

        height     = srcScan.nextInt();
        width      = srcScan.nextInt();
        kernelSpan = filterScan.nextInt();

        if (height <= 0 || width <= 0 || kernelSpan <= 0 || kernelSpan % 2 == 0) {
            srcScan.close();
            filterScan.close();
            throw new IllegalArgumentException("Invalid dimensions or kernel size.");
        }

        inputMat  = new int [height][width];
        kernelMat = new int [kernelSpan][kernelSpan];
        resultMat = new long[height][width];

        for (int r = 0; r < height; r++)
            for (int c = 0; c < width; c++)
                inputMat[r][c] = srcScan.nextInt();

        for (int r = 0; r < kernelSpan; r++)
            for (int c = 0; c < kernelSpan; c++)
                kernelMat[r][c] = filterScan.nextInt();

        srcScan.close();
        filterScan.close();
    }


    // ======================================================
    // I/O: write result grid to output.txt
    // ======================================================

    static void flushOutput() throws IOException {
        PrintWriter out = new PrintWriter(new FileWriter("output.txt"));

        out.println(height + " " + width);

        for (int r = 0; r < height; r++) {
            for (int c = 0; c < width; c++) {
                if (c > 0) out.print(" ");
                out.print(resultMat[r][c]);
            }
            out.println();
        }

        out.close();
    }


    // ======================================================
    // Utility: clamp an index to stay within [0, limit)
    // ======================================================

    static int clip(int val, int limit) {
        if (val < 0)      return 0;
        if (val >= limit) return limit - 1;
        return val;
    }


    // ======================================================
    // Core: apply the convolution filter at one grid cell
    // ======================================================

    static void processPoint(int row, int col) {
        long acc  = 0;
        int  half = kernelSpan / 2;

        for (int dr = 0; dr < kernelSpan; dr++) {
            for (int dc = 0; dc < kernelSpan; dc++) {
                int sr = clip(row + dr - half, height);
                int sc = clip(col + dc - half, width);
                acc += (long) inputMat[sr][sc] * kernelMat[dr][dc];
            }
        }

        resultMat[row][col] = acc;
    }


    // ======================================================
    // Strategy: single thread covers the entire grid
    // ======================================================

    static void serialPass() {
        for (int r = 0; r < height; r++)
            for (int c = 0; c < width; c++)
                processPoint(r, c);
    }


    // ======================================================
    // Strategy: split grid into horizontal row bands
    // ======================================================

    static void sweepRows(int lo, int hi) {
        for (int r = lo; r < hi; r++)
            for (int c = 0; c < width; c++)
                processPoint(r, c);
    }

    static void launchRowWorkers(int nThreads) throws InterruptedException {
        Thread[] workers = new Thread[nThreads];

        for (int t = 0; t < nThreads; t++) {
            final int lo = t       * height / nThreads;
            final int hi = (t + 1) * height / nThreads;
            workers[t] = new Thread(() -> sweepRows(lo, hi));
            workers[t].start();
        }

        for (Thread w : workers)
            w.join();
    }


    // ======================================================
    // Strategy: split grid into vertical column bands
    // ======================================================

    static void sweepCols(int lo, int hi) {
        for (int c = lo; c < hi; c++)
            for (int r = 0; r < height; r++)
                processPoint(r, c);
    }

    static void launchColWorkers(int nThreads) throws InterruptedException {
        Thread[] workers = new Thread[nThreads];

        for (int t = 0; t < nThreads; t++) {
            final int lo = t       * width / nThreads;
            final int hi = (t + 1) * width / nThreads;
            workers[t] = new Thread(() -> sweepCols(lo, hi));
            workers[t].start();
        }

        for (Thread w : workers)
            w.join();
    }


    // ======================================================
    // Entry point
    // ======================================================

    public static void main(String[] args) throws Exception {

        if (args.length != 2) {
            System.out.println("Usage: java Main <method> <threads>");
            System.out.println("  method: sequential | horizontal | vertical");
            return;
        }

        String strategy    = args[0];
        int    threadCount = Integer.parseInt(args[1]);

        if (threadCount < 1) {
            System.out.println("Thread count must be at least 1.");
            return;
        }

        parseInputs();

        long t0 = System.nanoTime();

        switch (strategy) {
            case "sequential":
                serialPass();
                break;
            case "horizontal":
                launchRowWorkers(threadCount);
                break;
            case "vertical":
                launchColWorkers(threadCount);
                break;
            default:
                System.out.println("Unknown method: " + strategy);
                return;
        }

        long t1 = System.nanoTime();

        double elapsed = (t1 - t0) / 1_000_000.0;

        flushOutput();

        System.out.println("Execution time: " + elapsed + " ms");
    }
}
