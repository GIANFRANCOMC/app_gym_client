<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="mb-3">
        @csrf
        <div>
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese correo electrónico" :value="old('email')" required autofocus autocomplete="username"/>
            @if ($errors->get('email'))
                <ul>
                    @foreach ((array) $errors->get('email') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div>
            <div class="d-flex justify-content-between mt-3">
                <label for="email" class="form-label">Contraseña</label>
                    @if (Route::has('password.request'))
                        {{-- <a href="{{ route('password.request') }}">
                            <small>¿Has olvidado tu contraseña?</small>
                        </a> --}}
                    @endif
            </div>
            <div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese contraseña" required autocomplete="current-password"/>
                @if ($errors->get('password'))
                    <ul>
                        @foreach ((array) $errors->get('password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> --}}
        <div class="flex items-center justify-end mt-4">
            <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Iniciar sesión</button>
            </div>
        </div>
    </form>
</x-guest-layout>
