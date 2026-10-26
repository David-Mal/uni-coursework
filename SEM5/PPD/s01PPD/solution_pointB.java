import java.util.Arrays;
import java.util.Random;

/**
 * C[i] = f(A[i], B[i]) computed sequentially and with p explicitly created threads.
 *   Distribution a) LINEAR: contiguous segments, sizes differ by at most 1.
 *   Distribution b) CYCLIC: thread i handles indices i, i+p, i+2p, ...
 * Vectors are never split physically; each thread only gets (start, end, step).
 */
public class solution_pointB {

    // ---- operations -------------------------------------------------------
    static final int ADD = 0;        // c = a + b                  (cheap)
    static final int SQRT_SQRT = 1;  // c = sqrt(sqrt(a) + sqrt(b)) (expensive)

    static double f(int op, double x, double y) {
        return (op == ADD) ? x + y : Math.sqrt(Math.sqrt(x) + Math.sqrt(y));
    }

    static String opName(int op) {
        return (op == ADD) ? "a+b" : "sqrt(sqrt(a)+sqrt(b))";
    }

    // ---- initialisation (values in [1, 100)) ------------------------------
    static double[] init(int n, long seed) {
        Random rnd = new Random(seed);
        double[] v = new double[n];
        for (int i = 0; i < n; i++) v[i] = 1 + 99 * rnd.nextDouble();
        return v;
    }

    // ---- sequential version ------------------------------------------------
    static void sequential(double[] a, double[] b, double[] c, int op) {
        for (int i = 0; i < a.length; i++) c[i] = f(op, a[i], b[i]);
    }

    // ---- worker thread: indices start, start+step, ... < end ---------------
    static class Worker extends Thread {
        private final double[] a, b, c;
        private final int start, end, step, op;

        Worker(double[] a, double[] b, double[] c, int start, int end, int step, int op) {
            this.a = a; this.b = b; this.c = c;
            this.start = start; this.end = end; this.step = step; this.op = op;
        }

        @Override
        public void run() {
            for (int i = start; i < end; i += step) c[i] = f(op, a[i], b[i]);
        }
    }

    // ---- parallel version ---------------------------------------------------
    static void parallel(double[] a, double[] b, double[] c, int p, int op, boolean cyclic) {
        int n = a.length;
        Worker[] threads = new Worker[p];
        int q = n / p, r = n % p;

        for (int i = 0; i < p; i++) {
            if (cyclic) {
                threads[i] = new Worker(a, b, c, i, n, p, op);
            } else {
                int start = i * q + Math.min(i, r);
                int end = start + q + (i < r ? 1 : 0);
                threads[i] = new Worker(a, b, c, start, end, 1, op);
            }
            threads[i].start();
        }
        for (Worker t : threads) {
            try {
                t.join();
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
                throw new RuntimeException(e);
            }
        }
    }

    // ---- 1) verification (n = 10, p = 3) -----------------------------------
    static void verify() {
        int n = 10, p = 3;
        double[] a = init(n, 1), b = init(n, 2);
        double[] ref = new double[n], lin = new double[n], cyc = new double[n];

        System.out.println("=== Verification: n=" + n + ", p=" + p + " ===");
        for (int op : new int[]{ADD, SQRT_SQRT}) {
            sequential(a, b, ref, op);
            parallel(a, b, lin, p, op, false);
            parallel(a, b, cyc, p, op, true);
            System.out.println("Operation: " + opName(op));
            System.out.println("  sequential: " + fmt(ref));
            System.out.println("  linear    : " + fmt(lin) + "  -> " + (Arrays.equals(ref, lin) ? "OK" : "WRONG"));
            System.out.println("  cyclic    : " + fmt(cyc) + "  -> " + (Arrays.equals(ref, cyc) ? "OK" : "WRONG"));
        }
        System.out.println();
    }

    static String fmt(double[] v) {
        StringBuilder sb = new StringBuilder("[");
        for (int i = 0; i < v.length; i++) sb.append(i > 0 ? ", " : "").append(String.format("%.3f", v[i]));
        return sb.append("]").toString();
    }

    // ---- timing helpers ------------------------------------------------------
    static final int WARMUP = 3, RUNS = 7;

    /** Median time in ms; parallel == false -> sequential, otherwise p threads. */
    static double time(double[] a, double[] b, double[] c, int p, int op, boolean cyclic, boolean par) {
        for (int w = 0; w < WARMUP; w++) run(a, b, c, p, op, cyclic, par);
        double[] t = new double[RUNS];
        for (int r = 0; r < RUNS; r++) {
            long t0 = System.nanoTime();
            run(a, b, c, p, op, cyclic, par);
            t[r] = (System.nanoTime() - t0) / 1e6;
        }
        Arrays.sort(t);
        return t[RUNS / 2];
    }

    static void run(double[] a, double[] b, double[] c, int p, int op, boolean cyclic, boolean par) {
        if (par) parallel(a, b, c, p, op, cyclic);
        else sequential(a, b, c, op);
    }

    // ---- 2) find smallest n (doubling) where parallel < sequential ---------
    static void findCrossover(int op, boolean cyclic, int p, int nMax) {
        String label = String.format("%-24s %-6s p=%-3d", opName(op), cyclic ? "cyclic" : "linear", p);
        for (int n = 1000; n <= nMax; n *= 2) {
            double[] a = init(n, 1), b = init(n, 2);
            double[] cs = new double[n], cp = new double[n];

            double ts = time(a, b, cs, p, op, cyclic, false);
            double tp = time(a, b, cp, p, op, cyclic, true);

            if (tp < ts) {
                boolean ok = Arrays.equals(cs, cp);
                System.out.printf("%s -> n=%,10d  seq=%8.3f ms  par=%8.3f ms  speedup=%.2f  %s%n",
                        label, n, ts, tp, ts / tp, ok ? "" : "(RESULT MISMATCH!)");
                return;
            }
        }
        System.out.printf("%s -> parallel never faster up to n=%,d%n", label, nMax);
    }

    public static void main(String[] args) {
        int nMax = args.length > 0 ? Integer.parseInt(args[0]) : (1 << 24);
        int[] ps = {4, 6, 8, 16};

        System.out.println("Available processors: " + Runtime.getRuntime().availableProcessors());
        System.out.println();
        verify();

        for (int op : new int[]{ADD, SQRT_SQRT}) {
            System.out.println("=== Smallest n (doubling from 1000) where parallel beats sequential: "
                    + opName(op) + " ===");
            for (boolean cyclic : new boolean[]{false, true}) {
                for (int p : ps) findCrossover(op, cyclic, p, nMax);
            }
            System.out.println();
        }

        System.out.println("Notes:");
        System.out.println(" - Parallel time includes creating, starting and joining p threads each run,");
        System.out.println("   so small n is dominated by this overhead and the sequential loop wins.");
        System.out.println(" - a+b is memory-bound and very cheap per element: the crossover n is large");
        System.out.println("   (or never reached). sqrt(sqrt(a)+sqrt(b)) does far more work per element,");
        System.out.println("   so the thread overhead is amortised at a much smaller n.");
        System.out.println(" - Cyclic access has stride p, which is worse for cache locality than linear.");
    }
}