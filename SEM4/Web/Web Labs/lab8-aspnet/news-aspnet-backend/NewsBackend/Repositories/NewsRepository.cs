using Microsoft.EntityFrameworkCore;
using NewsBackend.Data;
using NewsBackend.Models;

namespace NewsBackend.Repositories;

public class NewsRepository(AppDbContext db) : INewsRepository
{
    public Task<List<NewsArticle>> GetFilteredAsync(string? category, string startDate, string endDate)
    {
        var query = db.News.Where(n =>
            string.Compare(n.Date, startDate) >= 0 &&
            string.Compare(n.Date, endDate) <= 0);

        if (!string.IsNullOrEmpty(category))
            query = query.Where(n => n.Category == category);

        return query.OrderByDescending(n => n.Date).ToListAsync();
    }

    public Task<List<NewsArticle>> GetAllAsync() =>
        db.News.OrderByDescending(n => n.Date).ToListAsync();

    public Task<NewsArticle?> GetByIdAsync(int id) =>
        db.News.FindAsync(id).AsTask();

    public async Task<NewsArticle> CreateAsync(NewsArticle article)
    {
        db.News.Add(article);
        await db.SaveChangesAsync();
        return article;
    }

    public async Task<NewsArticle?> UpdateAsync(int id, NewsArticle updated)
    {
        var existing = await db.News.FindAsync(id);
        if (existing is null) return null;

        existing.Title    = updated.Title;
        existing.Text     = updated.Text;
        existing.Producer = updated.Producer;
        existing.Date     = updated.Date;
        existing.Category = updated.Category;
        await db.SaveChangesAsync();
        return existing;
    }

    public async Task<bool> DeleteAsync(int id)
    {
        var article = await db.News.FindAsync(id);
        if (article is null) return false;

        db.News.Remove(article);
        await db.SaveChangesAsync();
        return true;
    }
}
