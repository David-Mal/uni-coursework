using NewsBackend.Repositories;

namespace NewsBackend.Services;

public class AuthService(IUserRepository users) : IAuthService
{
    public async Task<ServiceResult<string>> LoginAsync(string username, string password)
    {
        if (string.IsNullOrEmpty(username) || string.IsNullOrEmpty(password))
            return ServiceResult<string>.Fail("Username and password are required.", 400);

        var user = await users.ValidateAsync(username, password);
        if (user is null)
            return ServiceResult<string>.Fail("Invalid username or password.", 401);

        return ServiceResult<string>.Ok(user.Username);
    }
}
