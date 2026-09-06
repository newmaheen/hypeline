<nav class="hypeline-navbar">

    <div class="hypeline-nav-container">

        {{-- =========================
             HEADER
        ========================== --}}

        <div class="hypeline-header-row">

            {{-- Logo --}}
            <a
                href="{{ route('home') }}"
                class="hypeline-logo"
            >
                HYPELINE
            </a>


            {{-- Mobile Menu Button --}}
            <button
                type="button"
                class="hypeline-menu-button"
                data-bs-toggle="collapse"
                data-bs-target="#hypelineMenu"
                aria-controls="hypelineMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>

        </div>


        {{-- =========================
             COLLAPSIBLE MENU
        ========================== --}}

        <div
            class="collapse hypeline-menu"
            id="hypelineMenu"
        >

            {{-- Main Navigation --}}
            <div class="hypeline-main-links">

                <a href="{{ route('home') }}">
                    Shop
                </a>

                <a href="{{ route('cart.index') }}">
                    Cart
                </a>

                @auth

                    <a href="{{ route('my-orders') }}">
                        My Orders
                    </a>

                @endauth

            </div>


            {{-- Account Navigation --}}
            <div class="hypeline-account-links">

                @auth

                    {{-- Dashboard --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="hypeline-outline-button"
                    >
                        Dashboard
                    </a>


                    {{-- User Name --}}
                    <a
                        href="{{ route('profile.edit') }}"
                        class="hypeline-user"
                    >
                        {{ Auth::user()->name }}
                    </a>


                    {{-- Logout --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="hypeline-dark-button"
                        >
                            Logout
                        </button>

                    </form>

                @else

                    {{-- Login --}}
                    <a href="{{ route('login') }}">
                        Login
                    </a>


                    {{-- Register --}}
                    <a
                        href="{{ route('register') }}"
                        class="hypeline-dark-button"
                    >
                        Register
                    </a>

                @endauth

            </div>

        </div>

    </div>

</nav>


<style>

/* =====================================================
   HYPELINE NAVBAR
===================================================== */

.hypeline-navbar {
    width: 100%;
    background: #ffffff;
    border-bottom: 1px solid #e8e8e8;

    position: relative;
    z-index: 1000;
}


/* =====================================================
   CONTAINER
===================================================== */

.hypeline-nav-container {
    max-width: 1200px;
    margin: 0 auto;

    padding: 0 25px;

    min-height: 78px;

    display: flex;
    align-items: center;
}


/* =====================================================
   HEADER ROW
===================================================== */

.hypeline-header-row {
    display: flex;
    align-items: center;
}


/* =====================================================
   LOGO
===================================================== */

.hypeline-logo {
    color: #111111 !important;

    text-decoration: none !important;

    font-size: 25px;
    font-weight: 800;

    letter-spacing: -1px;

    white-space: nowrap;
}

.hypeline-logo:hover {
    color: #111111 !important;
}


/* =====================================================
   DESKTOP MENU
===================================================== */

.hypeline-menu {
    flex: 1;

    margin-left: 70px;

    display: flex;
    align-items: center;
    justify-content: space-between;
}


.hypeline-main-links,
.hypeline-account-links {
    display: flex;
    align-items: center;

    gap: 28px;
}


.hypeline-main-links a,
.hypeline-account-links a {
    color: #111111 !important;

    text-decoration: none !important;

    font-size: 14px;
    font-weight: 600;

    transition: .2s ease;
}


.hypeline-main-links a:hover,
.hypeline-account-links a:hover {
    color: #777777 !important;
}


/* =====================================================
   USER
===================================================== */

.hypeline-user {
    font-weight: 500 !important;
}


/* =====================================================
   DASHBOARD BUTTON
===================================================== */

.hypeline-outline-button {
    border: 1px solid #111111;

    padding: 9px 17px;

    border-radius: 3px;
}


.hypeline-outline-button:hover {
    background: #111111;

    color: #ffffff !important;
}


/* =====================================================
   DARK BUTTON
===================================================== */

.hypeline-dark-button {
    background: #111111 !important;

    color: #ffffff !important;

    border: 1px solid #111111;

    padding: 10px 19px;

    border-radius: 3px;

    font-size: 13px !important;
    font-weight: 700 !important;

    text-decoration: none !important;

    cursor: pointer;
}


.hypeline-dark-button:hover {
    background: #333333 !important;

    color: #ffffff !important;
}


/* =====================================================
   LOGOUT FORM
===================================================== */

.hypeline-account-links form {
    margin: 0;
}


/* =====================================================
   MOBILE BUTTON
===================================================== */

.hypeline-menu-button {
    display: none;

    width: 44px;
    height: 44px;

    padding: 8px;

    background: #ffffff;

    border: 1px solid #dddddd;

    border-radius: 4px;

    cursor: pointer;

    align-items: center;
    justify-content: center;

    flex-direction: column;
}


.hypeline-menu-button span {
    display: block;

    width: 24px;
    height: 2px;

    background: #111111;

    margin: 3px 0;
}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 991px) {


    /* Container */

    .hypeline-nav-container {
        display: block;

        padding: 13px 18px;

        min-height: auto;
    }


    /* Header */

    .hypeline-header-row {
        width: 100%;

        display: flex;

        align-items: center;

        justify-content: space-between;
    }


    /* Logo */

    .hypeline-logo {
        font-size: 23px;
    }


    /* Three Lines */

    .hypeline-menu-button {
        display: flex;
    }


    /*
    IMPORTANT:

    Bootstrap controls:
    .collapse
    .show
    .collapsing

    So we DO NOT use
    display:none/block here.
    */


    /* Menu */

    .hypeline-menu {
        width: 100%;

        margin-left: 0;

        padding-top: 18px;

        padding-bottom: 18px;

        margin-top: 13px;

        border-top: 1px solid #eeeeee;
    }


    /* Main Links */

    .hypeline-main-links {
        width: 100%;

        display: flex;

        flex-direction: column;

        align-items: stretch;

        gap: 0;
    }


    .hypeline-main-links a {
        width: 100%;

        display: block;

        padding: 12px 5px;

        font-size: 15px;
    }


    /* Account Section */

    .hypeline-account-links {

        width: 100%;

        display: flex;

        flex-direction: column;

        align-items: stretch;

        gap: 0;

        border-top: 1px solid #eeeeee;

        margin-top: 12px;

        padding-top: 12px;
    }


    .hypeline-account-links a {

        width: 100%;

        display: block;

        padding: 12px 5px;

        font-size: 15px;
    }


    /* Dashboard */

    .hypeline-outline-button {

        width: 100%;

        text-align: center;

        margin-bottom: 5px;
    }


    /* Logout */

    .hypeline-account-links form {

        width: 100%;

        margin-top: 5px;
    }


    .hypeline-dark-button {

        width: 100%;

        display: block;

        text-align: center;
    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 575px) {

    .hypeline-nav-container {
        padding-left: 15px;
        padding-right: 15px;
    }


    .hypeline-logo {
        font-size: 21px;
    }

}

</style>