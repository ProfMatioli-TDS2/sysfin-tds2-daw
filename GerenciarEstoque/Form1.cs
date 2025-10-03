using MySqlConnector;
using System.Data;
using System.Windows.Forms;


namespace GerenciarEstoque
{
    public partial class RelatorioEstoque : Form
    {
        public RelatorioEstoque()
        {
            InitializeComponent();
        }

        private void dtgEstoque_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void RelatorioEstoque_Load(object sender, EventArgs e)
        {
            CarregarDadosAutomaticamente();
        }

        private void CarregarDadosAutomaticamente()
        {
            string connectionString = "Server=127.0.0.1;Database=aluno;Uid=aluno;Pwd=senhaaluno";
            string query = "SELECT id, nome, email FROM sua_tabela;";

            try
            {
                using (MySqlConnection connection = new MySqlConnection(connectionString))
                {
                    using (MySqlDataAdapter adapter = new MySqlDataAdapter(query, connection))
                    {
                        DataTable dataTable = new DataTable();
                        adapter.Fill(dataTable);
                        dtgEstoque.DataSource = dataTable;
                    }
                }

                dtgEstoque.Columns["id"].HeaderText = "Código";
                dtgEstoque.Columns["nome"].HeaderText = "Nome";
                dtgEstoque.Columns["email"].HeaderText = "E-mail";
                dtgEstoque.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
            }
            catch (Exception ex)
            {
                MessageBox.Show("Falha ao conectar ao banco de dados: " + ex.Message, "Erro de Conexão", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            CarregarDadosAutomaticamente();
        }
    }
}
