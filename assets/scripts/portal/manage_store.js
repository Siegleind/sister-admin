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

    var t = $('#storeData').DataTable({
        ajax: table_data_src,
        columns: [
            {
                "data": null,
                "searchable": false,
                "orderable": false
            },
            { "data": "store_name" },
            { "data": "store_type" },
            /*{ 
                "data": "store_type",
                "render": function ( data, type, row, meta )
                {
                    if(data == 0){
                        return 'Office';
                    }else{
                        return data;
                    }
                } 
            },*/
            { "data": "store_email" },
            {
                "data": "store_status",
                "searchable": false,
                "render": function ( data, type, row, meta ) {
                    if(data == 1){
                        return 'Enabled';
                    }else{
                        return 'Disabled';
                    }
                }
            },
            {
                "data": "store_id",
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

    $('#storeData tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $('#roleData tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $("div.toolbar").html('<button type="button" class="btn btn-primary rounded mb-1" onClick="createMode()"><i class="fa fa-plus-circle"></i> Create New Store</button>');
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
            data: $('#storeAction').serialize(),
            dataType: "json"
        }).done(function( result ) {
            //console.log('test');
                $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
                if(result.success > 0){
                    console.log('test');
                    $('#modalAction').modal('hide');
                    $('#modalSuccess').modal('show');
                    $('#storeAction').trigger("reset");
                    t.row.add( result.form ).draw( false );
                }else{
                    $.each(result.error, function(index, value) {
                        $("#feedBack ul").append('<li>'+value+'</li>');
                        $("#storeAction input[name="+index+"]").addClass('border-warning');
                    });
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
    $('#modalAction').modal('show');
    $('#modalAction').on('shown.bs.modal', function () {
        $('#roleAction').trigger("reset");
        $('#newStoreAction').val('create');
        $('#modalAction .modal-title').html('Create New Store');
    });
}

function modifyMode(pid)
{
    $('#modalAction .modal-title').html('Modify Store');
    $('#feedBack ul').html('');
    $('#feedBack').addClass('d-none');
    $.ajax({
        method: "POST",
        url: info_url+pid,
        data: $('#storeAction').serialize(),
        dataType: "json"
    }).done(function( result ) {
        $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
        if(result.success == 1){
            $('#storeAction').trigger("reset");
            $('#newStoreAction').val('modify');
            $('#newStoreID').val(result.data.role.store_id);
            $('#newStoreName').val(result.data.role.store_name);
            $('#newStoreType').val(result.data.role.store_type);
            $('#newStoreEmail').val(result.data.role.store_email);
            $('#newStoreStatus').val(result.data.role.store_status);
            /* Error Here */
            $('#modalTitle').html('Great !');
            $('#modalMessage').html('Stores created successfully !');
            $('#modalAction').modal('show');
        }else{
            $('#feedBack ul').html('');
            $('#feedBack').removeClass('d-none');
            $.each(result.error, function(index, value) {
                $("#feedBack ul").append('<li>'+value+'</li>');
                $("#storeAction input[name="+index+"]").addClass('border-warning');
            });
            
        }
    });

}