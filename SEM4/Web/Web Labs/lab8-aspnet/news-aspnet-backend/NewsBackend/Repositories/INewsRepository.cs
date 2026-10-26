using NewsBackend.Models;

namespace NewsBackend.Repositories;

public interface INewsRepository
{
    Task<List<NewsArticle>> GetFilteredAsync(string? category, string startDate, string endDate);
    Task<List<NewsArticle>> GetAllAsync();
    Task<NewsArticle?> GetByIdAsync(int id);
    Task<NewsArticle> CreateAsync(NewsArticle article);
    Task<NewsArticle?> UpdateAsync(int id, NewsArticle updated);
    Task<bool> DeleteAsync(int id);
}
