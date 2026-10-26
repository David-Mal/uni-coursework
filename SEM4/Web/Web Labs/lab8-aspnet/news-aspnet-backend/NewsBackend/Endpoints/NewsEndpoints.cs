using NewsBackend.Extensions;
using NewsBackend.Services;

namespace NewsBackend.Endpoints;

public static class NewsEndpoints
{
    public static void MapNewsEndpoints(this WebApplication app)
    {
        app.MapMethods("/fetch_news.php", ["OPTIONS"], () => Results.Ok());
        app.MapMethods("/admin.php",      ["OPTIONS"], () => Results.Ok());

        app.MapGet("/fetch_news.php", async (HttpRequest req, HttpContext ctx, INewsService news) =>
        {
            if (!ctx.IsAuthenticated()) return ctx.Unauthorized();

            var category  = req.Query["category"].ToString();
            var startDate = req.Query["start"].ToString().OrDefault("1900-01-01");
            var endDate   = req.Query["end"].ToString().OrDefault("2099-12-31");

            return Results.Json(await news.GetFilteredAsync(category, startDate, endDate));
        });

        app.MapGet("/admin.php", async (HttpRequest req, HttpContext ctx, INewsService news) =>
        {
            if (!ctx.IsAuthenticated()) return ctx.Unauthorized();

            if (req.Query.ContainsKey("delete"))
            {
                if (!int.TryParse(req.Query["delete"], out var id))
                    return Results.Json(new { success = false, error = "Invalid id." }, statusCode: 400);

                var result = await news.DeleteAsync(id);
                return result.Success
                    ? Results.Json(new { success = true, action = "deleted" })
                    : Results.Json(new { success = false, error = result.Error }, statusCode: result.StatusCode);
            }

            return Results.Json(await news.GetAllAsync());
        });

        app.MapPost("/admin.php", async (HttpRequest req, HttpContext ctx, INewsService news) =>
        {
            if (!ctx.IsAuthenticated()) return ctx.Unauthorized();

            var form     = await req.ReadFormAsync();
            var idStr    = form["id"].ToString().Trim();
            var title    = form["title"].ToString().Trim();
            var text     = form["text"].ToString().Trim();
            var producer = form["producer"].ToString().Trim();
            var date     = form["date"].ToString().Trim();
            var category = form["category"].ToString().Trim();

            var result = await news.SaveArticleAsync(idStr, title, text, producer, date, category);
            if (!result.Success)
                return Results.Json(new { success = false, error = result.Error }, statusCode: result.StatusCode);

            return result.Data!.IsUpdate
                ? Results.Json(new { success = true, action = "updated" })
                : Results.Json(new { success = true, action = "created", id = result.Data.Id });
        });
    }
}
