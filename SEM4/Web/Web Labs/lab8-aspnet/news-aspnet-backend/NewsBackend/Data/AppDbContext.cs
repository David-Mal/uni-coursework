using Microsoft.EntityFrameworkCore;
using NewsBackend.Models;

namespace NewsBackend.Data;

public class AppDbContext(DbContextOptions<AppDbContext> options) : DbContext(options)
{
    public DbSet<NewsArticle> News { get; set; }
    public DbSet<User> Users { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<User>().HasData(
            new User { Id = 1, Username = "admin", Password = "admin" }
        );

        modelBuilder.Entity<NewsArticle>().HasData(
            new NewsArticle
            {
                Id = 1,
                Title = "New Health Guidelines Released",
                Text = "The WHO has released new guidelines for daily exercise and nutrition recommendations for adults.",
                Producer = "WHO Press",
                Date = "2026-04-01",
                Category = "health"
            },
            new NewsArticle
            {
                Id = 2,
                Title = "Local Elections Update",
                Text = "City council elections are scheduled for next month with several new candidates entering the race.",
                Producer = "City Times",
                Date = "2026-04-05",
                Category = "politics"
            },
            new NewsArticle
            {
                Id = 3,
                Title = "Championship Finals This Weekend",
                Text = "The national basketball championship finals begin this Saturday with a sold-out arena expected.",
                Producer = "Sports Daily",
                Date = "2026-04-08",
                Category = "sports"
            },
            new NewsArticle
            {
                Id = 4,
                Title = "Community Park Renovation Completed",
                Text = "The downtown community park has reopened after a six-month renovation with new facilities for families.",
                Producer = "Local News",
                Date = "2026-04-03",
                Category = "society"
            },
            new NewsArticle
            {
                Id = 5,
                Title = "Storm Warning for the Region",
                Text = "Meteorologists predict heavy rainfall and strong winds over the next 48 hours across the northern region.",
                Producer = "Weather Central",
                Date = "2026-04-09",
                Category = "weather"
            }
        );
    }
}
