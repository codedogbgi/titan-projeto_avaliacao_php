<?php require('global.php'); ?>
<?php if (isset($_POST['acao'])) {
    if ($_POST['acao'] == 'newEmpresa') {
        $sql = 'INSERT INTO tbl_empresa (nome) VALUES(:nome)';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':nome' => $_POST['nome']
        ]);
        if ($statement->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Empresa cadastrada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar empresa!']);
        }
    }
    if ($_POST['acao'] == 'removeConta') {
        $sql = 'DELETE FROM tbl_conta_pagar WHERE id_conta_pagar = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $_POST['id']
        ]);
        if ($statement->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Conta removida com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao remover conta!']);
        }
    }
    if ($_POST['acao'] == 'baixarConta') {
        $sql = 'UPDATE tbl_conta_pagar SET pago = 1, valor = :valor WHERE id_conta_pagar = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $_POST['id'],
            ':valor' => $_POST['valor']
        ]);
        if ($statement->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Conta baixada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao baixar conta!']);
        }
    }
    if ($_POST['acao'] == 'newConta') {
        $sql = 'INSERT INTO tbl_conta_pagar (valor, data_pagar, id_empresa) VALUES(:valor, :data_pagar, :id_empresa)';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':valor' => str_replace(',', '.', $_POST['valor']),
            ':data_pagar' => $_POST['vencimento'],
            ':id_empresa' => $_POST['empresa']
        ]);
        if ($statement->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Conta cadastrada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar conta!']);
        }
    }
    die;
}
?>
<?php $empresas = getAllEmpresas(); ?>
<?php $contas = getAllContas($_GET); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teste | Titan Software</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <link rel="icon" href="https://titansoftware.com.br/wp-content/uploads/2022/02/cropped-logo-2-e1645123435437-150x150.png" sizes="32x32" />
    <link rel="icon" href="https://titansoftware.com.br/wp-content/uploads/2022/02/cropped-logo-2-e1645123435437-300x300.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://titansoftware.com.br/wp-content/uploads/2022/02/cropped-logo-2-e1645123435437-300x300.png" />
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 60px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: #f5f5f5;
        }

        body>.container {
            padding: 60px 15px 0;
        }

        .container .text-muted {
            margin: 20px 0;
        }

        .footer>.container {
            padding-right: 15px;
            padding-left: 15px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Titan Software</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#modalEmpresa">Nova Empresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#modalConta">Nova Conta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Contas a pagar <button type="button" data-bs-toggle="modal" data-bs-target="#modalFiltros" class="btn btn-outline-secondary <?= !$contas && empty($_GET) ? 'd-none' : ''; ?>"><i class="fas fa-search"></i> Filtrar contas</button></h1>
        </div>
        <div class="row">
            <div class="col-md-12 container_table">
                <table id="contas" class="table hovered table-striped">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Pago</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($contas) : ?>
                            <?php foreach ($contas as $c) : ?>
                                <tr>
                                    <td><?= $c['nome']; ?></td>
                                    <td>R$ <?= convertReal($c['valor']); ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['data_pagar'])); ?></td>
                                    <td <?= ($c['pago']) ? 'class="text-success"' : 'class="text-danger"'; ?>><?= ($c['pago']) ? 'Sim' : 'Não'; ?></td>
                                    <td>
                                        <div title="Baixar conta" data-id="<?= $c['id_conta_pagar']; ?>" data-vencido="<?= $c['data_pagar'] < date('Y-m-d') ? '1' : '0'; ?>" data-desconto="<?= $c['data_pagar'] > date('Y-m-d') ? '1' : '0'; ?>" data-vencimento="<?= $c['data_pagar']; ?>" data-valor="<?= $c['valor']; ?>" class="btn btn-sm btn-primary btn-pagar"><i class="fa-solid fa-dollar-sign"></i></div>
                                        <div title="Remover conta" data-id="<?= $c['id_conta_pagar']; ?>" class="btn btn-sm btn-danger btn-remover"><i class="fas fa-trash-alt"></i></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5">Nenhuma conta cadastrada</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="text-muted">Teste feito por Rafael Esposo.</p>
        </div>
    </footer>

    <div class="modal" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFiltrosLabel">Filtros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFilter" action="" method="GET">
                        <div class="row mb-3 align-items-center">
                            <label>Empresa</label>
                            <div class="col">
                                <select id="empresa" name="empresa" class="form-control">
                                    <option value="todas">Todas as empresas</option>
                                    <?php foreach ($empresas as $e) : ?>
                                        <option <?= ($_GET['empresa'] == $e['id_empresa']) ? 'selected' : ''; ?> value="<?= $e['id_empresa']; ?>"><?= $e['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label>Vencimento</label>
                            <div class="col">
                                <input type="date" value="<?= !empty($_GET['data-ini']) ? $_GET['data-ini'] : ''; ?>" id="data-ini" name="data-ini" class="form-control">
                            </div>
                            <div class="col-1">
                                <span class="text-center">até</span>
                            </div>
                            <div class="col">
                                <input type="date" value="<?= !empty($_GET['data-fin']) ? $_GET['data-fin'] : ''; ?>" id="data-fin" name="data-fin" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label>Valores</label>
                            <div class="col">
                                <input type="text" value="<?= !empty($_GET['valor-ini']) ? $_GET['valor-ini'] : ''; ?>" id="valor-ini" name="valor-ini" class="form-control money">
                            </div>
                            <div class="col-1"><span class="text-center">até</span></div>
                            <div class="col">
                                <input type="text" value="<?= !empty($_GET['valor-fin']) ? $_GET['valor-fin'] : ''; ?>" id="valor-fin" name="valor-fin" class="form-control money">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label>Situação</label>
                            <div class="col">
                                <select id="status" name="status" class="form-control">
                                    <option value="todas">Todas</option>
                                    <option value="sim">Pago</option>
                                    <option value="nao">Em aberto</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" form="formFilter" class="btn btn-primary">Filtrar</button>
                    <?php if (!empty($_GET)) : ?>
                        <a href="/" class="btn btn-sm btn-danger">Limpar Filtros</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalEmpresa" tabindex="-1" aria-labelledby="modalEmpresaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEmpresaLabel">Nova Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newEmpresa">
                        <div class="row mb-3 align-items-center">
                            <label>Nome da Empresa</label>
                            <div class="col">
                                <input required type="text" name="nome" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" form="newEmpresa" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalBaixa" tabindex="-1" aria-labelledby="modalBaixaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBaixaLabel">Baixar Conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="append"></div>
                    <form id="baixaConta">
                        <div class="row mb-3 align-items-center">
                            <label>Data Vencimento</label>
                            <div class="col">
                                <input type="date" class="form-control vencimento">
                            </div>
                        </div>
                        <div class="row mb-3 valores-modificados align-items-center">
                            <div class="col">
                                <label>Valor Original</label>
                                <input type="text" class="form-control vlr_original money">
                            </div>
                            <div class="col col-juros">
                                <label>Valor com Juros</label>
                                <input type="text" class="form-control vlr_juros money">
                            </div>
                            <div class="col col-desconto">
                                <label>Valor com Desconto</label>
                                <input type="text" class="form-control vlr_desconto money">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" id="btnBaixaConta" onclick="baixarConta()" class="btn btn-primary">Baixar Conta</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalConta" tabindex="-1" aria-labelledby="modalContaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalContaLabel">Nova Conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!$empresas) : ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Atenção!</strong> Você precisa cadastrar uma empresa antes de cadastrar uma conta.
                        </div>
                    <?php else : ?>
                        <form id="newConta">
                            <div class="row mb-3 align-items-center">
                                <label>Empresa</label>
                                <div class="col">
                                    <select name="empresa" class="form-control">
                                        <?php foreach ($empresas as $e) : ?>
                                            <option value="<?= $e['id_empresa']; ?>"><?= $e['nome']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-6">
                                    <label>Valor</label>
                                    <input type="text" name="valor" class="form-control money">
                                </div>
                                <div class="col-6">
                                    <label>Vencimento</label>
                                    <input type="date" name="vencimento" class="form-control">
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" <?= (!$empresas) ? 'disabled' : ''; ?> form="newConta" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            var validation = '<small class="error-text position-absolute text-danger">Corrija esse campo</small>'
            $('.money').mask('000000000000000,00', {
                reverse: true
            });

            $('form#newEmpresa').on('submit', function(e) {
                e.preventDefault();
                var nome = $('form#newEmpresa input[name="nome"]').val();
                if (nome == '') {
                    $('form#newEmpresa input[name="nome"]').addClass('is-invalid');
                    $('form#newEmpresa input[name="nome"]').after(validation);
                } else {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            acao: 'newEmpresa',
                            nome: nome
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                }
            });

            $('form#newConta').on('submit', function(e) {
                e.preventDefault();
                var empresa = $('form#newConta select[name="empresa"]').val();
                var valor = $('form#newConta input[name="valor"]').val();
                var vencimento = $('form#newConta input[name="vencimento"]').val();
                if (empresa == '' || valor == '' || vencimento == '') {
                    alert('Preencha todos os campos');
                } else {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            acao: 'newConta',
                            empresa: empresa,
                            valor: valor,
                            vencimento: vencimento
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                }
            });

            $('.btn-pagar').on('click', function(e) {
                e.preventDefault();
                $('#modalBaixa .modal-body .append').html('');
                $('#modalBaixa input').attr('readonly', true);
                var id = $(this).data('id');
                var desconto = $(this).data('desconto');
                var vencido = $(this).data('vencido');
                var valor = $(this).data('valor');
                var vencimento = $(this).data('vencimento');
                var valorBaixa;
                $('#modalBaixa').modal('show');
                $('#modalBaixa .vencimento').val(vencimento);
                $('#modalBaixa .modal-body .vlr_original').val(valor.replace('.', ','));

                if (vencido == 1) {
                    valorBaixa = parseFloat(valor) + (parseFloat(valor) * 0.1);
                    $('#modalBaixa .modal-body .append').append('<div class="alert alert-warning" role="alert">Essa conta está vencida, será aplicado juros de 10% sobre o valor original.</div>');
                    $('#modalBaixa .modal-body .vlr_juros, .col-juros').show().val(parseFloat(valorBaixa).toFixed(2).replace('.', ','));
                    $('#modalBaixa .modal-body .vlr_desconto, .col-desconto').hide();
                }
                if (desconto == 1) {
                    valorBaixa = parseFloat(valor) - (parseFloat(valor) * 0.05);
                    $('#modalBaixa .modal-body .append').append('<div class="alert alert-warning" role="alert">Está conta está sendo paga antes do vencimento, será aplicado um desconto de 5% sobre o valor original.</div>');
                    $('#modalBaixa .modal-body .vlr_juros, .col-juros').hide();
                    $('#modalBaixa .modal-body .vlr_desconto, .col-desconto').show().val(parseFloat(valorBaixa).toFixed(2).replace('.', ','));
                }
                if (vencido == 0 && desconto == 0) {
                    valorBaixa = valor;
                    $('#modalBaixa .modal-body .vlr_juros, .col-juros').hide();
                    $('#modalBaixa .modal-body .vlr_desconto, .col-desconto').hide();

                }
                $('#btnBaixaConta').attr('data-id', id).attr('data-valor', valorBaixa);
            });

            $('.btn-remover').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                if (confirm('Tem certeza que deseja excluir?')) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            acao: 'removeConta',
                            id: id
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                }
            });

            $('#modalFiltros input#data-fin, #modalFiltros input#data-ini').on('change', function() {
                $(this).next('.error-text').remove();
                var dataIni = $('#modalFiltros input#data-ini').val();
                var dataFin = $('#modalFiltros input#data-fin').val();
                var today = '<?= date('Y-m-d'); ?>';
                if (dataIni > today || dataFin > today) {
                    alert('Data inicial e final não pode ser no futuro');
                    $('#modalFiltros input#data-ini').val('');
                    $(this).val('').focus().after(validation).show();
                }
                if (dataIni.length != 0 && dataFin.length != 0) {
                    if (dataIni > dataFin) {
                        alert('Data inicial não pode ser maior que a data final');
                        $('#modalFiltros input#data-fin').val('');
                        $(this).val('').focus().after(validation).show();
                    }
                }
            });

            $('#modalFiltros input#valor-fin, #modalFiltros input#valor-ini').on('change', function() {
                $(this).next('.error-text').remove();
                var valorIni = parseFloat($('#modalFiltros input#valor-ini').val());
                var valorFin = parseFloat($('#modalFiltros input#valor-fin').val());
                if (valorIni.toString().length != 0 && valorFin.toString().length != 0) {
                    if (valorIni > valorFin) {
                        alert('Valor inicial não pode ser maior que o valor final');
                        $('#modalFiltros input#valor-fin').val('');
                        $(this).val('').focus().after(validation).show();
                    }
                }
            });
            <?php if ($contas) : ?>
                $('#contas').DataTable({
                    "language": {
                        url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json'
                    },
                    "processing": true,
                    "bPaginate": false,
                    "bFilter": false,
                    "bInfo": false,
                });
            <?php endif; ?>
        });

        function baixarConta() {
            var id = $('#btnBaixaConta').data('id');
            var valor = $('#btnBaixaConta').data('valor');
            if (confirm('Deseja realmente baixar essa conta?')) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        acao: 'baixarConta',
                        id: id,
                        valor: valor,
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>