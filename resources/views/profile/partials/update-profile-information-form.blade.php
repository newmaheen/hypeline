<section>

    <header>

        <h2 class="h5 fw-bold text-dark mb-2">
            {{ __('Profile Information') }}
        </h2>

        <p class="text-muted small mb-4">
            {{ __("Update your account's profile information and email address.") }}
        </p>

    </header>


    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>


    <form
        method="post"
        action="{{ route('profile.update') }}"
    >

        @csrf
        @method('patch')


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
                value="{{ old('name', $user->name) }}"
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
                value="{{ old('email', $user->email) }}"
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


            @if (
                $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
                && ! $user->hasVerifiedEmail()
            )

                <div class="mt-3">

                    <p class="small text-muted mb-2">

                        {{ __('Your email address is unverified.') }}

                    </p>


                    <button
                        form="send-verification"
                        type="submit"
                        class="btn btn-outline-dark btn-sm"
                    >
                        {{ __('Re-send Verification Email') }}
                    </button>


                    @if (session('status') === 'verification-link-sent')

                        <p class="text-success small mt-3 mb-0">

                            {{ __('A new verification link has been sent to your email address.') }}

                        </p>

                    @endif

                </div>

            @endif

        </div>


        {{-- Save Button --}}

        <div class="d-flex align-items-center gap-3">

            <button
                type="submit"
                class="btn btn-dark px-4"
            >
                {{ __('Save Changes') }}
            </button>


            @if (session('status') === 'profile-updated')

                <span class="text-success small fw-semibold">

                    {{ __('Saved successfully.') }}

                </span>

            @endif

        </div>


    </form>

</section>