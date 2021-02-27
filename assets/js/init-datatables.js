
function format(d) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td>Full name:</td>' +
        '<td>' + d.name + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extension number:</td>' +
        '<td>' + d.extn + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extra info:</td>' +
        '<td>And any further details here (images etc)...</td>' +
        '</tr>' +
        '</table>';
}


// Data Table

$('.convert-data-table').DataTable({
    "PaginationType": "bootstrap",
    dom: '<"tbl-head clearfix"T>,<"tbl-top clearfix"lfr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"i><"tbl-pagin pull-right"p>>',
    tableTools: {
        "sSwfPath": "swf/copy_csv_xls_pdf.swf"
    }
});




$('.colvis-data-table').DataTable({
    "PaginationType": "bootstrap",
    dom: '<"tbl-head clearfix"C>,<"tbl-top clearfix"lfr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"i><"tbl-pagin pull-right"p>>'


});


$('.responsive-data-table').DataTable({
	"language": {
		"oPaginate": {
			"sNext": "Próxima",
			"sPrevious": "Anterior"
		},
		"sEmptyTable": "Não há dados para serem mostrados.",
		"sInfo": "Mostrando _TOTAL_ registros (_START_ de _END_)",
		"sSearch": "<i class='fa fa-search'></i> ",
		"sLengthMenu": "<span>_MENU_</span>",
		"info":           "Mostrando _START_ de _END_ registros de _TOTAL_ no total",
		"infoEmpty":      "Mostrando 0 registros.",
		"infoPostFix":    ""
	},
    "PaginationType": "bootstrap",
	"pageLength": 10,
	"bLengthChange": false,
    responsive: true,
    dom: '<"tbl-top clearfix"lfr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"i><"tbl-pagin pull-right"p>>'
});