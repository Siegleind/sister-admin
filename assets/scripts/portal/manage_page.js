$(document).ready(function() {
    "use strict";
    
    $('#menuToggle').on('click', function(event) {
        $('body').toggleClass('open');
    });
    
    $('#formType').on('change', function(event) {
        if($(this).val() == 1){
            $("#formContentEmbed").prop('disabled', true);
            $("#formContentHTML").prop('disabled', false);
            $("#formContentEmbed").addClass('d-none');
            $(".tox").removeClass('d-none');
            $("#formContentHTML").removeClass('d-none');
        }else if($(this).val() == 2){
            $("#formContentEmbed").prop('disabled', false);
            $("#formContentHTML").prop('disabled', true);
            $(".tox").addClass('d-none');
            $("#formContentEmbed").removeClass('d-none');
            $("#formContentHTML").addClass('d-none');
        }
    });

    $('#formContentHTML').tinymce({
        
        width: 700,
        menubar: "file edit insert view format table tools help",
        plugins: [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste image imagetools",
            "table"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image imagetools",
        paste_data_images: true,
        file_picker_types: 'image',
  /* and here's our custom image picker*/
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
              Note: In modern browsers input[type="file"] is functional without
              even adding it to the DOM, but that might not be the case in some older
              or quirky browsers like IE, so you might want to add it to the DOM
              just in case, and visually hide it. And do not forget do remove it
              once you do not need it anymore.
            */

            input.onchange = function () {
              var file = this.files[0];

              var reader = new FileReader();
              reader.onload = function () {
                /*
                  Note: Now we need to register the blob in TinyMCEs image blob
                  registry. In the next release this part hopefully won't be
                  necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
              };
              reader.readAsDataURL(file);
            };

            input.click();
        }
    });
    var t = $('#userData').DataTable({
        ajax: table_data_src,
        columns: [
            {
                "data": null,
                "searchable": false,
                "orderable": false
            },
            {
                "data": "page_title",
                "searchable": true,
                "orderable": true,
                "render": function ( data, type, row, meta ) {
                    return `<a href="${row.page_domain}${row.page_url}">${data}</a>`;
                }
            },
            { "data": "page_group" },
            { "data": "page_type" },
            { "data": "page_status"},
            {
                "data": "page_id",
                "searchable": false,
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                    return '<span onClick="modifyMode('+data+');" role="button" style="cursor: pointer;"><i class="text-success fas fa-pencil-alt fas-xs mr-1" data-toggle="tooltip" title="Edit"></i> Modify</span>';
                }
            },
            {
                "data": "page_id",
                "searchable": false,
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                    return '<span onClick="modifyMode('+data+');" role="button" style="cursor: pointer;"><i class="text-success fas fa-pencil-alt fas-xs mr-1" data-toggle="tooltip" title="Edit"></i> Delete</span>';
                }
            }
        ],
        dom: 'l<"toolbar">frtip',
        lengthChange: false,
        pageLength: 25
    });
    $("div.toolbar").html('<button type="button" class="btn btn-primary mb-1 rounded" data-toggle="modal" data-target="#modalForm" onClick="createMode();"><i class="fa fa-plus-circle"></i> Create Page</button>');
    $("div.toolbar").css('float','left');
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    $('#submitForm').click(function(){
        $.ajax({
            method: "POST",
            url: submit_url,
            data: $('#inputForm').serialize(),
            dataType: "json"
        }).done(function( result ) {
            $("#feedBack").html('');
            $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
            if(result.success == 1){
                if($('#formMode').val() == 'create'){
                    $('#modalForm').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Page has been created.',
                        allowOutsideClick: false
                    });
                    $('#modalAction').modal('hide');
                    $('#userAction').trigger("reset");
                    t.row.add( result.form ).draw( false );
                }else{
                    $('#modalForm').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Page has been modified.',
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
    $('#inputForm').trigger("reset");
    $('#formMode').val('create');
    $('#modalForm .modal-title').html('Create Page');
    $('#modalForm').modal('show');
}

function modifyMode(pid)
{
    $('#modalForm .modal-title').html('Modify Page');
    $.ajax({
        method: "POST",
        url: info_url+pid,
        data: $('#inputForm').serialize(),
        dataType: "json"
    }).done(function( result ) {
        $("#feedBack").html('');
        $("input[name='"+result.response.csrfName+"']").val(result.response.csrfHash);
        if(result.success){
            $('#inputForm').trigger("reset");
            $('#formMode').val('modify');
            $('#formID').val(result.data.page_id);
            $('#formTitle').val(result.data.page_title);
            $('#formGroup').val(result.data.page_group);
            console.log(result.data.page_type);
            if(result.data.page_type == 1){
                $('#formContentHTML').html(atob(result.data.page_content));
            }else{
                $('#formContentEmbed').val(atob(result.data.page_content));
            }
            $('#formType').val(result.data.page_type);
            $('#formStatus').val(result.data.page_status);
            $('#modalForm').modal('show');
        }else{
            $.each(result.error, function(index, value) {
                $("#feedBack").append(`<div class="alert alert-danger" role="alert">${value}</div>`);
                $("#userAction input[name="+index+"]").addClass('border-warning');
            });
        }
    });

}


