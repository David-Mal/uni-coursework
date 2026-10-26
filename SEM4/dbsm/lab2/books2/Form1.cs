using Microsoft.Data.SqlClient;
using System.Configuration;
using System.Data;

namespace books
{
    public partial class Form1 : Form
    {
        private readonly string _connStr =
            ConfigurationManager.ConnectionStrings["BooksDB"].ConnectionString;

        private ScenarioConfig _cfg = null!;

        // ADO.NET
        private SqlConnection _conn = null!;
        private SqlDataAdapter _parentAdapter = null!;
        private SqlDataAdapter _childAdapter = null!;
        private SqlCommandBuilder _parentBuilder = null!;
        private SqlCommandBuilder _childBuilder = null!;
        private DataSet _ds = new DataSet();

        private BindingSource _parentBS = new BindingSource();
        private BindingSource _childBS = new BindingSource();

        // Textbox-urile create dinamic, indexate după ColumnName
        private Dictionary<string, TextBox> _parentTxts = new();
        private Dictionary<string, TextBox> _childTxts = new();

        public Form1() => InitializeComponent();

        private void Form1_Load(object sender, EventArgs e)
        {
            // 1. Încarcă scenariul din config
            string scenarioName = ConfigurationManager.AppSettings["ActiveScenario"]
                ?? throw new Exception("ActiveScenario not set in App.config");

            string xmlPath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "scenarios.xml");
            _cfg = ScenarioConfigLoader.Load(scenarioName, xmlPath);

            // 2. Aplică titlul formei și labelele
            this.Text = _cfg.FormTitle;
            lblParent.Text = _cfg.ParentLabel;
            lblChild.Text = _cfg.ChildLabel;

            // 3. Creează textboxes dinamice
            BuildFieldControls(_cfg.ParentFields, pnlParentFields, _parentTxts);
            BuildFieldControls(_cfg.ChildFields, pnlChildFields, _childTxts);

            // 4. Init DB
            InitialiseDB();

            // 5. Init UI bindings
            InitialiseUI();
        }

        // ── Construiește Label + TextBox pentru fiecare câmp din config ────────
        private void BuildFieldControls(
            List<FieldConfig> fields,
            FlowLayoutPanel panel,
            Dictionary<string, TextBox> dict)
        {
            panel.Controls.Clear();
            dict.Clear();

            foreach (var f in fields)
            {
                var lbl = new Label
                {
                    Text = f.Label,
                    AutoSize = false,
                    Width = 120,
                    Height = 40,
                    TextAlign = ContentAlignment.BottomLeft,
                    Font = new Font("Segoe UI", 9F)
                };

                var txt = new TextBox
                {
                    Width = 160,
                    Height = 30,
                    Margin = new Padding(0, 0, 20, 0)
                };

                dict[f.ColumnName] = txt;

                var vp = new Panel { Width = 190, Height = 80 };
                lbl.Location = new Point(0, 0);
                txt.Location = new Point(0, 42);
                vp.Controls.Add(lbl);
                vp.Controls.Add(txt);
                panel.Controls.Add(vp);
            }
        }

        // ── ADO.NET init ───────────────────────────────────────────────────────
        private void InitialiseDB()
        {
            _conn = new SqlConnection(_connStr);

            _parentAdapter = new SqlDataAdapter(_cfg.ParentSelectSP, _conn);
            _parentBuilder = new SqlCommandBuilder(_parentAdapter);
            _parentAdapter.Fill(_ds, _cfg.ParentTable);

            _childAdapter = new SqlDataAdapter(_cfg.ChildSelectSP, _conn);
            _childBuilder = new SqlCommandBuilder(_childAdapter);
            _childAdapter.Fill(_ds, _cfg.ChildTable);

            _ds.Relations.Add("ParentChild",
                _ds.Tables[_cfg.ParentTable].Columns[_cfg.ParentPK],
                _ds.Tables[_cfg.ChildTable].Columns[_cfg.ChildFK]);
        }

        // ── Binding ────────────────────────────────────────────────────────────
        private void InitialiseUI()
        {
            _parentBS.DataSource = _ds;
            _parentBS.DataMember = _cfg.ParentTable;
            dgv.DataSource = _parentBS;

            // Binding textboxes parent
            foreach (var f in _cfg.ParentFields)
                if (_parentTxts.TryGetValue(f.ColumnName, out var tb))
                    tb.DataBindings.Add("Text", _parentBS, f.ColumnName);

            _childBS.DataSource = _parentBS;
            _childBS.DataMember = "ParentChild";
            dgvBooks.DataSource = _childBS;

            // Binding textboxes child
            foreach (var f in _cfg.ChildFields)
                if (_childTxts.TryGetValue(f.ColumnName, out var tb))
                    tb.DataBindings.Add("Text", _childBS, f.ColumnName);

            _parentBS.PositionChanged += (s, e) => HighlightRow();
        }

        private void HighlightRow()
        {
            int pos = _parentBS.Position;
            if (pos < 0 || pos >= dgv.Rows.Count) return;
            dgv.ClearSelection();
            dgv.Rows[pos].Selected = true;
        }

        // ── Helper: refill ─────────────────────────────────────────────────────
        private void RefillAll(int parentPos = -1)
        {
            _ds.Tables[_cfg.ChildTable].Clear();
            _ds.Tables[_cfg.ParentTable].Clear();
            _parentAdapter.Fill(_ds, _cfg.ParentTable);
            _childAdapter.Fill(_ds, _cfg.ChildTable);
            if (parentPos >= 0) _parentBS.Position = parentPos;
        }

        private void RefillChildren()
        {
            _ds.Tables[_cfg.ChildTable].Clear();
            _childAdapter.Fill(_ds, _cfg.ChildTable);
        }

        // ── Navigare parent ────────────────────────────────────────────────────
        private void button1_Click(object sender, EventArgs e) { _parentBS.MovePrevious(); HighlightRow(); }
        private void button2_Click(object sender, EventArgs e) { _parentBS.MoveNext(); HighlightRow(); }

        // ── PARENT INSERT ──────────────────────────────────────────────────────
        private void insertBtn_Click(object sender, EventArgs e)
        {
            DataRow row = _ds.Tables[_cfg.ParentTable].NewRow();
            foreach (var f in _cfg.ParentFields)
                if (_parentTxts.TryGetValue(f.ColumnName, out var tb))
                    row[f.ColumnName] = tb.Text.Trim();

            _ds.Tables[_cfg.ParentTable].Rows.Add(row);
            try
            {
                _parentAdapter.Update(_ds, _cfg.ParentTable);
                RefillAll();
                _parentBS.MoveLast();
                HighlightRow();
                MessageBox.Show("Insert successful.");
            }
            catch (Exception ex) { row.RejectChanges(); MessageBox.Show(ex.Message); }
        }

        // ── PARENT UPDATE ──────────────────────────────────────────────────────
        private void updateBtn_Click(object sender, EventArgs e)
        {
            if (_parentBS.Current is not DataRowView rv) return;
            int pos = _parentBS.Position;

            rv.BeginEdit();
            foreach (var f in _cfg.ParentFields)
                if (_parentTxts.TryGetValue(f.ColumnName, out var tb))
                    rv[f.ColumnName] = tb.Text.Trim();
            rv.EndEdit();

            try
            {
                _parentAdapter.Update(_ds, _cfg.ParentTable);
                RefillAll(pos);
                MessageBox.Show("Update successful.");
            }
            catch (Exception ex) { rv.CancelEdit(); MessageBox.Show(ex.Message); }
        }

        // ── PARENT DELETE ──────────────────────────────────────────────────────
        private void deleteBtn_Click(object sender, EventArgs e)
        {
            if (_parentBS.Count == 0) return;
            if (MessageBox.Show("Delete this record?", "Confirm",
                MessageBoxButtons.YesNo, MessageBoxIcon.Warning) != DialogResult.Yes) return;

            if (_parentBS.Current is not DataRowView rv) return;
            rv.Row.Delete();

            try
            {
                _parentAdapter.Update(_ds, _cfg.ParentTable);
                RefillAll();
                HighlightRow();
                MessageBox.Show("Delete successful.");
            }
            catch (Exception ex) { rv.Row.RejectChanges(); MessageBox.Show(ex.Message); }
        }

        // ── CHILD INSERT ───────────────────────────────────────────────────────
        private void btnInsertBook_Click(object sender, EventArgs e)
        {
            if (_parentBS.Current is not DataRowView parentRv) return;

            DataRow row = _ds.Tables[_cfg.ChildTable].NewRow();
            foreach (var f in _cfg.ChildFields)
                if (_childTxts.TryGetValue(f.ColumnName, out var tb))
                    row[f.ColumnName] = tb.Text.Trim();

            row[_cfg.ChildFK] = parentRv[_cfg.ParentPK];
            _ds.Tables[_cfg.ChildTable].Rows.Add(row);

            try
            {
                _childAdapter.Update(_ds, _cfg.ChildTable);
                RefillChildren();
                MessageBox.Show("Child insert successful.");
            }
            catch (Exception ex) { row.RejectChanges(); MessageBox.Show(ex.Message); }
        }

        // ── CHILD UPDATE ───────────────────────────────────────────────────────
        private void btnUpdateBook_Click(object sender, EventArgs e)
        {
            if (_childBS.Count == 0 || _childBS.Current is not DataRowView rv) return;

            rv.BeginEdit();
            foreach (var f in _cfg.ChildFields)
                if (_childTxts.TryGetValue(f.ColumnName, out var tb))
                    rv[f.ColumnName] = tb.Text.Trim();
            rv.EndEdit();

            try
            {
                _childAdapter.Update(_ds, _cfg.ChildTable);
                RefillChildren();
                MessageBox.Show("Child update successful.");
            }
            catch (Exception ex) { rv.CancelEdit(); MessageBox.Show(ex.Message); }
        }

        // ── CHILD DELETE ───────────────────────────────────────────────────────
        private void btnDeleteBook_Click(object sender, EventArgs e)
        {
            if (_childBS.Count == 0) { MessageBox.Show("No child record selected."); return; }
            if (MessageBox.Show("Delete this record?", "Confirm",
                MessageBoxButtons.YesNo, MessageBoxIcon.Warning) != DialogResult.Yes) return;

            if (_childBS.Current is not DataRowView rv) return;
            rv.Row.Delete();

            try
            {
                _childAdapter.Update(_ds, _cfg.ChildTable);
                RefillChildren();
                MessageBox.Show("Child delete successful.");
            }
            catch (Exception ex) { rv.Row.RejectChanges(); MessageBox.Show(ex.Message); }
        }

        private void exitBtn_Click(object sender, EventArgs e) => Application.Exit();
    }
}