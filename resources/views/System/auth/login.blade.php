<x-system-guest-layout>
    @php
        $ownerApp     = config("app.owner_app");
        $hasCompany   = isset($data->company) && !empty($data->company);
        $hasCompanies = isset($data->companies) && !empty($data->companies) && count($data->companies) > 0;
    @endphp

    <div class="text-end mb-4">
        <a href="{{ $ownerApp->web }}" target="_blank">
            <img src="{{ asset($ownerApp->assets->img->logotype) }}" class="img-fluid border rounded-pill border px-2" width="30%"/>
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
