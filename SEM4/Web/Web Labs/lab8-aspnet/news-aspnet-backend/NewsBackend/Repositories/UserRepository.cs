using Microsoft.EntityFrameworkCore;
using NewsBackend.Data;
using NewsBackend.Models;

namespace NewsBackend.Repositories;

public class UserRepository(AppDbContext db) : IUserRepository
{
    public Task<User?> ValidateAsync(string username, string password) =>
        db.Users.FirstOrDefaultAsync(u => u.Username == username && u.Password == password);
}
