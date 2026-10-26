using NewsBackend.Models;

namespace NewsBackend.Repositories;

public interface IUserRepository
{
    Task<User?> ValidateAsync(string username, string password);
}
