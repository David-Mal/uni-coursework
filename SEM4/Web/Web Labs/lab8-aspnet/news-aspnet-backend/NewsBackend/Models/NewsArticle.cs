using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace NewsBackend.Models;

[Table("news")]
public class NewsArticle
{
    [Column("id")]
    public int Id { get; set; }

    [Column("title")]
    [Required, MaxLength(255)]
    public string Title { get; set; } = string.Empty;

    [Column("text")]
    [Required]
    public string Text { get; set; } = string.Empty;

    [Column("producer")]
    [MaxLength(100)]
    public string Producer { get; set; } = string.Empty;

    [Column("date")]
    [Required]
    public string Date { get; set; } = string.Empty;

    [Column("category")]
    [Required, MaxLength(50)]
    public string Category { get; set; } = string.Empty;
}
