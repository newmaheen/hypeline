<footer class="bg-dark text-white mt-5">

    <div class="container py-5">

        <div class="row g-4">

            {{-- Brand --}}
            <div class="col-12 col-md-6 col-lg-4">

                <h3 class="fw-bold mb-3">
                    HYPELINE
                </h3>

                <p class="text-white-50 mb-0">
                    Style that speaks.
                    Quality products for your everyday lifestyle.
                </p>

            </div>


            {{-- Quick Links --}}
            <div class="col-6 col-md-3 col-lg-4">

                <h6 class="fw-bold mb-3">
                    QUICK LINKS
                </h6>

                <ul class="list-unstyled mb-0">

                    <li class="mb-2">
                        <a
                            href="{{ route('home') }}"
                            class="text-white-50 text-decoration-none"
                        >
                            Shop
                        </a>
                    </li>

                    <li class="mb-2">
                        <a
                            href="{{ route('cart.index') }}"
                            class="text-white-50 text-decoration-none"
                        >
                            Cart
                        </a>
                    </li>

                    @auth

                        <li class="mb-2">
                            <a
                                href="{{ route('my-orders') }}"
                                class="text-white-50 text-decoration-none"
                            >
                                My Orders
                            </a>
                        </li>

                    @endauth

                </ul>

            </div>


            {{-- Contact --}}
            <div class="col-6 col-md-3 col-lg-4">

                <h6 class="fw-bold mb-3">
                    CONTACT
                </h6>

                <p class="text-white-50 mb-2">
                    Bangladesh
                </p>

                <p class="text-white-50 mb-2">
                    +880 1348-061290
                </p>

                <p class="text-white-50 mb-0">
                    hypelinebd@gmail.com
                </p>

            </div>

        </div>

        <hr class="border-secondary my-4">

        <div class="text-center text-white-50 small">

            © {{ date('Y') }} HYPELINE. All rights reserved.

        </div>

    </div>

</footer>