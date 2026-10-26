using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace NewsBackend.Models;

[Table("users")]
public class User
{
    [Column("id")]
    public int Id { get; set; }

    [Column("username")]
    [Required, MaxLength(100)]
    public string Username { get; set; } = string.Empty;

    [Column("password")]
    [Required, MaxLength(255)]
    public string Password { get; set; } = string.Empty;
}
