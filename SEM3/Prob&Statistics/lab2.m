p = 0.3;
N = 1e6;

count_fail4 = 0;
count_fail_le2 = 0;

for i = 1:N
    failures = 0;
    while rand() >= p    % failure
        failures = failures + 1;
    end
    
    if failures == 4
        count_fail4 = count_fail4 + 1;
    end
    if failures <= 3
        count_fail_le2 = count_fail_le2 + 1;
    end
end

p_fail4 = count_fail4 / N;
p_fail_le2 = count_fail_le2 / N;

fprintf('Estimated P(exactly 4 failures) = %.4f\n', p_fail4);
fprintf('Estimated P(at most 2 failures) = %.4f\n', p_fail_le2);
