<x-system-guest-layout>
    <div class="d-flex justify-content-center mb-3">
        <span class="h5 text-center fw-bold text-uppercase">
            {{ $data->company->commercial_name }}
        </span>
    </div>
    <form method="POST" action="{{ route('login') }}" class="mb-3">
        @csrf
        <div>
            <label for="email" class="form-label colon-at-end">Correo electrónico</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese correo electrónico" :value="old('email')" required autofocus autocomplete="username"/>
            @if ($errors->get('email'))
                @foreach ((array) $errors->get('email') as $message)
                    <small class="text-danger">{{ $message }}</small>
                @endforeach
            @endif
        </div>
        <div class="mt-3">
            <label for="password" class="form-label colon-at-end">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese contraseña" required autocomplete="current-password"/>
            @if ($errors->get('password'))
                @foreach ((array) $errors->get('password') as $message)
                    <small class="text-danger">{{ $message }}</small>
                @endforeach
            @endif
        </div>
        <div class="flex items-center justify-end mt-4">
            <div class="mb-3">
                <button class="btn btn-primary waves-effect w-100" type="submit">
                    <i class="fa fa-sign-in"></i>
                    <span class="ms-1">Iniciar sesión</span>
                </button>
            </div>
        </div>
    </form>
</x-system-guest-layout>
