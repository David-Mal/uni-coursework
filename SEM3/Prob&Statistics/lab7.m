A = [85, 92, 97, 65, 75, 96];
B = [81, 79, 76, 84, 83, 77, 78, 82];

% Define alpha explicitly to avoid conflict with built-in function
alpha = 0.05; 

%% Part a) Comparing Variances (Two-tailed)
% H0: sigmaA^2 = sigmaB^2
% H1: sigmaA^2 != sigmaB^2

[H, P, CI, STATS] = vartest2(A, B, 'Alpha', alpha, 'Tail', 'both');

if H == 1
    fprintf("We reject H0, the variances are different\n");
    varType = 'unequal';
else
    fprintf("We do NOT reject H0, the variances are the same\n");
    varType = 'equal';
end

% Rejection Region for F-test
fstat = STATS.fstat;
cv1 = finv(alpha/2, STATS.df1, STATS.df2);
cv2 = finv(1-alpha/2, STATS.df1, STATS.df2);

fprintf("The test statistic (F): %.3f\n", fstat);
fprintf("Rejection region: (-inf, %.3f) U (%.3f, inf)\n", cv1, cv2);


%% Part b) Comparing Means (Right-tailed)
% H0: miuA <= miuB
% H1: miuA > miuB (Player A's claim)

[H_mean, P_mean, CI_mean, STATS_mean] = ttest2(A, B, 'Alpha', alpha, 'Tail', 'right', 'Vartype', varType);

if H_mean == 1
    fprintf("Player A's claim is correct (Reject H0)\n");
else
    fprintf("Player A's claim is wrong (Fail to reject H0)\n");
end

% Rejection Region for T-test
tstat = STATS_mean.tstat;
critical_t = tinv(1-alpha, STATS_mean.df);

fprintf("The test statistic (T): %.3f\n", tstat);
fprintf("Rejection region: (%.3f, inf)\n", critical_t);



%% Part c) Confidence Interval
alpha_c = 0.10; % 90% Confidence level

[~, ~, CI_diff, ~] = ttest2(A, B, 'Alpha', alpha_c, 'Tail', 'both', 'Vartype', varType);

fprintf("90%% CI for (miuA - miuB): [%.4f, %.4f]\n", CI_diff(1), CI_diff(2));