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
                        Reset your password
                    </p>

                </div>


                {{-- Forgot Password Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">


                        <p class="text-muted small mb-4">
                            {{ __('Forgot your password? No problem. Enter your email address and we will send you a password reset link.') }}
                        </p>


                        {{-- Session Status --}}

                        <x-auth-session-status
                            class="mb-4"
                            :status="session('status')"
                        />


                        <form
                            method="POST"
                            action="{{ route('password.email') }}"
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
                                    autocomplete="email"
                                >

                                @if($errors->get('email'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('email') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


                            {{-- Send Button --}}

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-dark py-2"
                                >
                                    {{ __('Email Password Reset Link') }}
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