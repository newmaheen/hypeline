<x-guest-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-7 col-lg-5">

                {{-- Brand --}}

                <div class="text-center mb-4">

                    <h1 class="fw-bold text-dark mb-2">
                        HYPELINE
                    </h1>

                    <p class="text-muted mb-0">
                        Welcome back. Sign in to your account.
                    </p>

                </div>


                {{-- Login Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">


                        {{-- Session Status --}}

                        <x-auth-session-status
                            class="mb-4"
                            :status="session('status')"
                        />


                        <form
                            method="POST"
                            action="{{ route('login') }}"
                        >

                            @csrf


                            {{-- Email --}}

                            <div class="mb-4">

                                <label
                                    for="email"
                                    class="form-label fw-semibold"
                                >
                                    {{ __('Email') }}
                                </label>

                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >

                                @if($errors->get('email'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('email') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


                            {{-- Password --}}

                            <div class="mb-4">

                                <label
                                    for="password"
                                    class="form-label fw-semibold"
                                >
                                    {{ __('Password') }}
                                </label>

                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control"
                                    required
                                    autocomplete="current-password"
                                >

                                @if($errors->get('password'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('password') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


                            {{-- Remember Me --}}

                            <div class="mb-4">

                                <div class="form-check">

                                    <input
                                        id="remember_me"
                                        type="checkbox"
                                        class="form-check-input"
                                        name="remember"
                                    >

                                    <label
                                        for="remember_me"
                                        class="form-check-label text-muted small"
                                    >
                                        {{ __('Remember me') }}
                                    </label>

                                </div>

                            </div>


                            {{-- Login / Forgot Password --}}

                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">

                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-dark small text-decoration-none"
                                    >
                                        {{ __('Forgot your password?') }}
                                    </a>

                                @endif


                                <button
                                    type="submit"
                                    class="btn btn-dark px-4 w-100 w-sm-auto"
                                >
                                    {{ __('Log in') }}
                                </button>

                            </div>

                        </form>


                        {{-- Register --}}

                        @if (Route::has('register'))

                            <hr class="my-4">

                            <div class="text-center">

                                <span class="text-muted small">
                                    Don't have an account?
                                </span>

                                <a
                                    href="{{ route('register') }}"
                                    class="text-dark fw-semibold small text-decoration-none ms-1"
                                >
                                    Create Account
                                </a>

                            </div>

                        @endif


                    </div>

                </div>


                {{-- Back to Shop --}}

                <div class="text-center mt-4">

                    <a
                        href="{{ route('home') }}"
                        class="text-muted small text-decoration-none"
                    >
                        ← Back to Shop
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>