@extends("guests/layouts/main")

@section("content")
    <div class="d-flex justify-content-center">
        <a href="{{ route('login') }}" class="btn btn-primary waves-effect text-center">
            Login
        </a>
    </div>
@endsection
