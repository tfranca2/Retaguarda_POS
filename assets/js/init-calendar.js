(function($){

    $('#agenda').fullCalendar({
        
        ignoreTimezone: true,
        editable: false,
        droppable: false,
        timeFormat: 'H:mm',
        eventLimit: 4,
        eventClick: function(ev, jsEvent, view) {

            $('#id').val('');
            $('#edit').attr('href','');
            $('#inicio').val('');
            $('#fim').val('');
            $('#cliente').val('');
            $('#servico').val('');
            $('#prestador').val('');
            $('#endereco').val('');
            $('#tipo_imovel').val('');
            $('#valor').val('');
            $('#iniciado').val('');
            $('#concluido').val('');
            $('#observacoes').html('');
            $('#campos').html('');
            $('#posservico').html('');
            $('#condominio').html('');
            $('#unidade').html('');
            $('#bloco').html('');

            $('.iniciado_block').hide();
            $('.concluido_block').hide();
            $('#iniciar').show();
            $('#concluir').show();

            $('.collapse.in').removeClass('in');


            $.ajax({
                url: window.location.href+"/agendamentos/"+ev.id,
                dataType: 'json',
                success: function(data) {
                    console.log(data);

                    $('#id').val(data.id);
                    $('#editar').attr( 'href', base_url+"/contratacao/"+data.id+"/edit" );
                    $('#inicio').val( moment(data.inicio).format("DD/MM/YYYY HH:mm:ss") );
                    $('#fim').val( moment(data.fim).format("DD/MM/YYYY HH:mm:ss") );
                    $('#cliente').val(data.cliente.nome);
                    $('#servico').val(data.servico.nome);
                    $('#prestador').val(data.prestador.usuario.name);
                    $('#endereco').val( 
                        data.cliente.endereco 
                        +' '+ data.cliente.numero 
                        +', '+ data.cliente.bairro
                        +', '+ data.cliente.cidade
                        +' - '+ data.cliente.estado
                    );
                    $('#tipo_imovel').val(data.tipo_imovel);
                    $('#valor').val( data.valor.replace('.',',') );

                    if( data.cliente.condominio )
                        $('#condominio').html( data.cliente.condominio );
                    if( data.cliente.unidade )
                        $('#unidade').html( ' | ' + data.cliente.unidade );
                    if( data.cliente.bloco )
                        $('#bloco').html( ' | ' + data.cliente.bloco );
                    
                    if( data.concluido ){
                        $('#concluido').val( moment(data.concluido).format("DD/MM/YYYY HH:mm:ss") );
                        $('.concluido_block').show();
                        $('#concluir').hide();
                        $('#editar').hide();
                    }
                    if( data.iniciado ){
                        $('#iniciado').val( moment(data.iniciado).format("DD/MM/YYYY HH:mm:ss") );
                        $('.iniciado_block').show();
                        $('#iniciar').hide();
                        $('#editar').hide();
                    } else {
                        $('#concluir').hide();
                    }

                    $('#observacoes').html(data.observacoes);

                    id = Math.floor(Math.random() * 10000) + 1;

                    // $('#campos').html(data.campos);
                    c = '<div class="row">';
                    $( data.campos ).each(function(i,obj){
                        c += '<div class="col-md-4">';
                        c += '<div class="form-group">';
                        c += '<label for="">'+ obj.nome +'</label>';
                        c += '<input type="text" class="form-control" value="'+ obj.valor +'" readonly="">';
                        c += '</div>';
                        c += '</div>';
                    });
                    c += '</div>';

                    pos = JSON.parse( data.pos_servico );
                    fotos = JSON.parse( data.fotos );

                    if( pos || fotos ){

                        c += '<div class="row">';
                            c += '<div class="col-md-12">';
                            c += '<hr>';
                            c += '<label for="">Checklist</label>';
                            c += '</div>';
                        c += '</div>';
                        c += '<div class="row">';
                        $( pos ).each(function(i,obj){
                            c += '<div class="col-md-4">';
                            c += '<div class="form-group">';
                            c += '<i class="fa fa-check-square-o" aria-hidden="true"></i> '+ obj;
                            c += '</div>';
                            c += '</div>';
                        });
                        c += '</div>';

                        c += '<div class="row">';
                        $( fotos ).each(function(i,obj){
                            c += '<div class="col-md-4">';
                            c += '<div class="form-group">';
                            c += '<a href="'+ obj +'" data-lightbox="fotos" >';
                            c += '<img src="'+ obj +'" >';
                            c += '</a>';
                            c += '</div>';
                            c += '</div>';
                        });
                        c += '</div>';

                    }

                    if( data.rating ){
                        c += '<div class="row">';
                            c += '<div class="col-md-12">';
                            c += '<hr>';
                            c += '<label for="">Feedback</label>';
                            c += '</div>';
                        c += '</div>';
                        c += '<div class="row">';
                            c += '<div class="col-md-4 text-center">';
                            for( var i=1; i<=5; i++ ){
                                if( i <= data.rating )
                                    c += ' <i class="fa fa-star" aria-hidden="true"></i>';
                                else
                                    c += ' <i class="fa fa-star-o" aria-hidden="true"></i>';
                            }
                            c += '</div>';
                            c += '<div class="col-md-8">';
                            c += '<textarea disabled class="form-control">'+ data.justificativa +'</textarea>';
                            c += '</div>';
                        c += '</div>';
                    }

                    $('#campos').html(c);

                    ps = '<input type="hidden" name="id" value="'+ data.id +'">';
                    ps += '<div class="row">';
                    $( JSON.parse( data.servico.pos_servico ) ).each(function(i,obj){
                        ps += '<div class="col-md-10 col-md-offset-1">';
                            ps += '<div class="form-group">';
                                ps += '<input type="checkbox" name="'+ obj +'"> ';
                                ps += '<label for="">'+ obj +'</label>';
                            ps += '</div>';
                        ps += '</div>';
                    });
                    ps += '</div>';
                    ps += '<div class="row">';
                        ps += '<div class="col-md-12">';
                            ps += '<hr>';
                            ps += '<div class="col-sm-6 p-0">';
                                ps += '<div class="form-group">';
                                    ps += '<label for="">Fotos do local do serviço</label>';
                                ps += '</div>';
                            ps += '</div>';
                            ps += '<div class="col-sm-1 p-0">';
                                ps += '<div class="form-group">';
                                    ps += '<a href="#" class="btn btn-info" id="add-foto" title="Adicionar"><i class="fa fa-plus"></i></a>';
                                ps += '</div>';
                            ps += '</div>';
                        ps += '</div>';
                    ps += '</div>';
                    ps += '<div id="fotos">';
                        ps += '<div class="foto">';
                            ps += '<div class="row">';
                                ps += '<div class="col-sm-10">';
                                    ps += '<div class="form-group">';
                                        ps += '<label for="">Foto</label>';
                                        ps += '<div class="customfile">';
                                            ps += '<input type="file" name="foto[]" id="'+id+'">';
                                            ps += '<span class="file_name"></span>';
                                            ps += '<label class="btn btn-info" for="'+id+'"><i class="fa fa-photo"></i> Procurar</label>';
                                        ps += '</div>';
                                    ps += '</div>';
                                ps += '</div>';
                                ps += '<div class="col-sm-2">';
                                    ps += '<div class="form-group">';
                                        ps += '<label for=""><br></label><br>';
                                        ps += '<a href="#" class="btn btn-danger remove-foto" title="Remover"><i class="fa fa-minus"></i></a>';
                                    ps += '</div>';
                                ps += '</div>';
                            ps += '</div>';
                        ps += '</div>';
                    ps += '</div>';
                    $('#posservico').html(ps);

                    $('#modal').modal();
                }
            });
        },        
        events: function( start, end, timezone, callback ){
            $.ajax({
                url: window.location.href+"/agendamentos",
                dataType: 'json',
                data: {
                    'inicio' : start.format() + ' 00:00:00',
                    'fim' : end.format() + ' 23:59:59',
                },
                success: function(data) {
                    callback(data);
                }
            });
        },

        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent '+ calEvent.className +' ">' + calEvent.description + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500');
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },


        
    });

})(jQuery);
