x = [67, 84, 88, 68, 64, 75, 69, 71, 82, 78, 87, 63, 87, 78, 62, 68, 76, 88, 88, 64, 81, 77, 89, 66, 79, 74, 84, 89, 89, 72, 49, 76, 59, 41, 67, 87,  52, 66, 51, 42];
size = 40;
sigma = 10.5;
alpha1 = 0.1;
[h,p,ci,zval] = ztest(x,0,sigma,alpha1,0);
fprintf("the %d%% conf. int. for the expected number of conocurentusers is (%f, %f)\n", 100*(1-alpha1), ci(1), ci(2));


%b
alpha1 = 0.05;
[h,p,ci,zval] = ztest(x, 70, sigma, alpha1, 1);

if h == 0
    fprintf("we dont have enough data to reject the hypothesis.\n");
    fprintf("the number of concurent users is less or equal to 70.\n");
    
else
    fprintf("we reject the new hypothesis\n");
    fprintf("the number of concurent users is more than 70.\n");
end

% c
if alpha1 < p
    fprintf("we dont have enough data to reject the hypothesis.\n");
    fprintf("the number of concurent users is less or equal to 70.\n");
    
else
    fprintf("we reject the new hypothesis\n");
    fprintf("the number of concurent users is more than 70.\n");
end

fprintf("rejection interval is (%.5f, inf)\n", norminv(1-alpha1, 0, 1));
[h, p, ci, stats] = vartest(x, sigma*sigma);

if h == 0
    fprintf("we dont agree with the manager\n");
    
else
    fprintf("we agree with the manager\n");
    
end
chi2inv(1/alpha1, 39);