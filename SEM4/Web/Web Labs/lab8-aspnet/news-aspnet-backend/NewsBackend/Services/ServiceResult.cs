namespace NewsBackend.Services;

public record ServiceResult<T>(bool Success, T? Data = default, string? Error = null, int StatusCode = 200)
{
    public static ServiceResult<T> Ok(T data) => new(true, data);
    public static ServiceResult<T> Fail(string error, int statusCode = 400) => new(false, default, error, statusCode);
}
