@if( $errors->all() )
    <div class="alert alert-danger">
        <p><strong>Có lỗi phát sinh.</strong></p>
        @foreach ($errors->all('<p>:message</p>') as $msg)
            {{ $msg }}
        @endforeach
    </div>
@endif

@if( $success->all() )
    <div class="alert alert-success">
        <p><strong>Success!</strong></p>
        @foreach ($success->all('<p>:message</p>') as $msg)
            {{ $msg }}
        @endforeach
    </div>
@endif