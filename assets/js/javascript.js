function updateFuncao() {
    var select = document.getElementById("nomeMotorista");
    var selectedOption = select.options[select.selectedIndex];
    var funcaoInput = document.getElementById("funcao");
    var idInput = document.getElementById("idInput");
    // Aqui estamos pegando o atributo data-funcao do option selecionado
    var funcao = selectedOption.getAttribute("data-funcao");
    var id = selectedOption.getAttribute("data-id");
    funcaoInput.value = funcao;
    idInput.value = id;
}

function getRoot() {
    var root = "http://" + document.location.hostname + "/CalculoDiarias/";
    return root;
}

$("#submitForm").on("click", function(event) {
    event.preventDefault();
    var dados = $("#formDiaria").serialize();

    $.ajax({
        url: getRoot() + 'crud/CadDiaria.php',
        type: 'post',
        dataType: 'json',
        data: dados,
        success: function(response) {
            if (response.retorno == 'success') {
                $("#id").val(response.id);
                $("#motorista").val(response.motorista);
                $("#dias").val(response.dias);
                $("#valorAlmoco").val(response.valorAlmoco);
                $("#valorJanta").val(response.valorJanta);
                $("#valorPernoite").val(response.valorPernoite);
                $("#total").val(response.total);
                $("#dataInicio").val(response.dataInicio);
                $("#dataFim").val(response.dataFinal);
                $("#resultadoAlmoco").html(response.resultadoAlmoco);
                $("#resultadoJanta").html(response.resultadoJanta);
                $("#resultadoPernoite").html(response.resultadoPernoite);
                $("#resultadoModal").modal("show");
            } else {
                alert(response.erros);
            }
        }
    });
});


function calcularTotal() {
    var valorAlmoco = parseFloat(document.getElementById('valorAlmoco').value);
    var valorJanta = parseFloat(document.getElementById('valorJanta').value);
    var valorPernoite = parseFloat(document.getElementById('valorPernoite').value);

    var total = valorAlmoco + valorJanta + valorPernoite;

    document.getElementById('total').value = total;
}


$("#submitBanco").on("click", function(event) {
    event.preventDefault();
    var dados = $("#formDadosSalvos").serialize();

    $.ajax({
        url: getRoot() + 'crud/salvarDiarias.php',
        type: 'post',
        dataType: 'json',
        data: dados,
        success: function(response) {
            $('#divResultado').empty(); // Limpa o conteúdo anterior
            if (response.retorno == 'error') {
                $.each(response.erros, function(key, value) {
                    $('#divResultado').append(value + '<br>'); // Adiciona os erros à div
                });
            } else if (response.retorno == 'success') {
                $('#divResultado').html('Dados inseridos com sucesso!'); // Exibe mensagem de sucesso
                $('#formDadosSalvos').trigger('reset'); // Reseta o formulário de salvar no banco
                $('#formDiaria').trigger('reset'); // Reseta o formulário principal
                setTimeout(function() {
                    $('#resultadoModal').modal('hide'); // Fecha a modal após 5 segundos
                }, 1000);
            } else {
                $('#divResultado').html('Erro desconhecido!'); // Exibe uma mensagem genérica de erro
            }
        }
    });
});

$(document).ready(function() {
    function loadTable(page = 1) {
        const motorista = $('#motorista').val();
        const dataInicial = $('#data-inicial').val();
        const dataFinal = $('#data-final').val();
        $.ajax({
            url: '../views/load_table.php',
            type: 'GET',
            dataType: 'json', // Ensure the response is treated as JSON
            data: {
                pagina: page,
                motorista: motorista,
                'data-inicial': dataInicial,
                'data-final': dataFinal
            },
            success: function(response) {
                $('#tableContainer').html(response.table);
                $('#paginationContainer').html(response.pagination);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error loading table:', textStatus, errorThrown);
            }
        });
    }

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadTable();
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadTable(page);
    });

    // Load the initial table
    loadTable();
});

$(document).on('click', '.btn-primary', function() {
    var idDiaria = $(this).data('id');
    var nome = $(this).data('nome');
    var dataInicial = $(this).data('data_inicial');
    var dataFinal = $(this).data('data_final');
    var almoco = $(this).data('almoco');
    var janta = $(this).data('janta');
    var pernoite = $(this).data('pernoite');
    var valTotal = $(this).data('val_total');
    
    $('#idDiaria').val(idDiaria);
    $('#nome').val(nome);
    $('#data_inicial').val(dataInicial);
    $('#data_final').val(dataFinal);
    $('#almoco').val(almoco);
    $('#janta').val(janta);
    $('#pernoite').val(pernoite);
    $('#val_total').val(valTotal);
});

$('#editarForm').on('submit', function(event) {
    event.preventDefault();
    
    var formData = $(this).serialize();
    
    $.ajax({
        type: 'POST',
        url: 'update_diaria.php',
        data: formData,
        success: function(response) {
            // Atualize a tabela ou forneça um feedback ao usuário
            $('#editarModal').modal('hide');
            // Recarregar a tabela
            carregarTabela();
        }
    });
});