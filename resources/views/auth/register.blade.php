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
                        Create your account and start shopping.
                    </p>

                </div>


                {{-- Register Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <form
                            method="POST"
                            action="{{ route('register') }}"
                        >

                            @csrf


                            {{-- Name --}}

                            <div class="mb-4">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    {{ __('Name') }}
                                </label>

                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                >

                                @if($errors->get('name'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('name') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


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


                            {{-- Register Button --}}

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-dark py-2"
                                >
                                    {{ __('Create Account') }}
                                </button>

                            </div>


                        </form>


                        {{-- Login Link --}}

                        <hr class="my-4">

                        <div class="text-center">

                            <span class="text-muted small">
                                Already have an account?
                            </span>

                            <a
                                href="{{ route('login') }}"
                                class="text-dark fw-semibold small text-decoration-none ms-1"
                            >
                                Log in
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