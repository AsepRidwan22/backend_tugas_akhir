@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"> <button type="button"
            class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg></button> <strong>Error!</strong>
        <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::get('success', false))
    @php $data = Session::get('success'); @endphp
    <div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert"> <button
            type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-x close" data-bs-dismiss="alert">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg></button> <strong>Success!</strong> Lorem Ipsum is simply dummy text of the printing.
        @if (is_array($data))
            <ul class="list-unstyled mb-0">
                @foreach ($data as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ $data }}
        @endif
    </div>
@endif
