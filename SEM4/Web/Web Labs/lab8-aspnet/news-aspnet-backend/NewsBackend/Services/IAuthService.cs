namespace NewsBackend.Services;

public interface IAuthService
{
    Task<ServiceResult<string>> LoginAsync(string username, string password);
}
