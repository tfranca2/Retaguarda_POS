;(function ($) {


    /* initialize the external events
     -----------------------------------------------------------------*/

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });


    /* initialize the calendar
     -----------------------------------------------------------------*/

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar').fullCalendar({
        ignoreTimezone: false,
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        editable: false,
        droppable: false, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }

        },
        dayClick: function(date, jsEvent, view) {

            $("#data_selecionada").html( "<h4><strong>"+date.format("DD")+" De "+date.format("MMM")+"</strong></h4>" )
            $("#data-selecao").show();
            consultorio =  $('#consultorio-selecionado').val();

             // alert("Data: "+date.format("DD-M-YYYY")+" Selecionada com sucesso.");
             $.ajax({
                url: window.location.href+"/dias",
                dataType: 'json',
                data: {
                    day: date.format("YYYY-M-DD"),
                    consultorio: consultorio
                },
                complete: function(data) {
                      horarios = data.responseJSON;
                      $("#horarios").html(" "); //clear everything from movie div

                      horarios.forEach(function(entry) {   
                          var d = new Date(entry.horario);
                          hours = addZero(d.getHours());
                          min = addZero(d.getMinutes());
                       $("#horarios").append("<div class='col-sm-2 col-md-2'><button type='button' class='btn btn-info btn-lg btn-radius-default' onclick='agendar("+entry.id+")'>"+hours+":"+min+"</button></div>");
                        $("#horario-msg").hide();
                       });
                }
            });

        },       
        eventClick: function(ev, jsEvent, view) {
            date = ev.start;
            $("#data_selecionada").html( "<h4><strong>"+date.format("DD")+" De "+date.format("MMM")+"</strong></h4>" )
            $("#data-selecao").show();
            consultorio =  $('#consultorio-selecionado').val();

             // alert("Data: "+date.format("DD-M-YYYY")+" Selecionada com sucesso.");
             $.ajax({
                url: window.location.href+"/dias",
                dataType: 'json',
                data: {
                    day: date.format("YYYY-M-DD"),
                    consultorio: consultorio
                },
                complete: function(data) {
                      horarios = data.responseJSON;
                      $("#horarios").html(" "); //clear everything from movie div

                      horarios.forEach(function(entry) {   
                          var d = new Date(entry.horario);
                          hours =  addZero(d.getHours());
                          min =  addZero(d.getMinutes());
                       $("#horarios").append("<div class='col-sm-2 col-md-2'><button type='button' class='btn btn-info btn-lg btn-radius-default' onclick='agendar("+entry.id+")'>"+hours+":"+min+"</button></div>");
                        $("#horario-msg").hide();
                       });
                }
            });

        },        
        events: function(start, end, timezone, callback) {
        $.ajax({
            url: window.location.href+"/agendamentos",
            dataType: 'json',
            data: {
                agenda: true
            },
            complete: function(data) {
                var events = data.responseJSON;
                if (events.length > 0) {
                     $("#data-msg").hide();
                }else{
                    $("#calendar").hide();

                }

                callback(events);
            }
        });
    }
    });


    $('#agenda').fullCalendar({

        ignoreTimezone: false,
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],        
        buttonText: {today: "Hoje"},
        editable: false,
        droppable: false, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#agenda').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }

        },
        dayClick: function(date, jsEvent, view) {

            $("#data_selecionada").html( "<h4><strong>"+date.format("DD")+" De "+date.format("MMM")+"</strong></h4>" )
            $("#data-selecao").show();
            consultorio =  $('#consultorio-selecionado').val();

             // alert("Data: "+date.format("DD-M-YYYY")+" Selecionada com sucesso.");
             $.ajax({
                url: window.location.href+"/dias",
                dataType: 'json',
                data: {
                    day: date.format("YYYY-M-DD"),
                    consultorio: consultorio
                },
                complete: function(data) {
                      horarios = data.responseJSON;
                      $("#horarios").html(" "); //clear everything from movie div

                      horarios.forEach(function(entry) {   
                          var d = new Date(entry.horario);
                          hours = addZero(d.getHours());
                          min = addZero(d.getMinutes());
                       $("#horarios").append("<div class='col-sm-2 col-md-2'><button type='button' class='btn btn-info btn-lg btn-radius-default' onclick='agendar("+entry.id+")'>"+hours+":"+min+"</button></div>");
                        $("#horario-msg").hide();
                       });
                }
            });

        },       
        eventClick: function(ev, jsEvent, view) {
            console.log(ev);
            $('#myModal').modal('show')
            $('#cliente_nome').val(ev.nome);
            $('#cliente_email').val(ev.email);
            $('#cliente_telefone').val(ev.telefone);
            $('#cliente_idade').val(ev.idade);
            if( ev.foto.length > 0 )
                $('#foto').css({ 'background' : 'url("'+ ev.foto +'") #eee no-repeat top center / cover' });
            else
                $('#foto').css({ 'background' : 'url("https://place-hold.it/300/eeeeee/626262&text=Foto&fontsize=15&bold=true") #eee no-repeat top center / cover' });
            $('#profissional').val(ev.profissional);
            $('#valor').val(ev.valor);
            $('#consultorio').val(ev.consultorio);
            $('#endereco').val(ev.endereco);
            $('#horario').val(ev.horario);

            $('#id').val(ev.id);
        },        
        events: function(start, end, timezone, callback) {
        $.ajax({
            url: window.location.href+"/agendamentos",
            dataType: 'json',
            data: {
                agenda: true
            },
            complete: function(data) {
                var events = data.responseJSON;
                if (events.length > 0) {
                    $("#data-msg").hide();
                }else{
                    $("#agenda").hide();
		            $("#data-msg-horario").show();
                }
                callback(events);
            }
        });
    }
    });


function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

})(jQuery);
