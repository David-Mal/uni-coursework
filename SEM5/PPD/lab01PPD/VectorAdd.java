import java.util.Random;

public class VectorAdd {
    public static void main(String[] args) {
        if (args.length != 1) {
            System.err.println("Usage: java VectorAdd <n>");
            System.exit(1);
        }

        final int n;
        try {
            n = Integer.parseInt(args[0]);
        } catch (NumberFormatException e) {
            System.err.println("n must be a positive integer");
            System.exit(1);
            return;
        }

        if (n <= 0) {
            System.err.println("n must be a positive integer");
            System.exit(1);
        }

        // Generate random vectors A and B (fixed seed for reproducibility)
        Random rng = new Random(42);
        double[] A = new double[n];
        double[] B = new double[n];
        double[] C = new double[n];

        for (int i = 0; i < n; i++) {
            A[i] = rng.nextDouble() * 2000.0 - 1000.0; // [-1000, 1000)
            B[i] = rng.nextDouble() * 2000.0 - 1000.0;
        }

        // Timed section: C = A + B
        long start = System.nanoTime();

        for (int i = 0; i < n; i++) {
            C[i] = A[i] + B[i];
        }

        long end = System.nanoTime();
        double elapsedMs = (end - start) / 1_000_000.0;

        // Prevent the JIT from optimizing the addition away
        double sink = C[0] + C[n - 1];
        if (Double.isNaN(sink)) {
            System.err.println("Unexpected NaN");
        }

        // Output only the measured time in milliseconds
        System.out.println(elapsedMs);
    }
}