@extends('admins/layouts/main')

@section('breadcrumbRoute')
<h4 class="py-2 mb-4">
    <span class="text-muted fw-light">
        <i class="fa fa-home fa-2xs"></i> /
    </span>
    <span class="text-uppercase">
        CLIENTES
    </span>
</h4>
@endsection

@section('content')
    <div id="app"></div>
    @vite('resources/js/vue/customers/main.js')
@endsection
