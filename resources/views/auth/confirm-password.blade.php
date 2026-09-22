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
                        Confirm your password
                    </p>

                </div>


                {{-- Confirmation Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <p class="text-muted small mb-4">
                            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                        </p>


                        <form
                            method="POST"
                            action="{{ route('password.confirm') }}"
                        >

                            @csrf


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
                                    autofocus
                                >

                                @if($errors->get('password'))

                                    <div class="text-danger small mt-2">

                                        @foreach($errors->get('password') as $error)

                                            <div>{{ $error }}</div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>


                            {{-- Confirm Button --}}

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-dark py-2"
                                >
                                    {{ __('Confirm Password') }}
                                </button>

                            </div>


                        </form>


                        {{-- Back to Shop --}}

                        <hr class="my-4">

                        <div class="text-center">

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

        </div>

    </div>

</x-guest-layout>