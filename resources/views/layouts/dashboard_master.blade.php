<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('template/src/assets/img/favicon.ico') }}" />
        <link href="{{ asset('template/layouts/collapsible-menu/css/light/loader.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/layouts/collapsible-menu/css/dark/loader.css') }}" rel="stylesheet"
            type="text/css" />
        <script src="{{ asset('template/layouts/collapsible-menu/loader.js') }}"></script>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="{{ asset('template/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('template/layouts/collapsible-menu/css/light/plugins.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/layouts/collapsible-menu/css/dark/plugins.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/src/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL STYLES -->
        <link rel="stylesheet" type="text/css"
            href="{{ asset('template/src/plugins/src/table/datatable/datatables.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('template/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('template/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">

        <link rel="stylesheet" href="{{ asset('template/src/plugins/src/sweetalerts2/sweetalerts2.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('template/src/plugins/css/light/table/datatable/custom_dt_miscellaneous.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('template/src/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css') }}">

        <link href="{{ asset('template/src/assets/css/light/components/modal.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/src/assets/css/dark/components/modal.css') }}" rel="stylesheet"
            type="text/css" />

        <link href="{{ asset('template/src/plugins/css/light/sweetalerts2/custom-sweetalert.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/src/plugins/css/dark/sweetalerts2/custom-sweetalert.css') }}" rel="stylesheet"
            type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <style>
            .modal-dialog {
                max-width: 800px;
                width: 100%;
            }
        </style>
        @stack('styles')
    </head>

    <body class="layout-boxed alt-menu">
        @php
        $routeName = Route::currentRouteName();
        @endphp
        <!-- BEGIN LOADER -->
        <div id="load_screen">
            <div class="loader">
                <div class="loader-content">
                    <div class="spinner-grow align-self-center"></div>
                </div>
            </div>
        </div>
        <!--  END LOADER -->

        <!--  BEGIN NAVBAR  -->
        @include('layouts.partials.admin_navbar')
        <!--  END NAVBAR  -->

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container sidebar-closed sidebar-closed" id="container">

            <div class="overlay"></div>
            <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            @include('layouts.partials.admin_sidebar')
            <!--  END SIDEBAR  -->

            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content">
                <div class="layout-px-spacing">
                    <div class="middle-content container-xxl p-0">
                        <!-- BREADCRUMB -->
                        <div class="page-meta">
                            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Datatables</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Basic</li>
                                </ol>
                            </nav>
                        </div>
                        <!-- /BREADCRUMB -->

                        @yield('content')

                        @isset($dataTable)
                        <div class="row layout-top-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content widget-content-area br-8">
                                        @isset($forms)
                                        <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                            <thead>
                                                @foreach ($forms as $form)
                                                <th>{{ $form }}</th>
                                                @endforeach
                                                <th class="no-content">Action</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endisset

                        <!--  BEGIN FOOTER  -->
                        @include('layouts.partials.admin_footer')
                        <!--  END FOOTER  -->
                    </div>
                    <!--  END CONTENT AREA  -->

                </div>
                <!-- END MAIN CONTAINER -->

                @if (isset($create) || isset($edit))
                <!-- Modal -->
                <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalHeading">Modal Title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <svg> ... </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="javascript:void(0)" id="form" enctype="multipart/form-data">
                                    <input type="hidden" name="_method" value="PUT">
                                    @yield('form')
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button class="btn" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>
                                    Discard</button>
                                {{ Form::submit('Submit', ['class' => 'btn btn-primary', 'id' => 'btnSave']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Modal -->
                @endisset

                <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
                <script src="{{ asset('template/src/plugins/src/global/vendors.min.js') }}"></script>
                <script src="{{ asset('template/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('template/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}">
                </script>
                <script src="{{ asset('template/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
                <script src="{{ asset('template/layouts/collapsible-menu/app.js') }}"></script>
                <script src="{{ asset('template/src/plugins/src/highlight/highlight.pack.js') }}"></script>
                <!-- END GLOBAL MANDATORY SCRIPTS -->

                <!-- BEGIN PAGE LEVEL SCRIPTS -->
                <script src="{{ asset('template/src/plugins/src/table/datatable/datatables.js') }}"></script>
                <script
                    src="{{ asset('template/src/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js') }}">
                </script>
                <script src="{{ asset('template/src/plugins/src/table/datatable/button-ext/jszip.min.js') }}"></script>
                <script src="{{ asset('template/src/plugins/src/table/datatable/button-ext/buttons.html5.min.js') }}">
                </script>
                <script src="{{ asset('template/src/plugins/src/table/datatable/button-ext/buttons.print.min.js') }}">
                </script>
                <script src="{{ asset('template/src/plugins/src/sweetalerts2/sweetalerts2.min.js') }}"></script>
                <!-- END PAGE LEVEL SCRIPTS -->
                @stack('scripts')

                @isset($dataTable)
                @yield('dataTable')
                <script type="text/javascript">
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })
                    $(document).ready(function() {
                        let methodType = '';
                        let urlUpdate = '';
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        let table = $('#zero-config').DataTable({
                            "dom": "<'dt--top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f>>>" +
                                "<'table-responsive'tr>" +
                                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                            "oLanguage": {
                                "oPaginate": {
                                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                                },
                                "sInfo": "Showing page _PAGE_ of _PAGES_",
                                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                                "sSearchPlaceholder": "Search...",
                                "sLengthMenu": "Results :  _MENU_",
                            },
                            "stripeClasses": [],
                            "lengthMenu": [7, 10, 20, 50],
                            "pageLength": 10,
                            processing: true,
                            serverSide: true,
                            ajax: url,
                            columns: dataColumn,
                            buttons: dataButton
                        });


                        @isset($create)
                            $('#btnCreate').click(function() {
                                clearModal('Tambah', 'POST');
                                forms.forEach(e => {
                                    let data = e.split(":");
                                    if(data[0] == 'relation'){
                                        $('#' + data[1]).val('');
                                    } else{
                                        $('#' + e).val('');
                                    }
                                });
                            });
                        @endisset

                        @isset($edit)
                            $('body').on('click', '.btnEdit', function() {
                                clearModal('Ubah', 'PUT');
                                let id = $(this).data('id');
                                urlUpdate = url + '/' + id;
                                $.get(url + '/' + id, function(res) {
                                    forms.forEach(e => {
                                        let data = e.split(":");
                                        if(data[0] == 'relation'){
                                            $('#' + data[1]).val(res.data[relationName][data[1]]);
                                        } else{
                                            if($('#' + e).attr('type') != 'file'){
                                                $('#' + e).val(res.data[e]);
                                            }
                                        }
                                        @yield('customEdit')
                                    });
                                })
                            });
                        @endisset

                        @if (isset($create) || isset($edit))
                            function clearModal(title, type){
                                methodType = type;
                                if ( $("input").hasClass("image-file") ) {
                                    $('#preview-image').attr('src', "{{ asset('template/src/assets/img/preview.png') }}");
                                }
                                $("input[name='_method']").val(methodType);
                                $('.invalid-feedback').text('').removeClass('invalid-feedback display-invalid');
                                $('.input-error').removeClass('input-error');
                                $('#btnSave').val('Submit');
                                $('#form').trigger('reset');
                                $('#modalHeading').html(title + ' ' + modalTitle);
                                $('#ajaxModal').modal('show');

                                @yield('clearModal')
                            }

                            $('.image-file').change(function(){
                                let reader = new FileReader();
                                reader.onload = (e) => {
                                    $('#preview-image').attr('src', e.target.result);
                                }
                                reader.readAsDataURL(this.files[0]);
                            });

                            $('#btnSave').on('click', (function(e) {
                                e.preventDefault();
                                if (methodType == 'POST') {
                                    urlSave = url;
                                } else if (methodType == 'PUT') {
                                    urlSave = urlUpdate;
                                }
                                $('#btnSave').text('Sending..', true);
                                let form = $('#form')[0];
                                $.ajax({
                                    data: new FormData(form),
                                    url: urlSave,
                                    type: 'POST',
                                    contentType: false,
                                    cache: false,
                                    processData:false,
                                    beforeSend: function() {
                                        $(document).find('div.error_text').text('');
                                        $('.input-error').removeClass('input-error');

                                    },
                                    success: function(res) {
                                        swalWithBootstrapButtons.fire(
                                            'Success!',
                                            res.message,
                                            'success'
                                        )
                                        $('#form').trigger("reset");
                                        $('#ajaxModal').modal('hide');
                                        table.draw();
                                        $('#btnSave').html('Save Changes');
                                    },
                                    error: function(res) {
                                        if (res.status == 400) {
                                            $.each(res.responseJSON.errors, function(prefix, val) {
                                                let error = $('div.' + prefix + '_error');
                                                let input = $('#' + prefix);
                                                error.text(val[0])
                                                error.addClass('invalid-feedback display-invalid');
                                                input.addClass('input-error');
                                            });
                                        } else {
                                            swalWithBootstrapButtons.fire(
                                                'Error!',
                                                res.responseJSON.message,
                                                'error'
                                            )
                                        }
                                        $('#btnSave').html('Save Changes');
                                    }
                                });
                            }));
                        @endif

                        @isset($delete)
                            $('body').on('click', '.btnDelete', function() {
                                let id = $(this).data("id");
                                deleteAjax(url + "/" + id);
                            });
                            $('body').on('click', '#btnDeleteAll', function() {
                                deleteAjax(url + "/delete/all");
                            });

                            function deleteAjax(urlDelete) {
                                Swal.fire({
                                    title: 'Apa kamu yakin?',
                                    text: "Anda tidak akan dapat mengembalikan ini!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya, hapus!',
                                    cancelButtonText: 'Tidak, batalkan!',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            type: "DELETE",
                                            url: urlDelete,
                                            success: function(res) {
                                                swalWithBootstrapButtons.fire(
                                                    'Success!',
                                                    res.message,
                                                    'success'
                                                )
                                                table.draw();
                                            },
                                            error: function(res) {
                                                swalWithBootstrapButtons.fire(
                                                    'Error!',
                                                    res.responseJSON.message,
                                                    'error'
                                                )
                                            }
                                        });
                                    } else if (
                                        result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                        swalWithBootstrapButtons.fire(
                                            'Dibatalkan',
                                            'Datamu aman :)',
                                            'error'
                                        )
                                    }
                                });
                            }
                        @endisset

                        @isset($status)
                            $('body').on('change', '.toggle-class', function() {
                                let status = $(this).prop('checked') == true ? 1 : 0;
                                var user_id = $(this).data('id');
                                $.ajax({
                                    type: "PUT",
                                    dataType: "json",
                                    url: url + '/status',
                                    data: {
                                        'status': status,
                                        'id': user_id
                                    },
                                    success: function(res) {
                                        swalWithBootstrapButtons.fire(
                                            'Success!',
                                            res.message,
                                            'success'
                                        )
                                    },
                                    error: function(res) {
                                        swalWithBootstrapButtons.fire(
                                            'Error!',
                                            res.responseJSON.message,
                                            'error'
                                        )
                                    }
                                });
                            });
                        @endisset

                        @if(isset($import) || isset($export))
                            $('body').on('click', '#btnImport', function() {
                                clearModalFile('Import');
                            });
                            $('body').on('click', '.btnExport', function() {
                                clearModalFile('Export');
                            });

                            function clearModalFile(title){
                                methodType = title;
                                $('.invalid-feedback').text('').removeClass('invalid-feedback display-invalid');
                                $('.input-error').removeClass('input-error');
                                $('#btnFileSave').val('Submit');
                                $('#fileForm').trigger('reset');
                                $('#modalFileHeading').html(title + ' ' + modalTitle);
                                $('#importForm').trigger("reset");
                                $('#fileModal').modal('show')
                            }

                            $('#btnFileSave').on('click', (function(e) {
                                e.preventDefault();
                                if (methodType == 'Import') {
                                    urlSave = url + '/import';
                                } else if (methodType == 'Export') {
                                    urlSave = url + '/export';
                                }
                                $('#btnFileSave').text('Sending..', true);
                                let form = $('#fileForm')[0];

                                $.ajax({
                                    data: new FormData(form),
                                    url: urlSave,
                                    type: 'POST',
                                    contentType: false,
                                    cache: false,
                                    processData:false,
                                    beforeSend: function() {
                                        $(document).find('div.error_text').text('');
                                        $('.input-error').removeClass('input-error');
                                    },
                                    success: function(res) {
                                        swalWithBootstrapButtons.fire(
                                            'Success!',
                                            res.message,
                                            'success'
                                        )
                                        $('#fileForm').trigger("reset");
                                        $('#fileModal').modal('hide');
                                        table.draw();
                                        $('#btnFileSave').html('Save Changes');
                                    },
                                    error: function(res) {
                                        if (res.status == 400) {
                                            $.each(res.responseJSON.errors, function(prefix, val) {
                                                let error = $('div.file_error');
                                                let input = $('input#file');
                                                error.text(val[0])
                                                error.addClass('invalid-feedback display-invalid');
                                                input.addClass('input-error');
                                            });
                                        } else {
                                            swalWithBootstrapButtons.fire(
                                                'Error!',
                                                res.responseJSON.message,
                                                'error'
                                            )
                                        }
                                        $('#btnFileSave').html('Save Changes');
                                    }
                                });
                            }));
                        @endif
                    });
                </script>
                @endisset
    </body>

</html>
