using NewsBackend.Models;

namespace NewsBackend.Services;

public record SaveArticleResult(bool IsUpdate, int? Id);

public interface INewsService
{
    Task<List<NewsArticle>> GetFilteredAsync(string? category, string startDate, string endDate);
    Task<List<NewsArticle>> GetAllAsync();
    Task<ServiceResult<bool>> DeleteAsync(int id);
    Task<ServiceResult<SaveArticleResult>> SaveArticleAsync(
        string? idStr, string title, string text, string producer, string date, string category);
}
