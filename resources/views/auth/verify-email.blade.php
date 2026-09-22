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
                        Verify your email address
                    </p>

                </div>


                {{-- Verification Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <div class="text-center">

                            <div
                                class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle bg-light"
                                style="width: 70px; height: 70px;"
                            >
                                <span class="fs-2">
                                    ✉
                                </span>
                            </div>


                            <h2 class="h5 fw-bold text-dark mb-3">
                                {{ __('Check Your Email') }}
                            </h2>


                            <p class="text-muted small mb-4">
                                {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
                            </p>


                            {{-- Verification Success --}}

                            @if (session('status') == 'verification-link-sent')

                                <div class="alert alert-success small mb-4">

                                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}

                                </div>

                            @endif


                            {{-- Resend Verification --}}

                            <form
                                method="POST"
                                action="{{ route('verification.send') }}"
                                class="mb-3"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-dark w-100 py-2"
                                >
                                    {{ __('Resend Verification Email') }}
                                </button>

                            </form>


                            {{-- Logout --}}

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-secondary w-100 py-2"
                                >
                                    {{ __('Log Out') }}
                                </button>

                            </form>

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