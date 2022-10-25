<?php
$hostname = 'mysql-1';
$username = 'root';
$password = '123456';
$database = 'titansoftware';
try {
    $pdo = new PDO(
        "mysql:host=$hostname;dbname=$database;charset=utf8",
        $username,
        $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
} catch (PDOException $e) {
    echo $e->getMessage();
}

function getAllEmpresas()
{
    global $pdo;
    $sql = "SELECT * FROM tbl_empresa";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllContas($filtros = NULL)
{
    global $pdo;
    if (!$filtros) {
        $sql = "SELECT contas.*, empresa.nome  FROM tbl_conta_pagar contas INNER JOIN tbl_empresa empresa ON contas.id_empresa = empresa.id_empresa";
    } else {
        $where = '';
        if (!empty($filtros['empresa'])) {
            if ($filtros['empresa'] != 'todas') {
                $where .= " AND contas.id_empresa = " . $filtros['empresa'];
            }
        }
        if (!empty($filtros['data-ini'])) {
            $where .= " AND contas.data_pagar >= '" . $filtros['data-ini'] . "'";
        }
        if (!empty($filtros['data-fin'])) {
            $where .= " AND contas.data_pagar <= '" . $filtros['data-fin'] . "'";
        }
        if (!empty($filtros['valor-ini'])) {
            $where .= " AND contas.valor >= " . str_replace(",", ".", $filtros['valor-ini']);
        }
        if (!empty($filtros['valor-fin'])) {
            $where .= " AND contas.valor <= " . str_replace(",", ".", $filtros['valor-fin']);
        }
        if (!empty($filtros['pago'])) {
            switch ($filtros['pago']) {
                case 'sim':
                    $where .= " AND contas.pago = 1";
                    break;
                case 'nao':
                    $where .= " AND contas.pago = 0";
                    break;
                default:
                    break;
            }
        }
        $sql = "SELECT contas.*, empresa.nome  FROM tbl_conta_pagar contas INNER JOIN tbl_empresa empresa ON contas.id_empresa = empresa.id_empresa WHERE 1=1 $where";
    }
    $sql = $pdo->query($sql);
    if ($sql->rowCount() > 0) {
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return null;
    }
}

function convertReal($vlr)
{
    return number_format($vlr, 2, ',', '.');
}
