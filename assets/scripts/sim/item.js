$(document).ready(function($) {
    var forms = $("#advSearch");

    var table = $("#tableData").DataTable({
        searching: false,
        processing: true,

        orderCellsTop: true,
        ajax: {
            url: forms.attr('action'),
            dataType: 'json',
            data: function ( d ) {
                d[ctn] = ch;
                d['item_name'] = $("#advSearch input[name='item_name']").val();
            },
            type: "POST",
            dataSrc: function ( json ) {
                //Make your callback here.
                window.ch = json[ctn];
                return json.data;
            }
        },
        columns: [
            { "data": "item_name","searchable": false },
            { "data": "item_serial","searchable": false },
            { "data": "item_type","searchable": false },
            { "data": "item_user","searchable": false },
            { "data": "item_location","searchable": false },
            { "data": "item_status","searchable": false },
        ],
        lengthChange: false,
        pageLength: 10
    });
    $("#advSearch").submit(function(e){
        if(table != null){
            table.ajax.reload();
        }
    });
    $(".standardSelect").chosen({
        disable_search_threshold: 10,
        no_results_text: "Oops, nothing found!",
        width: "100%"
    });
});

function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function form_array(){
    var values = {};
    $('#advSearch :input').each(function() {
        values[this.name] = $(this).val();
    });
    return values;
}