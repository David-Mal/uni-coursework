namespace books
{
    public class FieldConfig
    {
        public string ColumnName { get; set; } = "";
        public string Label { get; set; } = "";
    }

    public class ScenarioConfig
    {
        public string FormTitle { get; set; } = "";

        // Parent
        public string ParentTable { get; set; } = "";
        public string ParentLabel { get; set; } = "";
        public string ParentSelectSP { get; set; } = "";  // stored proc sau query
        public string ParentInsertSP { get; set; } = "";
        public string ParentUpdateSP { get; set; } = "";
        public string ParentDeleteSP { get; set; } = "";
        public string ParentPK { get; set; } = "";
        public List<FieldConfig> ParentFields { get; set; } = new();

        // Child
        public string ChildTable { get; set; } = "";
        public string ChildLabel { get; set; } = "";
        public string ChildSelectSP { get; set; } = "";
        public string ChildInsertSP { get; set; } = "";
        public string ChildUpdateSP { get; set; } = "";
        public string ChildDeleteSP { get; set; } = "";
        public string ChildPK { get; set; } = "";
        public string ChildFK { get; set; } = "";        // FK spre parent
        public List<FieldConfig> ChildFields { get; set; } = new();
    }
}