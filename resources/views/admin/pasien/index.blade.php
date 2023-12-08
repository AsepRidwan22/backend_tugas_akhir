@extends('layouts.dashboard_master')
@section('title')
Halaman Pasien
@endsection
@php
$dataTable = true;
$status = true;
$delete = true;
$forms = ['No', 'Name', 'Email', 'Status'];
@endphp
@section('dataTable')
<script type="text/javascript">
    const url = "{{ route('admin.post.index') }}";
        const modalTitle = 'Pasien';
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
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];
        dataButton = [];
</script>
@endsection
