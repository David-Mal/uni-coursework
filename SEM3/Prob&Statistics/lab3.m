% b
before=[56, 47, 49, 37, 38, 60, 50, 43, 43, 59, 50, 56, 54, 58];
after=[53, 21, 32, 49, 45, 38, 44, 33, 32, 43, 53, 46, 36, 48, 39, 35, 37, 36, 39, 45];
subplot(2,1,1); histogram(before,10); title("before");
subplot(2,1,2);histogram(after,10); title("after");


% c
subplot(1,2,1); boxplot(before, "Labels", "Before");
subplot(1,2,2); boxplot(after, "Labels", "After");

% d
all_data=[before'; after'];
g1=repmat({'before'}, size(before));
g2=repmat({'after'}, size(after));
g=[g1';g2'];
clf;
boxplot(all_data, g);


% a

acc=[43, 37, 50, 51, 58, 105, 52, 45, 45, 10];
boxplot(acc, 'Labels', 'Accounts');
V = isoutlier(acc, 'quartiles');
acc(V);

% c
new_acc=acc(V==0);

mean_value = mean(new_acc);
median_value = median(new_acc);
quartiles = quantile(new_acc, [0.25 0.5 0.75]);
std_dev = std(new_acc);

% 3
x = [2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024];
y = [17, 23, 31, 29, 33, 39, 39, 40, 41, 44, 47];
varofx = var(x);
varofy = var(y);
Mcov =  cov(x, y);
covxy = Mcov(1,2);
Mcorr = corrcoef(x,y);
rhoxy = Mcorr(1,2);

% c
meanofx = mean(x);
meanofy = mean(y);
scatter(x, y, 'r*');
lsline;

prediction = rhoxy * sqrt(varofy)/sqrt(varofx) * (2026 - meanofx) + meanofy;
