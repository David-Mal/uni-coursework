using NewsBackend.Extensions;
using NewsBackend.Services;

namespace NewsBackend.Endpoints;

public static class AuthEndpoints
{
    public static void MapAuthEndpoints(this WebApplication app)
    {
        app.MapMethods("/login.php",  ["OPTIONS"], () => Results.Ok());
        app.MapMethods("/logout.php", ["OPTIONS"], () => Results.Ok());

        app.MapPost("/login.php", async (HttpRequest req, HttpContext ctx, IAuthService auth) =>
        {
            var form     = await req.ReadFormAsync();
            var username = form["username"].ToString().Trim();
            var password = form["password"].ToString();

            var result = await auth.LoginAsync(username, password);
            if (!result.Success)
                return Results.Json(new { success = false, error = result.Error }, statusCode: result.StatusCode);

            ctx.Session.SetString("user", result.Data!);
            return Results.Json(new { success = true, username = result.Data });
        });

        app.MapGet("/logout.php", (HttpContext ctx) =>
        {
            ctx.Session.Clear();
            return Results.Json(new { success = true });
        });
    }
}
