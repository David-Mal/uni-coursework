namespace books
{
    partial class Form1
    {
        private System.ComponentModel.IContainer components = null;

        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null)) components.Dispose();
            base.Dispose(disposing);
        }

        private void InitializeComponent()
        {
            dgv = new DataGridView();
            dgvBooks = new DataGridView();
            button1 = new Button();   // Previous
            button2 = new Button();   // Next
            updateBtn = new Button();
            insertBtn = new Button();
            deleteBtn = new Button();
            btnUpdateBook = new Button();
            btnInsertBook = new Button();
            btnDeleteBook = new Button();
            lblParent = new Label();
            lblChild = new Label();
            pnlParentFields = new FlowLayoutPanel();
            pnlChildFields = new FlowLayoutPanel();
            exitBtn = new Button();

            ((System.ComponentModel.ISupportInitialize)dgv).BeginInit();
            ((System.ComponentModel.ISupportInitialize)dgvBooks).BeginInit();
            SuspendLayout();

            // dgv (parent)
            dgv.BackgroundColor = SystemColors.ControlLight;
            dgv.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            dgv.Location = new Point(30, 70);
            dgv.Size = new Size(860, 380);
            dgv.ReadOnly = true;
            dgv.SelectionMode = DataGridViewSelectionMode.FullRowSelect;

            // dgvBooks (child)
            dgvBooks.BackgroundColor = SystemColors.ControlLight;
            dgvBooks.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            dgvBooks.Location = new Point(930, 70);
            dgvBooks.Size = new Size(980, 380);
            dgvBooks.ReadOnly = true;
            dgvBooks.SelectionMode = DataGridViewSelectionMode.FullRowSelect;

            // lblParent
            lblParent.AutoSize = true;
            lblParent.Font = new Font("Segoe UI", 11F, FontStyle.Bold);
            lblParent.Location = new Point(30, 30);

            // lblChild
            lblChild.AutoSize = true;
            lblChild.Font = new Font("Segoe UI", 11F, FontStyle.Bold);
            lblChild.Location = new Point(930, 30);

            // pnlParentFields — FlowLayout unde injectăm label+textbox dinamic
            pnlParentFields.Location = new Point(30, 460);
            pnlParentFields.Size = new Size(860, 80);
            pnlParentFields.FlowDirection = FlowDirection.LeftToRight;
            pnlParentFields.WrapContents = false;

            // pnlChildFields
            pnlChildFields.Location = new Point(930, 460);
            pnlChildFields.Size = new Size(980, 80);
            pnlChildFields.FlowDirection = FlowDirection.LeftToRight;
            pnlChildFields.WrapContents = false;

            // Butoane Parent
            button1.Text = "< Prev";
            button1.Location = new Point(30, 560);
            button1.Size = new Size(100, 40);
            button1.Click += button1_Click;

            button2.Text = "Next >";
            button2.Location = new Point(140, 560);
            button2.Size = new Size(100, 40);
            button2.Click += button2_Click;

            updateBtn.Text = "Update";
            updateBtn.BackColor = Color.DarkSeaGreen;
            updateBtn.Location = new Point(260, 560);
            updateBtn.Size = new Size(110, 40);
            updateBtn.Click += updateBtn_Click;

            insertBtn.Text = "Insert";
            insertBtn.BackColor = Color.DarkKhaki;
            insertBtn.Location = new Point(380, 560);
            insertBtn.Size = new Size(110, 40);
            insertBtn.Click += insertBtn_Click;

            deleteBtn.Text = "Delete";
            deleteBtn.BackColor = Color.FromArgb(255, 128, 255);
            deleteBtn.Location = new Point(500, 560);
            deleteBtn.Size = new Size(110, 40);
            deleteBtn.Click += deleteBtn_Click;

            // Butoane Child
            btnInsertBook.Text = "Insert";
            btnInsertBook.BackColor = Color.FromArgb(192, 255, 192);
            btnInsertBook.Location = new Point(930, 560);
            btnInsertBook.Size = new Size(130, 40);
            btnInsertBook.Click += btnInsertBook_Click;

            btnUpdateBook.Text = "Update";
            btnUpdateBook.BackColor = Color.FromArgb(192, 255, 255);
            btnUpdateBook.Location = new Point(1070, 560);
            btnUpdateBook.Size = new Size(130, 40);
            btnUpdateBook.Click += btnUpdateBook_Click;

            btnDeleteBook.Text = "Delete";
            btnDeleteBook.BackColor = Color.LightCoral;
            btnDeleteBook.Location = new Point(1210, 560);
            btnDeleteBook.Size = new Size(130, 40);
            btnDeleteBook.Click += btnDeleteBook_Click;

            // Exit
            exitBtn.Text = "Exit";
            exitBtn.BackColor = Color.Red;
            exitBtn.ForeColor = Color.White;
            exitBtn.Font = new Font("Segoe UI", 10F, FontStyle.Bold);
            exitBtn.Location = new Point(1820, 560);
            exitBtn.Size = new Size(90, 40);
            exitBtn.Click += exitBtn_Click;

            // Form
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(1940, 630);
            Text = "Master-Detail";
            Load += Form1_Load;

            Controls.AddRange(new Control[] {
                dgv, dgvBooks,
                lblParent, lblChild,
                pnlParentFields, pnlChildFields,
                button1, button2, updateBtn, insertBtn, deleteBtn,
                btnInsertBook, btnUpdateBook, btnDeleteBook,
                exitBtn
            });

            ((System.ComponentModel.ISupportInitialize)dgv).EndInit();
            ((System.ComponentModel.ISupportInitialize)dgvBooks).EndInit();
            ResumeLayout(false);
        }

        // Controale fixe
        private DataGridView dgv;
        private DataGridView dgvBooks;
        private Button button1, button2;
        private Button updateBtn, insertBtn, deleteBtn;
        private Button btnUpdateBook, btnInsertBook, btnDeleteBook;
        private Button exitBtn;
        private Label lblParent, lblChild;
        private FlowLayoutPanel pnlParentFields;
        private FlowLayoutPanel pnlChildFields;
    }
}