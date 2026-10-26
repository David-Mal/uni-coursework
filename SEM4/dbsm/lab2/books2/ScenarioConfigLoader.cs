using System.Xml;

namespace books
{
    public static class ScenarioConfigLoader
    {
        public static ScenarioConfig Load(string scenarioName, string xmlPath)
        {
            var doc = new XmlDocument();
            doc.Load(xmlPath);

            XmlNode? node = doc.SelectSingleNode($"//Scenario[@name='{scenarioName}']");
            if (node == null)
                throw new Exception($"Scenario '{scenarioName}' not found in scenarios.xml");

            string Attr(string name) => node.Attributes?[name]?.Value
                ?? throw new Exception($"Missing attribute '{name}' in scenario '{scenarioName}'");

            var config = new ScenarioConfig
            {
                FormTitle = Attr("FormTitle"),
                ParentTable = Attr("ParentTable"),
                ParentLabel = Attr("ParentLabel"),
                ParentPK = Attr("ParentPK"),
                ParentSelectSP = Attr("ParentSelect"),
                ParentInsertSP = Attr("ParentInsert"),
                ParentUpdateSP = Attr("ParentUpdate"),
                ParentDeleteSP = Attr("ParentDelete"),
                ChildTable = Attr("ChildTable"),
                ChildLabel = Attr("ChildLabel"),
                ChildPK = Attr("ChildPK"),
                ChildFK = Attr("ChildFK"),
                ChildSelectSP = Attr("ChildSelect"),
                ChildInsertSP = Attr("ChildInsert"),
                ChildUpdateSP = Attr("ChildUpdate"),
                ChildDeleteSP = Attr("ChildDelete"),
            };

            // Parent fields
            var parentFieldNodes = node.SelectNodes("ParentFields/Field");
            if (parentFieldNodes != null)
                foreach (XmlNode f in parentFieldNodes)
                    config.ParentFields.Add(new FieldConfig
                    {
                        ColumnName = f.Attributes!["Column"]!.Value,
                        Label = f.Attributes!["Label"]!.Value
                    });

            // Child fields
            var childFieldNodes = node.SelectNodes("ChildFields/Field");
            if (childFieldNodes != null)
                foreach (XmlNode f in childFieldNodes)
                    config.ChildFields.Add(new FieldConfig
                    {
                        ColumnName = f.Attributes!["Column"]!.Value,
                        Label = f.Attributes!["Label"]!.Value
                    });

            return config;
        }
    }
}