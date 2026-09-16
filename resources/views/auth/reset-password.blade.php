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
                        Create a new password
                    </p>

                </div>


                {{-- Reset Password Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <form
                            method="POST"
                            action="{{ route('password.store') }}"
                        >

                            @csrf


                            {{-- Password Reset Token --}}

                            <input
                                type="hidden"
                                name="token"
                                value="{{ $request->route('token') }}"
                            >


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
                                    value="{{ old('email', $request->email) }}"
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


                            {{-- New Password --}}

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
                                    autocomplete="new-password"
                                >

                                @if($errors->get('password'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('password') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


                            {{-- Confirm Password --}}

                            <div class="mb-4">

                                <label
                                    for="password_confirmation"
                                    class="form-label fw-semibold"
                                >
                                    {{ __('Confirm Password') }}
                                </label>

                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control"
                                    required
                                    autocomplete="new-password"
                                >

                                @if($errors->get('password_confirmation'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('password_confirmation') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


                            {{-- Reset Button --}}

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-dark py-2"
                                >
                                    {{ __('Reset Password') }}
                                </button>

                            </div>


                        </form>


                        {{-- Back to Login --}}

                        <hr class="my-4">

                        <div class="text-center">

                            <a
                                href="{{ route('login') }}"
                                class="text-dark fw-semibold small text-decoration-none"
                            >
                                ← Back to Login
                            </a>

                        </div>

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