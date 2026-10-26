namespace NewsBackend.Extensions;

public static class StringExtensions
{
    public static string OrDefault(this string value, string fallback) =>
        string.IsNullOrEmpty(value) ? fallback : value;
}
