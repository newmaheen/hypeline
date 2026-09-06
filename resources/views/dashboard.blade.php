
<x-app-layout>

    <x-slot name="header">
        <div class="container">
            <h2 class="h4 mb-0 fw-bold text-dark">
                Hypeline Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="bg-light min-vh-100">

        <div class="container py-5">

            {{-- Welcome --}}
            <div class="mb-5">
                <h1 class="display-6 fw-bold text-dark">
                    Welcome back, {{ Auth::user()->name }}!
                </h1>

                <p class="text-muted fs-5">
                    Manage your shopping, orders and account from here.
                </p>
            </div>


            {{-- Quick Action Cards --}}
            <div class="row g-4">

                {{-- Shop --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card h-100 border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h3 class="h5 fw-bold">
                                Shop Products
                            </h3>

                            <p class="text-muted">
                                Browse our latest products and collections.
                            </p>

                            <a
                                href="{{ route('home') }}"
                                class="btn btn-dark w-100"
                            >
                                Shop Now
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Orders --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card h-100 border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h3 class="h5 fw-bold">
                                My Orders
                            </h3>

                            <p class="text-muted">
                                View your orders and track their status.
                            </p>

                            <a
                                href="{{ route('my-orders') }}"
                                class="btn btn-outline-dark w-100"
                            >
                                View Orders
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Profile --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card h-100 border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h3 class="h5 fw-bold">
                                My Profile
                            </h3>

                            <p class="text-muted">
                                Manage your personal account information.
                            </p>

                            <a
                                href="{{ route('profile.edit') }}"
                                class="btn btn-outline-dark w-100"
                            >
                                Manage Profile
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Cart --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card h-100 border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h3 class="h5 fw-bold">
                                My Cart
                            </h3>

                            <p class="text-muted">
                                Review the products waiting in your cart.
                            </p>

                            <a
                                href="{{ route('cart.index') }}"
                                class="btn btn-outline-dark w-100"
                            >
                                View Cart
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Bottom CTA --}}
            <div class="card border-0 shadow-sm mt-5">

                <div class="card-body text-center p-5">

                    <h2 class="fw-bold">
                        Ready to shop?
                    </h2>

                    <p class="text-muted mb-4">
                        Discover products from Hypeline and place your order today.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="btn btn-dark px-5 py-2"
                    >
                        Start Shopping
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>

