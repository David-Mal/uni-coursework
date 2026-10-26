namespace NewsBackend.Extensions;

public static class HttpContextExtensions
{
    public static bool IsAuthenticated(this HttpContext ctx) =>
        ctx.Session.GetString("user") is not null;

    public static IResult Unauthorized(this HttpContext _) =>
        Results.Json(new { error = "Not authenticated" }, statusCode: 401);
}
