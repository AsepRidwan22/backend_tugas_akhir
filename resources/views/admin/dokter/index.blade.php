@extends('layouts.dashboard_master')
@section('title')
    Halaman Dokter
@endsection
@php
    $dataTable = true;
    $create = true;
    $edit = true;
    $delete = true;
    $forms = ['No', 'Name', 'Email'];
@endphp
@section('form')
    @include('admin.dokter.form')
@endsection
@section('dataTable')
    <script type="text/javascript">
        const url = "{{ route('admin.dokter.index') }}";
        const modalTitle = 'Dokter';
        const forms = ['name', 'email', 'password'];
        const dataColumn = [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];
        const dataButton = [{
            text: 'Tambah',
            className: 'btn btn-secondary toggle-vis mb-1',
            attr: {
                id: 'btnCreate'
            }
        }, ];
    </script>
@endsection
