$(document).ready(function(){

    $('.select2').select2({language: "pt-BR"});
    // $('.tags').tagsinput();

    $('.select2CidadeAjax').select2({
        minimumInputLength: 3,
        language: "pt-BR",
        ajax: {
            url: base_url+"/search/cidades",
            dataType: 'json',
            type: "GET",
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id,
                        }
                    })
                };
            },
        }
    });

});

$('form').parsley();

$('form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});

$('.form-cancel').on('click',function(e){
    e.preventDefault();

    var form = $(this).parents('form');
    var url = form.attr('action');

    swal({
        title: "Tem certeza disso?",
        text: "",
        icon: "warning",
        dangerMode: true,
        buttons: ["Cancelar", "Sim, estorne a transação!"],
        closeModal: false
    }).then((value) => {
		if( value ){
			$.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function( data ){
                    toastr.success( "Estornado com sucesso" );
                    location.reload();
                },
                error: function( data ){
                    var response = JSON.parse( data.responseText );
                    var errorString = '<ul>';
                    $.each( response.error, function( key, value ){
                        errorString += '<li>'+ value +'</li>';
                    });
                    errorString += '</ul>';
                    toastr.error( errorString );
                }
            });
        }
	});

});	

$('.form-delete').on('click',function(e){
    e.preventDefault();

    var form = $(this).parents('form');
    var url = form.attr('action');

    swal({
        title: "Tem certeza disso?",
        text: "",
        icon: "warning",
        dangerMode: true,
        buttons: ["Cancelar", "Sim, delete!"],
        closeModal: false
    }).then((value) => {
        if( value ){
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function( data ){
                    toastr.success( "Deletado com sucesso" );
                    location.reload();
                },
                error: function( data ){
                    var response = JSON.parse( data.responseText );
                    var errorString = '<ul>';
                    $.each( response.error, function( key, value ){
                        errorString += '<li>'+ value +'</li>';
                    });
                    errorString += '</ul>';
                    toastr.error( errorString );
                }
            });
        }
    });

}); 

$('.form-edit').on('submit',function(e){
    e.preventDefault();
    var url = $(this).attr('action');
    var formData = new FormData( this ) ;
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        success: function( data ){
            if( data.message )
                toastr.success( data.message );
            if( data.redirectURL )
                window.location.href = data.redirectURL;
        },
        complete: function( data ){

            console.log( data );
            var errorString = '';
            
            if( typeof data.responseJSON.error === 'object' ){

                errorString += '<ul>';
                $.each( data.responseJSON.error, function( key, value ){
                    errorString += '<li>'+ value +'</li>';
                });
                errorString += '</ul>';
                toastr.error( errorString );

            } else if( typeof data.responseJSON.error === 'string' ){

                errorString = data.responseJSON.error;
                toastr.error( errorString );

            } else if( data.responseJSON.hasOwnProperty('exception') ){
                errorString = 'Exception: ' + data.responseJSON.exception 
                            + '<br><br>' + data.responseJSON.message 
                            + '<br><br>' + data.responseJSON.file + ' on line: ' + data.responseJSON.line;
                toastr.error( errorString );
            }

        }
    });
}); 


$('#usuario_id').on('change',function(e){

    $('#email').val('');
    $('#cpf').val('');
    $('#avatar').attr('src', base_url +'/public/images/avatar.png');

    $.ajax({
        type: "GET",
        url: base_url + '/usuarios/' + $(this).val(),
        success: function( data ){

            $('#email').val( data.email );
            $('#cpf').val( data.cpf );
            if( data.imagem )
                // $('#avatar').attr( 'src', base_url +'/public/images/'+ data.imagem );
                $('#avatar').attr( 'src', data.imagem );

        }
    });

});

$("#add-banco").on('click',function(e){
    e.preventDefault();
    $('.banco').first().clone().show().appendTo('#bancos');
});

$("#add-referencia").on('click',function(e){
    e.preventDefault();
    $('.referencia').first().clone().show().appendTo('#referencias');
});

$("#add-documento").on('click',function(e){
    e.preventDefault();
    id = Math.floor(Math.random() * 10000) + 1;
    $('#documentos').append(
         '<div class="documento">'
            +'<div class="col-md-4 p-lr-o">'
                +'<div class="form-group">'
                    +'<label for="">Nome do documento</label>'
                    +'<input type="text" class="form-control" name="documento[]" placeholder="Nome do documento">'
                +'</div>'
            +'</div>'
            +'<div class="col-md-4 p-lr-o">'
                +'<div class="form-group">'
                    +'<label for="">Numeração</label>'
                    +'<input type="text" class="form-control" name="numeracao[]" placeholder="Numeração">'
                +'</div>'
            +'</div>'
            +'<div class="col-md-3 p-lr-o">'
                +'<div class="form-group">'
                    +'<label for="">Foto do Documento</label>'
                    +'<div class="customfile">'
                        +'<input type="file" name="foto[]" id="'+id+'">'
                        +'<span class="file_name"></span>'
                        +'<label class="btn btn-info" for="'+id+'"><i class="fa fa-photo"></i> Procurar</label>'
                    +'</div>'
                +'</div>'
            +'</div>'
            +'<div class="col-md-1 p-lr-o">'
                +'<div class="form-group">'
                    +'<label for=""><br></label><br>'
                    +'<a href="#" class="btn btn-danger remove-documento" title="Remover"><i class="fa fa-minus"></i></a>'
                +'</div>'
            +'</div>'
        +'</div>'
    );
});

$("#add-campo").on('click',function(e){
    e.preventDefault();
    $('.campo').first().clone().show().appendTo('#campos');
});

$("#add-experiencia").on('click',function(e){
    e.preventDefault();
    $('.experiencia').first().clone().show().appendTo('#experiencias');
});

$(document).on('click', '#add-foto',function(e){
    e.preventDefault();
    id = Math.floor(Math.random() * 10000) + 1;
    $('#fotos').append(
        '<div class="foto">'
            +'<div class="row">'
                +'<div class="col-sm-10">'
                    +'<div class="form-group">'
                        +'<label for="">Foto</label>'
                        +'<div class="customfile">'
                            +'<input type="file" name="foto[]" id="'+id+'">'
                            +'<span class="file_name"></span>'
                            +'<label class="btn btn-info" for="'+id+'"><i class="fa fa-photo"></i> Procurar</label>'
                        +'</div>'
                    +'</div>'
                +'</div>'
                +'<div class="col-sm-2">'
                    +'<div class="form-group">'
                        +'<label for=""><br></label><br>'
                        +'<a href="#" class="btn btn-danger remove-foto" title="Remover"><i class="fa fa-minus"></i></a>'
                    +'</div>'
                +'</div>'
            +'</div>'
        +'</div>'
    );
});

$("#add-desconto").on('click',function(e){
    e.preventDefault();
    $('.desconto').first().clone().show().appendTo('#descontos');
});

$(document).on('click', '.remove-banco', function(e){
    e.preventDefault();
    $(this).parents('.banco').remove();
    if( $('.banco').length == 1 )
        $("#add-banco").click();
});

$(document).on('click', '.remove-referencia', function(e){
    e.preventDefault();
    $(this).parents('.referencia').remove();
    if( $('.referencia').length == 1 )
        $("#add-referencia").click();
});

$(document).on('click', '.remove-documento', function(e){
    e.preventDefault();
    $(this).parents('.documento').remove();
    if( $('.documento').length == 0 )
        $("#add-documento").click();
});

$(document).on('click', '.remove-campo', function(e){
    e.preventDefault();
    $(this).parents('.campo').remove();
    if( $('.campo').length == 1 )
        $("#add-campo").click();
});

$(document).on('click', '.remove-experiencia', function(e){
    e.preventDefault();
    $(this).parents('.experiencia').remove();
    if( $('.experiencia').length == 1 )
        $("#add-experiencia").click();
});

$(document).on('click', '.remove-foto', function(e){
    e.preventDefault();
    $(this).parents('.foto').remove();
    if( $('.foto').length == 0 )
        $("#add-foto").click();
});

$(document).on('click', '.remove-desconto', function(e){
    e.preventDefault();
    $(this).parents('.desconto').remove();
    if( $('.desconto').length == 1 )
        $("#add-desconto").click();
});


$('body').on('click', '#toggleFilter', function(e){ 
    e.preventDefault();

    if( $('#filter').is(":visible") ){
        $(this).find('i').removeClass('fa-chevron-up');
        $(this).find('i').addClass('fa-chevron-down');
    } else {
        $(this).find('i').removeClass('fa-chevron-down');
        $(this).find('i').addClass('fa-chevron-up');
    }

    $('#filter').toggle();

});

$('body').on('focus', 'input[name=cpf]', function(){ $(this).mask('999.999.999-99') });
$('body').on('focus', 'input[name=cnpj]', function(){ $(this).mask('99.999.999/9999-99') });
$('body').on('keydown', 'input[name=cpf_cnpj]', function(){ 
    try {
        $(this).unmask();
    } catch (e) {}

    if($(this).val().length < 11){
        $(this).mask("999.999.999-99");
    } else {
        $(this).mask("99.999.999/9999-99");
    }

    // ajustando foco
    var elem = this;
    setTimeout(function(){
        // mudo a posição do seletor
        elem.selectionStart = elem.selectionEnd = 10000;
    }, 0);
    // reaplico o valor para mudar o foco
    var currentValue = $(this).val();
    $(this).val('');
    $(this).val(currentValue);
});
$('body').on('focus', 'input[name=cep]', function(){ $(this).mask('99.999-999') });
$('body').on('focus', '.telefone', function(){ $(this).mask('(99) 9 9999-9999') });
$('body').on('focus', '.decimal', function(){ $(this).mask("#.##0,00", {reverse: true}) });

$('body').on('focus', '.tags', function(){ $(this).tagsinput(); });
$('body').on('mousemove', '.tags', function(){ $(this).tagsinput(); });
$(document).ready(function(){
    $('.tags').each(function(){
        $(this).focus();
    });
});

$('select[name="tipo_campo[]"]').on('change',function(e){

    // var valorespadrao = $(this).parents('.campo').find('.tags');
    var valorespadrao2 = $(this).parents('.campo').find('.bootstrap-tagsinput > input');

    // $(valorespadrao).removeAttr('disabled');
    // $(valorespadrao2).removeAttr('disabled');
    $(valorespadrao2).parent().removeClass('disabled');

    if( 
           // $(this).val() == 'number' || 
        $(this).val() == 'date' 
        || $(this).val() == 'file' 
    ){
        // $(valorespadrao).attr('disabled','disabled');
        // $(valorespadrao2).attr('disabled','disabled');
        $(valorespadrao2).parent().addClass('disabled');
    }
});
$(document).ready(function(){
    $('select[name="tipo_campo[]"]').change();
});

$(document).on('change', '.customfile input', function(){
    $(this).parent().find('.file_name').text( this.files[0].name ); 
});

$(document).on('click', '#iniciar', function(){
    $.get( base_url +"/servicos/calendario/agendamentos/"+ $('#id').val() +"/iniciar", function(data){
        toastr.success( data.message );
        window.location.href = base_url +"/servicos/calendario/";
    });
});

function validarCPF(cpf) {  
    cpf = cpf.replace(/[^\d]+/g,'');    
    if(cpf == '') return false; 
    // Elimina CPFs invalidos conhecidos    
    if (cpf.length != 11 || 
        cpf == "00000000000" || 
        cpf == "11111111111" || 
        cpf == "22222222222" || 
        cpf == "33333333333" || 
        cpf == "44444444444" || 
        cpf == "55555555555" || 
        cpf == "66666666666" || 
        cpf == "77777777777" || 
        cpf == "88888888888" || 
        cpf == "99999999999")
            return false;       
    // Valida 1o digito 
    add = 0;    
    for (i=0; i < 9; i ++)      
        add += parseInt(cpf.charAt(i)) * (10 - i);  
        rev = 11 - (add % 11);  
        if (rev == 10 || rev == 11)     
            rev = 0;    
        if (rev != parseInt(cpf.charAt(9)))     
            return false;       
    // Valida 2o digito 
    add = 0;    
    for (i = 0; i < 10; i ++)       
        add += parseInt(cpf.charAt(i)) * (11 - i);  
    rev = 11 - (add % 11);  
    if (rev == 10 || rev == 11) 
        rev = 0;    
    if (rev != parseInt(cpf.charAt(10)))
        return false;       
    return true;   
}

function validarCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
    
}

window.ParsleyValidator.addValidator('cpf', function (value, requirement) {
    return validarCPF(value);
}, 32).addMessage('en', 'cpf', 'CPF inválido.');

window.ParsleyValidator.addValidator('cnpj', function (value, requirement) {
    return validarCNPJ(value);
}, 32).addMessage('en', 'cnpj', 'CNPJ inválido.');

window.ParsleyValidator.addValidator('cpf_cnpj', function (value, requirement) {
    if( value.length == 14 )
        return validarCPF(value);
    else
        return validarCNPJ(value);
}, 32).addMessage('en', 'cnpj', 'CNPJ inválido.');

$('.js-switch').each(function(i,html){
    new Switchery(html,{size:'small'});
});

function getOrientation(){
    return Math.abs(window.orientation) - 90 == 0 ? "landscape" : "portrait";
};
function getMobileWidth(){
    return getOrientation() == "landscape" ? screen.availHeight : screen.availWidth;
};
function getMobileHeight(){
    return getOrientation() == "landscape" ? screen.availWidth : screen.availHeight;
};
function getResolution(){
    return getMobileWidth()+' x '+getMobileHeight();
};
function visitor(){
    $.ajax({
        type: "GET",
        url: base_url + '/visitor',
        data: {
            location: window.location.href,
            orientation: getOrientation(),
            resolution: getResolution(),
        },
    });
};
$(document).ready(function(){
    visitor();
    setInterval(function(){ visitor(); }, 10000);// 10s
});
