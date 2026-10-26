import java.util.Random;

public class solution_pointA {

    static class AddThread extends Thread {
        private final double[] a, b, c;
        private final int start, end;

        AddThread(double[] a, double[] b, double[] c, int start, int end) {
            this.a = a;
            this.b = b;
            this.c = c;
            this.start = start;
            this.end = end;
        }

        @Override
        public void run() {
            for (int i = start; i < end; i++) {
                c[i] = a[i] + b[i];
            }
        }
    }

    /**
     * Linear distribution: n elements are split into p contiguous segments.
     * Let q = n / p and r = n % p. The first r threads get q + 1 elements,
     * the remaining p - r threads get q elements (max size difference = 1).
     */
    static double[] add(double[] a, double[] b, int p) {
        int n = a.length;
        double[] c = new double[n];
        Thread[] threads = new Thread[p];

        int q = n / p;
        int r = n % p;

        for (int i = 0; i < p; i++) {
            int start = i * q + Math.min(i, r);
            int end = start + q + (i < r ? 1 : 0);
            threads[i] = new AddThread(a, b, c, start, end);
            threads[i].start();
        }

        for (int i = 0; i < p; i++) {
            try {
                threads[i].join();
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
                throw new RuntimeException(e);
            }
        }
        return c;
    }

    public static void main(String[] args) {
        int n = args.length > 0 ? Integer.parseInt(args[0]) : 1_000_003;
        int p = args.length > 1 ? Integer.parseInt(args[1]) : 4;

        double[] a = new double[n];
        double[] b = new double[n];
        Random rnd = new Random(42);
        for (int i = 0; i < n; i++) {
            a[i] = rnd.nextDouble() * 100;
            b[i] = rnd.nextDouble() * 100;
        }

        long t0 = System.nanoTime();
        double[] c = add(a, b, p);
        long t1 = System.nanoTime();

        // Verification against the sequential result
        boolean ok = true;
        for (int i = 0; i < n && ok; i++) {
            if (c[i] != a[i] + b[i]) ok = false;
        }

        System.out.printf("n=%d, p=%d, time=%.3f ms, correct=%b%n",
                n, p, (t1 - t0) / 1e6, ok);
    }
}