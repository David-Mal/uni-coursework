N = 10000
cnt1 = 0;
cnt2 = 0;
lambda = 1/12;
for i = [1:N]
    U = rand(1);
    X = -1/lambda*log(U);
    X1 = -1/lambda*log(U);
    X2 = -1/lambda*log(U);
    X3 = -1/lambda*log(U);
    if X > 30
        cnt1 = cnt1 + 1;
    end

    if X1+X2+X3 < 60
        cnt2 = cnt2 + 1;
    end
end

fprintf("Answer for 2.c is: %.5f for a ----> %.5f for b", cnt1/N, cnt2/N);