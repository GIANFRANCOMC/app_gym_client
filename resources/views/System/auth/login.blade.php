<x-system-guest-layout>
    @php
        $ownerApp     = config("app.owner_app");
        $hasCompany   = isset($data->company) && !empty($data->company);
        $hasCompanies = isset($data->companies) && !empty($data->companies) && count($data->companies) > 0;
    @endphp

    <style>
        body {
            background-color: #f8f6fb;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23e0dce4' fill-opacity='0.18' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
    </style>

    <div class="container-fluid min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 960px;">
            <div class="col-lg-6 d-none d-lg-flex bg-light align-items-center justify-content-center p-3">
                @if($hasCompany)
                    <img src="{{ asset($data->company->login_image ? 'storage/'.$data->company->login_image : $ownerApp->assets->img->login_image) }}" class="img-fluid" style="max-height: 400px;">
                @else
                    <img src="{{ asset($ownerApp->assets->img->login_image) }}" class="img-fluid" style="max-height: 400px;">
                @endif
            </div>
            <div class="col-12 col-lg-6 bg-white px-3 px-md-5 py-4 py-md-5">
                <div class="border-0">
                    <div class="text-end mb-4">
                        <a href="javascript:void(0)">
                            <img src="{{ asset($data->company->logotype ? 'storage/'.$data->company->logotype : $ownerApp->assets->img->logotype) }}" class="img-fluid border rounded-pill border px-2" style="max-height: 80px;">
                        </a>
                    </div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                        <span class="fs-4 text-center mb-1 fw-semibold">
                            @if($hasCompany)
                                Bienvenido a <span class="fw-bold fs-3 text-dark">{{ $data->company->commercial_name }}</span> 👋
                            @else
                                Bienvenido a <span class="fw-bold fs-3 text-dark">{{ $ownerApp->commercial_name }}</span> 👋
                            @endif
                        </span>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label colon-at-end fw-semibold">Correo electrónico</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese el correo electrónico" :value="old('email')" required autofocus autocomplete="username"/>
                            @if ($errors->get('email'))
                                @foreach((array) $errors->get('email') as $message)
                                    <small class="text-danger">{{ $message }}</small>
                                @endforeach
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label colon-at-end fw-semibold">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="•••••••" required autocomplete="current-password"/>
                            @if ($errors->get('password'))
                                @foreach((array) $errors->get('password') as $message)
                                    <small class="text-danger">{{ $message }}</small>
                                @endforeach
                            @endif
                        </div>
                        @if($hasCompany)
                            <div class="mb-3 d-none">
                                <label for="company_id" class="form-label colon-at-end fw-semibold">Empresa</label>
                                <select class="form-control" id="company_id" name="company_id" :value="old('company_id')" required>
                                    <option value="{{ $data->company->id }}" selected>{{ $data->company->commercial_name }}</option>
                                </select>
                                @if($errors->get('company_id'))
                                    @foreach ((array) $errors->get('company_id') as $message)
                                        <small class="text-danger">{{ $message }}</small>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        @if($hasCompanies)
                            <div class="mb-3">
                                <label for="company_id" class="form-label colon-at-end fw-semibold">Empresa</label>
                                <select class="form-control" id="company_id" name="company_id" :value="old('company_id')" required>
                                    <option value="">Seleccione una empresa</option>
                                    @foreach($data->companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->commercial_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->get('company_id'))
                                    @foreach((array) $errors->get('company_id') as $message)
                                        <small class="text-danger">{{ $message }}</small>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        <div class="mb-1">
                            <div class="cf-turnstile" data-sitekey="{{ config("app.CAPTCHA_KEY_FRONTEND") }}" data-size="flexible"></div>
                            @if($errors->get('captcha'))
                                @foreach((array) $errors->get('captcha') as $message)
                                    <small class="text-danger">{{ $message }}</small>
                                @endforeach
                            @endif
                        </div>
                        @if($hasCompany || $hasCompanies)
                            <div class="d-flex justify-content-center align-items-center flex-wrap">
                                <button class="btn btn-primary waves-effect w-100" type="submit">
                                    <i class="fa fa-sign-in"></i>
                                    <span class="ms-2">Iniciar sesión</span>
                                </button>
                            </div>
                        @else
                            <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                                <span class="alert-icon rounded">
                                    <i class="ti ti-ban"></i>
                                </span>
                                <span class="ms-3">
                                    Actualice la <b>membresía</b> para acceder
                                </span>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="text-center mt-3 mt-md-4">
            <small class="text-muted">
                ¿Necesitas ayuda? Escríbenos ✉️
                <a href="mailto:{{ $ownerApp->support->email }}" class="text-primary">
                    {{ $ownerApp->support->email }}
                </a>
                o llámanos 📞
                <a href="tel:{{ $ownerApp->support->phone }}" class="text-primary">
                    {{ $ownerApp->support->phone }}
                </a>
            </small>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.querySelector("form");

            form.addEventListener("submit", function(e) {

                const captchaResponse = document.querySelector(`input[name="cf-turnstile-response"]`);

                if(!captchaResponse || captchaResponse?.value === "") {

                    e.preventDefault();

                    Swal.fire({
                        icon              : "warning",
                        allowOutsideClick : false,
		                allowEscapeKey    : false,
                        html              : `<span class="d-block fw-bold">Captcha requerido</span> <span class="d-block mt-2">Por favor, completa el captcha para continuar.</span>`,
                        confirmButtonText : "Entendido",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect"
                    }});

                }

            });

        });
    </script>

</x-system-guest-layout>
