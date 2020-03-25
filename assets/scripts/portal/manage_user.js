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

    var t = $('#userData').DataTable({
        ajax: table_data_src,
        columns: [
            {
                "data": null,
                "searchable": false,
                "orderable": false
            },
            { "data": "email" },
            { "data": "display_name" },
            { "data": "group_name" },
            { "data": "status" },
            {
                "data": "user_id",
                "searchable": false,
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                    return '<span onClick="modifyMode('+data+');" role="button" style="cursor: pointer;"><i class="text-success fas fa-pencil-alt fas-xs mr-1" data-toggle="tooltip" title="Edit"></i></span><a href="delete/'+data+'"><i class="text-danger fas fa-trash-alt fas-xs" data-toggle="tooltip" title="Delete"></i></a>';
                }
            }
        ],
        dom: 'l<"toolbar">frtip',
        lengthChange: false,
        pageLength: 25
    });
    $("div.toolbar").html('<button type="button" class="btn btn-primary mb-1 rounded" data-toggle="modal" data-target="#modalCreate" onClick="createMode();"><i class="fa fa-plus-circle"></i> Create User</button>');
    $("div.toolbar").css('float','left');
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    $('#submitNew').click(function(){
        $.ajax({
            method: "POST",
            url: submit_url,
            data: $('#userAction').serialize(),
            dataType: "json"
        }).done(function( result ) {
            $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
            if(result.success == 1){
                $('#modalCreate').modal('hide');
                $('#modalSuccess').modal('show');
                t.row.add( result.form ).draw( false );
                $('#newUser').trigger("reset");
            }else{
                $("#feedBack ul").append('<li>'+result.message+'</li>');
                $("#feedBack").removeClass("d-none");
                $.each(result.error, function(index, value) {
                    $("#feedBack ul").append('<li>'+value+'</li>');
                    $("#siteAction input[name="+index+"]").addClass('border-warning');
                });
            }
        });
    });
});
function createMode()
{
    $('#userAction').trigger("reset");
    $('#newUserMode').val('create');
    $('#modalAction .modal-title').html('Create User');
    $('#modalAction').modal('show');
}

function modifyMode(pid)
{
    $('#modalAction .modal-title').html('Modify User');
    $('#feedBack ul').html('');
    $('#feedBack').addClass('d-none');
    $.ajax({
        method: "POST",
        url: info_url+pid,
        data: $('#userAction').serialize(),
        dataType: "json"
    }).done(function( result ) {
        $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
        if(result.success == 1){
            $('#userAction').trigger("reset");
            $('#newUserMode').val('modify');
            $('#newEmail').val(result.data.email);
            $('#newUserID').val(result.data.user_id);
            $('#newDName').val(result.data.display_name);
            $('#newGroup').val(result.data.role_id);
            $('#newStatus').val(result.data.user_status);
            $('#newStore').val(result.data.store_id);
            /*Error here*/
            $('#modalTitle').html('Great !');
            $('#modalMessage').html('User successfully modified !');
            $('#modalAction').modal('show');
        }else{
            $('#feedBack ul').html('');
            $('#feedBack').removeClass('d-none');
            $.each(result.error, function(index, value) {
                $("#feedBack ul").append('<li>'+value+'</li>');
                $("#userAction input[name="+index+"]").addClass('border-warning');
            });

        }
    });

}


