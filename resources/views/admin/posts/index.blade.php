@extends('layouts.dashboard_master')
@section('title')
Halaman Postingan
@endsection
@php
$dataTable = true;
$create = true;
$edit = true;
$delete = true;
$forms = ['No', 'Title', 'Category', 'Slug', 'Image'];
@endphp
@section('form')
@include('admin.posts.form')
@endsection
@section('dataTable')
<script type="text/javascript">
    const url = "{{ route('admin.post.index') }}";
        const modalTitle = 'Post';
        const forms = ['title', 'image', 'slug', 'id_category', 'body'];
        const dataColumn = [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'category',
                name: 'category'
            },
            {
                data: 'image',
                name: 'image'
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

@section('customEdit')
if(e == 'body'){
let content = res.data[e];
let editor = document.getElementById("trix-editor").editor;
editor.loadHTML(content);
}
@yield('customEdit')
@endsection
@push('scripts')
<script>
    $('#title').on('change', function() {
        $.ajax({
            url: "{{ route('admin.post.checkSlug') }}",
            method: 'GET',
            data: { title: $(this).val() },
            success: function(data) {
            $('#slug').val(data.slug);
            }
        });
    });
</script>
@endpush
@push('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<style>
    html {
        .trix-button-row {
            .trix-button-group {
                border-color: #0f172a;

                .trix-button {
                    background-color: #94a3b8;
                    border-color: #0f172a;

                    &.trix-active {
                        background-color: lighten(#94a3b8, 10%);
                    }
                }
            }
        }

        .trix-content {
            width: 100%;
            min-height: 200px;
            background-color: #0f172a;
            border-color: #344155;
        }

        .trix-toolbar {
            max-width: 500px;
        }
    }
</style>
@endpush
