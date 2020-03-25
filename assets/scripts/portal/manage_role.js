$(document).ready(function() {
    "use strict";

    [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
        new SelectFx(el);
    } );

    $('.selectpicker').selectpicker;


    $('#menuToggle').on('click', function(event) {
        $('body').toggleClass('open');
    });

    $('.search-trigger').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.search-trigger').parent('.header-left').addClass('open');
    });

    $('.search-close').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.search-trigger').parent('.header-left').removeClass('open');
    });

    var t = $('#roleData').DataTable({
        ajax: table_data_src,
        columns: [
            {
                "data": null,
                "searchable": false,
                "orderable": false
            },
            { "data": "role_name" },
            {
                "data": "role_id",
                "searchable": false,
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                    return '<a href="#" onClick="modifyMode('+data+');"><i class="text-success fas fa-pencil-alt mr-1" data-toggle="tooltip" title="Edit"></i></a><a href="delete/'+data+'"><i class="text-danger fas fa-trash-alt" data-toggle="tooltip" title="Delete"></i></a>';
                }
            }
        ],
        dom: 'l<"toolbar">frtip',
        lengthChange: false,
        pageLength: 25
    });
    $('#roleData tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $('#roleData tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    $("div.toolbar").html('<button type="button" class="btn btn-primary mb-1" onClick="createMode()"><i class="fa fa-plus-circle"></i> Create New Role</button>');
    $("div.toolbar").css('float','left');
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#submitData').click(function(){
        $.ajax({
            method: "POST",
            url: submit_url,
            data: $('#roleAction').serialize(),
            dataType: "json"
        }).done(function( result ) {
                $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
                if(result.success == 1){
                    $('#modalAction').modal('hide');
                    $('#modalSuccess').modal('show');
                    $('#newUser').trigger("reset");
                    t.row.add( result.form ).draw( false );
                }else{
                    alert( "Error: " + result.message );
                }
        });
    });
    
    $('.site-access-click').change(function() {
        var class_id = $(this).attr('data-id');
        if(this.checked) {
            console.log('unchecked');
            $(".site-access[data-id=" + class_id + "]").prop("disabled", false);
        }else{
            console.log('checked');
            $(".site-access[data-id=" + class_id + "]").prop("disabled", true);
            $(".site-access[data-id=" + class_id + "] ").prop("checked", false);
        }

    });
});

function createMode()
{
    $(".site-access").prop("disabled", true);
    $('#roleAction').trigger("reset");
    $('#modalAction').modal('show');
}

function modifyMode(pid)
{
    $.ajax({
        method: "POST",
        url: info_url+pid,
        data: $('#roleAction').serialize(),
        dataType: "json"
    }).done(function( result ) {
        $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
        if(result.success == 1){
            $('#roleAction').trigger("reset");
            $('#newRoleAction').val('modify');
            $('#newRoleID').val(result.data.role.role_id);
            $('#newRoleName').val(result.data.role.role_name);
            $(".site-access").prop("disabled", false);
            $.each(result.data.access, function(index, value) {
                $("#roleAction input:checkbox[value="+value.permission+"]").prop("checked","true");
                $("#roleAction input:checkbox[value="+value.permission+"]").removeAttr("disabled");
            });
            $('#modalAction').modal('show');
        }else{
            alert( "Error: " + result.message );
        }
    });

}