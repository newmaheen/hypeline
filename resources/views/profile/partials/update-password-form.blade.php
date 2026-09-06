<section>

    <header>

        <h2 class="h5 fw-bold text-dark mb-2">
            {{ __('Update Password') }}
        </h2>

        <p class="text-muted small mb-4">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>

    </header>


    <form
        method="post"
        action="{{ route('password.update') }}"
    >

        @csrf
        @method('put')


        {{-- Current Password --}}

        <div class="mb-4">

            <label
                for="update_password_current_password"
                class="form-label fw-semibold"
            >
                {{ __('Current Password') }}
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="form-control"
                autocomplete="current-password"
            >

            @if($errors->updatePassword->get('current_password'))

                <div class="text-danger small mt-2">

                    @foreach($errors->updatePassword->get('current_password') as $error)

                        <div>{{ $error }}</div>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- New Password --}}

        <div class="mb-4">

            <label
                for="update_password_password"
                class="form-label fw-semibold"
            >
                {{ __('New Password') }}
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                class="form-control"
                autocomplete="new-password"
            >

            @if($errors->updatePassword->get('password'))

                <div class="text-danger small mt-2">

                    @foreach($errors->updatePassword->get('password') as $error)

                        <div>{{ $error }}</div>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- Confirm Password --}}

        <div class="mb-4">

            <label
                for="update_password_password_confirmation"
                class="form-label fw-semibold"
            >
                {{ __('Confirm Password') }}
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control"
                autocomplete="new-password"
            >

            @if($errors->updatePassword->get('password_confirmation'))

                <div class="text-danger small mt-2">

                    @foreach($errors->updatePassword->get('password_confirmation') as $error)

                        <div>{{ $error }}</div>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- Save Button --}}

        <div class="d-flex align-items-center gap-3">

            <button
                type="submit"
                class="btn btn-dark px-4"
            >
                {{ __('Update Password') }}
            </button>


            @if (session('status') === 'password-updated')

                <span class="text-success small fw-semibold">

                    {{ __('Password updated successfully.') }}

                </span>

            @endif

        </div>


    </form>

</section>