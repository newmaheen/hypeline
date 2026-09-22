<x-app-layout>

    <x-slot name="header">

        <div class="container">

            <h2 class="h4 mb-0 fw-bold text-dark">
                Profile
            </h2>

        </div>

    </x-slot>


    <style>

        .profile-page {
            background: #f8f8f8;
            min-height: 100vh;
            padding: 45px 0 70px;
        }

        .profile-title {
            font-size: 32px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
        }

        .profile-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .profile-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 20px;
        }

        .profile-card-title {
            font-size: 19px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
        }

        .profile-card-description {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .profile-danger {
            border-color: #e5e5e5;
        }

        @media (max-width: 767px) {

            .profile-page {
                padding: 25px 0 50px;
            }

            .profile-title {
                font-size: 28px;
            }

            .profile-card {
                padding: 20px;
            }

        }

    </style>


    <div class="profile-page">

        <div class="container">


            {{-- Page Heading --}}

            <div class="mb-4">

                <h1 class="profile-title">
                    My Profile
                </h1>

                <p class="profile-subtitle mb-0">
                    Manage your account information, password and account settings.
                </p>

            </div>


            {{-- Profile Information --}}

            <div class="profile-card">

                <div class="profile-card-title">
                    Profile Information
                </div>

                <p class="profile-card-description">
                    Update your name and email address associated with your account.
                </p>

                @include('profile.partials.update-profile-information-form')

            </div>


            {{-- Password --}}

            <div class="profile-card">

                <div class="profile-card-title">
                    Update Password
                </div>

                <p class="profile-card-description">
                    Make sure your account is using a strong and secure password.
                </p>

                @include('profile.partials.update-password-form')

            </div>


            {{-- Delete Account --}}

            <div class="profile-card profile-danger">

                <div class="profile-card-title">
                    Delete Account
                </div>

                <p class="profile-card-description">
                    Permanently delete your account and all associated data.
                </p>

                @include('profile.partials.delete-user-form')

            </div>


        </div>

    </div>


    @include('layouts.footer')

</x-app-layout>