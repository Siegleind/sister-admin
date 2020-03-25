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
            $("#feedBack").html('');
            $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
            if(result.success == 1){
                if($('#newUserMode').val() == 'create'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User has been created.',
                        allowOutsideClick: false
                    });
                    $('#modalAction').modal('hide');
                    $('#userAction').trigger("reset");
                    t.row.add( result.form ).draw( false );
                }else{
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User has been modified.',
                        allowOutsideClick: false
                    });
                    t.ajax.reload();
                }
                
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Please check highlighted item.',
                    allowOutsideClick: false
                });
                $.each(result.error, function(index, value) {
                    $("#feedBack").append(`<div class="alert alert-danger" role="alert">${value}</div>`);
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
    $.ajax({
        method: "POST",
        url: info_url+pid,
        data: $('#userAction').serialize(),
        dataType: "json"
    }).done(function( result ) {
        $("#feedBack").html('');
        $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
        if(result.success){
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
            $.each(result.error, function(index, value) {
                $("#feedBack").append(`<div class="alert alert-danger" role="alert">${value}</div>`);
                $("#userAction input[name="+index+"]").addClass('border-warning');
            });
        }
    });

}


