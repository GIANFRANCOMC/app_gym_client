<x-system-guest-layout>
    @php
        $hasCompany   = isset($data->company) && !empty($data->company);
        $hasCompanies = isset($data->companies) && !empty($data->companies) && count($data->companies) > 0;
    @endphp
    <div class="d-flex flex-column justify-content-center">
        @if ($hasCompany)
            <span class="h5 text-center fw-semibold mb-2">
                Bienvenido a
            </span>
            <span class="h3 text-center fw-bold mb-4">
                {{ $data->company->commercial_name }}
            </span>
        @else
            <span class="text-center mb-3">
                <img src="{{ asset('System/assets/img/utils/admin/6_.png') }}" class="img-fluid" width="70%"/>
            </span>
            {{-- <span class="h4 text-center fw-semibold mb-2">
                Bienvenido
            </span> --}}
        @endif
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email" class="form-label colon-at-end fw-semibold">Correo electrónico</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="usuario@hotmail.com" :value="old('email')" required autofocus autocomplete="username"/>
            @if ($errors->get('email'))
                @foreach ((array) $errors->get('email') as $message)
                    <small class="text-danger">{{ $message }}</small>
                @endforeach
            @endif
        </div>
        <div class="mt-3">
            <label for="password" class="form-label colon-at-end fw-semibold">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="•••••••" required autocomplete="current-password"/>
            @if ($errors->get('password'))
                @foreach ((array) $errors->get('password') as $message)
                    <small class="text-danger">{{ $message }}</small>
                @endforeach
            @endif
        </div>
        @if ($hasCompany)
            <div class="mt-3 d-none">
                <label for="company_id" class="form-label colon-at-end fw-semibold">Empresa</label>
                <select class="form-control" id="company_id" name="company_id" :value="old('company_id')" required>
                    <option value="{{ $data->company->id }}" selected>{{ $data->company->commercial_name }}</option>
                </select>
                @if ($errors->get('company_id'))
                    @foreach ((array) $errors->get('company_id') as $message)
                        <small class="text-danger">{{ $message }}</small>
                    @endforeach
                @endif
            </div>
        @endif
        @if ($hasCompanies)
            <div class="mt-3">
                <label for="company_id" class="form-label colon-at-end">Empresa</label>
                <select class="form-control" id="company_id" name="company_id" :value="old('company_id')" required>
                    <option value="">Seleccione</option>
                    @foreach ($data->companies as $company)
                        <option value="{{ $company->id }}">{{ $company->commercial_name }}</option>
                    @endforeach
                </select>
                @if ($errors->get('company_id'))
                    @foreach ((array) $errors->get('company_id') as $message)
                        <small class="text-danger">{{ $message }}</small>
                    @endforeach
                @endif
            </div>
        @endif
        @if ($hasCompany || $hasCompanies)
            <div class="flex items-center justify-end mt-4">
                <div class="mb-3">
                    <button class="btn btn-primary waves-effect w-100" type="submit">
                        <i class="fa fa-sign-in"></i>
                        <span class="ms-2">Iniciar sesión</span>
                    </button>
                </div>
            </div>
        @else
            <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                <span class="alert-icon rounded">
                    <i class="ti ti-ban"></i>
                </span>
                <span class="ms-3">
                    Actualice la <b>suscripción</b> para acceder
                </span>
            </div>
        @endif
        <div style="display: block; flex-flow: row;">
            <div class="cf-turnstile" data-sitekey="0x4AAAAAABD52_nxF5GTrXNM" data-size="flexible"></div>
        </div>
        @if ($errors->get('captcha'))
            @foreach ((array) $errors->get('captcha') as $message)
                <small class="text-danger">{{ $message }}</small>
            @endforeach
        @endif
        @if ($hasCompany)
            <div class="d-flex justify-content-center mt-3">
                @foreach ($data->company->socialsMedia as $socialMedia)
                    @switch ($socialMedia->type)
                        @case ("facebook")
                            <a href="{{ $socialMedia->link }}" class="btn btn-icon btn-label-info me-3" target="_blank">
                                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
                            </a>
                            @break

                        @case ("instagram")
                            <a href="{{ $socialMedia->link }}" class="btn btn-icon btn-label-danger me-3" target="_blank">
                                <i class="tf-icons fa-brands fa-instagram fs-5"></i>
                            </a>
                            @break

                        @case ("whatsapp")
                            <a href="{{ $socialMedia->link }}" class="btn btn-icon btn-label-success me-3" target="_blank">
                                <i class="tf-icons fa-brands fa-whatsapp fs-5"></i>
                            </a>
                            @break

                        @default
                            <!---->
                    @endswitch
                @endforeach
            </div>
        @endif
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.querySelector("form");

            form.addEventListener("submit", function(e) {

                const captchaResponse = document.querySelector('input[name="cf-turnstile-response"]');

                if(!captchaResponse || captchaResponse.value === "") {

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
