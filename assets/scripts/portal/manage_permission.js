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
    
    var t = $('#siteData').DataTable({
        ajax: table_data_src,
        columns: [
            { "data": "site_name" },
            {
                "data": "site_id",
                "searchable": false,
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                    return '<a href="./permission/detail/'+data+'"><i class="text-success fas fa-pencil-alt mr-1" data-toggle="tooltip" title="Edit"></i></a><a href="delete/'+data+'"><i class="text-danger fas fa-trash-alt" data-toggle="tooltip" title="Delete"></i></a>';
                }
            }
        ],
        dom: 'l<"toolbar">frtip',
        lengthChange: false,
        pageLength: 25
    });
    
    $('#siteData tbody').live( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $('#roleData tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    $("div.toolbar").html('<button type="button" class="btn btn-primary mb-1" onClick="createMode()"><i class="fa fa-plus-circle"></i> Create New Site</button>');
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
            data: $('#siteAction').serialize(),
            dataType: "json"
        }).done(function( result ) {
            $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
            if(result.success == 1){
                $('#modalAction').modal('hide');
                $('#modalSuccess').modal('show');
                $('#newUser').trigger("reset");
                t.row.add( result.form ).draw( false );
            }else{
                $.each(result.error, function(index, value) {
                    $("#feedBack ul").append('<li>'+value+'</li>');
                    $("#siteAction input[name="+index+"]").addClass('border-warning');
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
        $('#newSiteAction').val('create');
        $('#modalAction .modal-title').html('Create New Site');
    });
}

function modifyMode(pid)
{
    $('#modalAction .modal-title').html('Modify Site');
    $('#feedBack ul').html('');
    $('#feedBack').addClass('d-none');
    $.ajax({
        method: "POST",
        url: info_url+pid,
        data: $('#siteAction').serialize(),
        dataType: "json"
    }).done(function( result ) {
        $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
        if(result.success == 1){
            $('#siteAction').trigger("reset");
            $('#newSiteAction').val('modify');
            $('#newSiteID').val(result.data.site.site_id);
            $('#newSiteName').val(result.data.site.site_name);
            $('#newSiteColor').val(result.data.site.site_color);
            $('#newSiteIcon').val(result.data.site.site_icon);
            $('#newSiteStatus').val(result.data.site.site_status);
            /*Error here*/
            $('#modalTitle').html('Great !');
            $('#modalMessage').html('Sites created successfully !');
            $('#modalAction').modal('show');
        }else{
            $('#feedBack ul').html('');
            $('#feedBack').removeClass('d-none');
            $.each(result.error, function(index, value) {
                $("#feedBack ul").append('<li>'+value+'</li>');
                $("#siteAction input[name="+index+"]").addClass('border-warning');
            });
            
        }
    });

}