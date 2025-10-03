namespace GerenciarEstoque
{
    partial class RelatorioEstoque
    {
        /// <summary>
        ///  Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        ///  Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        ///  Required method for Designer support - do not modify
        ///  the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(RelatorioEstoque));
            dtgEstoque = new DataGridView();
            Voltar = new Button();
            button1 = new Button();
            text = new Label();
            ((System.ComponentModel.ISupportInitialize)dtgEstoque).BeginInit();
            SuspendLayout();
            // 
            // dtgEstoque
            // 
            dtgEstoque.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            dtgEstoque.Location = new Point(23, 45);
            dtgEstoque.Name = "dtgEstoque";
            dtgEstoque.ReadOnly = true;
            dtgEstoque.Size = new Size(495, 376);
            dtgEstoque.TabIndex = 0;
            dtgEstoque.CellContentClick += dtgEstoque_CellContentClick;
            // 
            // Voltar
            // 
            Voltar.BackColor = Color.Transparent;
            Voltar.BackgroundImage = (Image)resources.GetObject("Voltar.BackgroundImage");
            Voltar.BackgroundImageLayout = ImageLayout.Stretch;
            Voltar.ForeColor = Color.Transparent;
            Voltar.Location = new Point(23, 12);
            Voltar.Name = "Voltar";
            Voltar.Size = new Size(22, 25);
            Voltar.TabIndex = 1;
            Voltar.UseVisualStyleBackColor = false;
            // 
            // button1
            // 
            button1.BackColor = Color.Transparent;
            button1.BackgroundImage = (Image)resources.GetObject("button1.BackgroundImage");
            button1.BackgroundImageLayout = ImageLayout.Stretch;
            button1.ForeColor = Color.Transparent;
            button1.Location = new Point(51, 12);
            button1.Name = "button1";
            button1.Size = new Size(22, 25);
            button1.TabIndex = 1;
            button1.UseVisualStyleBackColor = false;
            button1.Click += button1_Click;
            // 
            // text
            // 
            text.AutoSize = true;
            text.Font = new Font("Segoe UI Black", 18F, FontStyle.Bold | FontStyle.Italic, GraphicsUnit.Point, 0);
            text.Location = new Point(124, 9);
            text.Name = "text";
            text.Size = new Size(303, 32);
            text.TabIndex = 2;
            text.Text = "RELATÓRIO DE ESTOQUE";
            // 
            // RelatorioEstoque
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(542, 450);
            Controls.Add(text);
            Controls.Add(button1);
            Controls.Add(Voltar);
            Controls.Add(dtgEstoque);
            Name = "RelatorioEstoque";
            Text = "Relatório de Estoque";
            Load += RelatorioEstoque_Load;
            ((System.ComponentModel.ISupportInitialize)dtgEstoque).EndInit();
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private DataGridView dtgEstoque;
        private Button Voltar;
        private Button button1;
        private Label text;
    }
}
