<section>

    <header>

        <h2 class="h5 fw-bold text-dark mb-2">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-muted small mb-4">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

    </header>


    {{-- Delete Button --}}

    <button
        type="button"
        class="btn btn-outline-danger"
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Delete Account') }}
    </button>


    {{-- Confirmation Modal --}}

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >

        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="p-4"
        >

            @csrf
            @method('delete')


            <h2 class="h5 fw-bold text-dark mb-2">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>


            <p class="text-muted small mb-4">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>


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
                    placeholder="{{ __('Enter your password') }}"
                    autocomplete="current-password"
                >

                @if($errors->userDeletion->get('password'))

                    <div class="text-danger small mt-2">

                        @foreach($errors->userDeletion->get('password') as $error)

                            <div>{{ $error }}</div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- Buttons --}}

            <div class="d-flex justify-content-end gap-2">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    x-on:click="$dispatch('close')"
                >
                    {{ __('Cancel') }}
                </button>


                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    {{ __('Delete Account') }}
                </button>

            </div>


        </form>

    </x-modal>

</section>