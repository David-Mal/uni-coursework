using NewsBackend.Models;
using NewsBackend.Repositories;

namespace NewsBackend.Services;

public class NewsService(INewsRepository news) : INewsService
{
    public Task<List<NewsArticle>> GetFilteredAsync(string? category, string startDate, string endDate) =>
        news.GetFilteredAsync(category, startDate, endDate);

    public Task<List<NewsArticle>> GetAllAsync() =>
        news.GetAllAsync();

    public async Task<ServiceResult<bool>> DeleteAsync(int id)
    {
        var deleted = await news.DeleteAsync(id);
        return deleted
            ? ServiceResult<bool>.Ok(true)
            : ServiceResult<bool>.Fail("Article not found.", 404);
    }

    public async Task<ServiceResult<SaveArticleResult>> SaveArticleAsync(
        string? idStr, string title, string text, string producer, string date, string category)
    {
        if (string.IsNullOrEmpty(title))    return ServiceResult<SaveArticleResult>.Fail("Title is required.");
        if (string.IsNullOrEmpty(text))     return ServiceResult<SaveArticleResult>.Fail("Content is required.");
        if (string.IsNullOrEmpty(date))     return ServiceResult<SaveArticleResult>.Fail("Date is required.");
        if (string.IsNullOrEmpty(category)) return ServiceResult<SaveArticleResult>.Fail("Category is required.");

        var article = new NewsArticle
        {
            Title    = title,
            Text     = text,
            Producer = producer,
            Date     = date,
            Category = category
        };

        if (!string.IsNullOrEmpty(idStr) && int.TryParse(idStr, out var id) && id > 0)
        {
            var updated = await news.UpdateAsync(id, article);
            return updated is not null
                ? ServiceResult<SaveArticleResult>.Ok(new SaveArticleResult(IsUpdate: true, Id: null))
                : ServiceResult<SaveArticleResult>.Fail("Article not found.", 404);
        }

        var created = await news.CreateAsync(article);
        return ServiceResult<SaveArticleResult>.Ok(new SaveArticleResult(IsUpdate: false, Id: created.Id));
    }
}
