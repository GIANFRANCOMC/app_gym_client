<!-- resources/views/customers/index.blade.php -->
@extends('admins/layouts/main')

@section('title', 'Listado de Clientes')

@section('content')
    <h4 class="py-2 mb-4">
        <span class="text-muted fw-light">
            <i class="fa fa-home fa-2xs"></i> /
        </span>
        <span class="text-uppercase">
            CLIENTES
        </span>
    </h4>
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <p class="fw-bold">
                <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Nombres, Apellidos." role="button"></i>
                <span>Buscar:</span>
            </p>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Ingrese la búsqueda">
                <button class="btn btn-primary waves-effect" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div class="align-self-end ms-3">
            <button class="btn btn-primary">
                <i class="fa fa-plus me-1"></i>
                <span>Agregar</span>
            </button>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                <tr class="text-nowrap">
                    <th class="fw-bold text-center col-1">Documento de identidad</th>
                    <th class="fw-bold text-center col-3">Nombres</th>
                    <th class="fw-bold text-center col-3">Apellidos</th>
                    <th class="fw-bold text-center col-1">Fecha de Nacimiento</th>
                    <th class="fw-bold text-center col-1">Estado</th>
                    <th class="fw-bold text-center col-1">Acciones</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <th scope="row">71883137</th>
                        <td>Gianfranco</td>
                        <td>Mejía Carhuajulca</td>
                        <td>29-01-1999 (24 años)</td>
                        <td class="text-center">
                            <span class="badge bg-label-primary me-1">Activo</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm rounded-pill btn-danger waves-effect waves-light">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation" class="mt-3">
            <ul class="pagination">
                <li class="page-item prev">
                    <a class="page-link waves-effect" href="javascript:void(0);">
                        <i class="ti ti-chevrons-left ti-xs"></i>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link waves-effect" href="javascript:void(0);">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link waves-effect" href="javascript:void(0);">2</a>
                </li>
                <li class="page-item active">
                    <a class="page-link waves-effect" href="javascript:void(0);">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link waves-effect" href="javascript:void(0);">4</a>
                </li>
                <li class="page-item">
                    <a class="page-link waves-effect" href="javascript:void(0);">5</a>
                </li>
                <li class="page-item next">
                    <a class="page-link waves-effect" href="javascript:void(0);">
                        <i class="ti ti-chevrons-right ti-xs"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

@endsection
