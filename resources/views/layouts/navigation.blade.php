<nav class="hypeline-navbar">

    <div class="container">

        <div class="hypeline-navbar-inner">

            {{-- LOGO --}}

            <a href="{{ url('/') }}" class="hypeline-logo">

                <img
                    src="{{ asset('images/hypeline-logo-2.png') }}"
                    alt="Hypeline"
                    class="hypeline-logo-image"
                >

                <span class="hypeline-logo-text">
                    HYPELINE
                </span>

            </a>


            {{-- MOBILE MENU BUTTON --}}

            <button
                type="button"
                class="hypeline-menu-button"
                id="hypelineMenuButton"
                aria-controls="hypelineMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>


            {{-- NAVIGATION MENU --}}

            <div class="hypeline-menu" id="hypelineMenu">


                {{-- LEFT SIDE LINKS --}}

                <div class="hypeline-menu-links">

                    {{-- HOME --}}

                    <a href="{{ url('/') }}">
                        Home
                    </a>


                    {{-- SHOP 

                    <a href="{{ url('/shop') }}">
                        Shop
                    </a>
                    --}}

                    {{-- CATEGORIES --}}

                    @php
                        $navbarCategories = \App\Models\Category::where('is_active', true)
                            ->orderBy('name')
                            ->get();
                    @endphp

                    @foreach($navbarCategories as $category)

                        <a href="{{ route('category.products', $category->slug) }}">
                            {{ $category->name }}
                        </a>

                    @endforeach


                    {{-- MY ORDERS --}}

                    @auth

                        <a href="{{ route('orders.index') }}">
                            My Orders
                        </a>

                    @endauth

                </div>


                {{-- RIGHT SIDE AUTH LINKS --}}

                <div class="hypeline-menu-auth">

                    @guest

                        <a
                            href="{{ route('login') }}"
                            class="hypeline-login-button"
                        >
                            Login
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="hypeline-register-button"
                        >
                            Register
                        </a>

                    @else

                        <a
                            href="{{ route('profile.edit') }}"
                            class="hypeline-profile-button"
                        >
                            Profile
                        </a>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                            class="hypeline-logout-form"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="hypeline-logout-button"
                            >
                                Logout
                            </button>

                        </form>

                    @endguest

                </div>

            </div>

        </div>

    </div>

</nav>



<style>

/* =========================================================
   HYPELINE NAVBAR
========================================================= */

.hypeline-navbar {

    background: #ffffff;

    border-bottom: 1px solid #eeeeee;

    width: 100%;

    position: relative;

    z-index: 9999;

}


.hypeline-navbar .container {

    width: 100%;

    max-width: 1320px;

    margin-left: auto;

    margin-right: auto;

    padding-left: 24px;

    padding-right: 24px;

}


/* =========================================================
   MAIN NAVBAR ROW
========================================================= */

.hypeline-navbar-inner {

    min-height: 78px;

    display: flex;

    align-items: center;

    position: relative;

}


/* =========================================================
   LOGO
========================================================= */

.hypeline-logo {

    display: inline-flex;

    align-items: center;

    text-decoration: none !important;

    line-height: 1;

    white-space: nowrap;

    gap: 10px;

    flex-shrink: 0;

}


.hypeline-logo-image {

    width: 125px;

    height: auto;

    max-height: 45px;

    object-fit: contain;

    object-position: left center;

    display: block;

}


.hypeline-logo-text {

    color: #111111 !important;

    font-size: 22px;

    font-weight: 800;

    letter-spacing: 2px;

    line-height: 1;

    display: inline-block;

}


/* =========================================================
   DESKTOP MENU
========================================================= */

.hypeline-menu {

    flex: 1;

    margin-left: 70px;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


/* =========================================================
   LEFT MENU LINKS
========================================================= */

.hypeline-menu-links {

    display: flex;

    align-items: center;

    gap: 34px;

}


.hypeline-menu-links a {

    color: #111111 !important;

    text-decoration: none !important;

    font-size: 15px;

    font-weight: 600;

    transition: 0.2s ease;

}


.hypeline-menu-links a:hover {

    color: #666666 !important;

}


/* =========================================================
   RIGHT AUTH AREA
========================================================= */

.hypeline-menu-auth {

    display: flex;

    align-items: center;

    gap: 12px;

}


/* =========================================================
   LOGIN
========================================================= */

.hypeline-login-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 42px;

    padding: 0 20px;

    border: 1px solid #111111;

    border-radius: 6px;

    background: #ffffff !important;

    color: #111111 !important;

    text-decoration: none !important;

    font-size: 14px;

    font-weight: 600;

    transition: 0.2s ease;

}


.hypeline-login-button:hover {

    background: #111111 !important;

    color: #ffffff !important;

}


/* =========================================================
   REGISTER
========================================================= */

.hypeline-register-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 42px;

    padding: 0 20px;

    border: 1px solid #111111 !important;

    border-radius: 6px;

    background-color: #111111 !important;

    background: #111111 !important;

    color: #ffffff !important;

    text-decoration: none !important;

    font-size: 14px;

    font-weight: 600;

    line-height: 1;

    opacity: 1 !important;

    transition: 0.2s ease;

}


.hypeline-register-button:link,

.hypeline-register-button:visited {

    color: #ffffff !important;

}


.hypeline-register-button:hover {

    background-color: #333333 !important;

    background: #333333 !important;

    color: #ffffff !important;

}


.hypeline-register-button:focus,

.hypeline-register-button:active {

    background-color: #111111 !important;

    background: #111111 !important;

    color: #ffffff !important;

    box-shadow: none !important;

}


/* =========================================================
   PROFILE
========================================================= */

.hypeline-profile-button {

    color: #111111 !important;

    text-decoration: none !important;

    font-size: 14px;

    font-weight: 600;

}


.hypeline-profile-button:hover {

    color: #666666 !important;

}


/* =========================================================
   LOGOUT
========================================================= */

.hypeline-logout-form {

    margin: 0;

    padding: 0;

}


.hypeline-logout-button {

    border: 0;

    background: transparent !important;

    color: #111111 !important;

    padding: 8px 0;

    font-size: 14px;

    font-weight: 600;

    cursor: pointer;

}


.hypeline-logout-button:hover {

    color: #666666 !important;

}


/* =========================================================
   MOBILE MENU BUTTON
========================================================= */

.hypeline-menu-button {

    display: none;

    width: 42px;

    height: 42px;

    padding: 7px;

    margin-left: auto;

    border: 1px solid #111111;

    border-radius: 6px;

    background: #ffffff;

    cursor: pointer;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    gap: 5px;

}


.hypeline-menu-button span {

    display: block;

    width: 22px;

    height: 2px;

    background: #111111;

    border-radius: 2px;

    transition: 0.2s ease;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 991px) {

    .hypeline-navbar-inner {

        min-height: 70px;

        flex-wrap: wrap;

    }


    /* Logo */

    .hypeline-logo-image {

        width: 115px;

        max-height: 40px;

    }


    .hypeline-logo-text {

        font-size: 20px;

        letter-spacing: 1.5px;

    }


    .hypeline-logo {

        gap: 8px;

    }


    /* 3 line button */

    .hypeline-menu-button {

        display: flex;

    }


    /* Mobile menu */

    .hypeline-menu {

        display: none;

        width: 100%;

        flex: none;

        margin-left: 0;

        margin-top: 15px;

        padding-top: 18px;

        padding-bottom: 18px;

        border-top: 1px solid #eeeeee;

        background: #ffffff;

    }


    .hypeline-menu.hypeline-menu-open {

        display: block;

    }


    /* Left links */

    .hypeline-menu-links {

        display: flex;

        flex-direction: column;

        align-items: flex-start;

        gap: 0;

        width: 100%;

    }


    .hypeline-menu-links a {

        display: block;

        width: 100%;

        padding: 11px 0;

        font-size: 15px;

    }


    /* Auth */

    .hypeline-menu-auth {

        display: flex;

        flex-direction: column;

        align-items: stretch;

        width: 100%;

        gap: 10px;

        margin-top: 10px;

        padding-top: 15px;

        border-top: 1px solid #eeeeee;

    }


    .hypeline-login-button,

    .hypeline-register-button {

        width: 100%;

        min-height: 44px;

    }


    .hypeline-profile-button {

        display: block;

        width: 100%;

        padding: 11px 0;

    }


    .hypeline-logout-form {

        width: 100%;

    }


    .hypeline-logout-button {

        width: 100%;

        text-align: left;

        padding: 11px 0;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .hypeline-logo-image {

        width: 105px;

        max-height: 36px;

    }


    .hypeline-logo-text {

        font-size: 18px;

        letter-spacing: 1.2px;

    }


    .hypeline-logo {

        gap: 7px;

    }


    .hypeline-menu-button {

        width: 40px;

        height: 40px;

    }

}


/* =========================================================
   MOBILE BUTTON OPEN ANIMATION
========================================================= */

.hypeline-menu-button.is-open span:nth-child(1) {

    transform: translateY(7px) rotate(45deg);

}


.hypeline-menu-button.is-open span:nth-child(2) {

    opacity: 0;

}


.hypeline-menu-button.is-open span:nth-child(3) {

    transform: translateY(-7px) rotate(-45deg);

}

</style>



<script>

document.addEventListener('DOMContentLoaded', function () {

    const menuButton =
        document.getElementById('hypelineMenuButton');

    const menu =
        document.getElementById('hypelineMenu');


    if (!menuButton || !menu) {

        return;

    }


    /* =====================================================
       OPEN / CLOSE MOBILE MENU
    ===================================================== */

    menuButton.addEventListener('click', function () {

        const isOpen =
            menu.classList.toggle('hypeline-menu-open');

        menuButton.classList.toggle('is-open', isOpen);

        menuButton.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );

    });


    /* =====================================================
       CLOSE MENU AFTER CLICKING A LINK
    ===================================================== */

    const menuLinks =
        menu.querySelectorAll('a');


    menuLinks.forEach(function (link) {

        link.addEventListener('click', function () {

            if (window.innerWidth <= 991) {

                menu.classList.remove(
                    'hypeline-menu-open'
                );

                menuButton.classList.remove(
                    'is-open'
                );

                menuButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        });

    });


    /* =====================================================
       IF SCREEN CHANGES TO DESKTOP
    ===================================================== */

    window.addEventListener('resize', function () {

        if (window.innerWidth > 991) {

            menu.classList.remove(
                'hypeline-menu-open'
            );

            menuButton.classList.remove(
                'is-open'
            );

            menuButton.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    });

});

</script>

