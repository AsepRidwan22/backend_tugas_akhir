@extends('layouts.dashboard_master')
@section('title')
Halaman Category
@endsection
@php
$dataTable = true;
$create = true;
$edit = true;
$delete = true;
$forms = ['No', 'Name', 'Slug'];
@endphp
@section('form')
@include('admin.categories.form')
@endsection
@section('dataTable')
<script type="text/javascript">
    const url = "{{ route('admin.category.index') }}";
        const modalTitle = 'Category';
        const forms = ['name', 'slug'];
        const dataColumn = [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'slug',
                name: 'slug'
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


@push('scripts')
<script>
    $('#name').on('change', function() {
        $.ajax({
            url: "{{ route('admin.category.checkSlug') }}",
            method: 'GET',
            data: { name: $(this).val() },
            success: function(data) {
            $('#slug').val(data.slug);
            }
        });
    });
</script>
@endpush
