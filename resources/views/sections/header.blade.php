@php
    // dd(auth()->user());
    $user = auth()->user();
    if ($user) {
        $role = $user->getRoleAttribute();
        // dd($role);
        $nav = '';
        if ($role == 'customer') {
            $nav = 'Customer Portal';
        } elseif ($role == 'admin') {
            $nav = 'Admin Portal';
        } elseif ($role == 'vendor') {
            $nav = 'Vendor Portal';
        }
    } else {
        $nav = 'Login';
    }
@endphp

<header class="site-header">
    <div id="main-header" class="main-header header-sticky">
        <div class="inner-header container clearfix">
            <div class="logo flex justify-center items-center" style="height: 110px;">
                <a class="" href="/">
                    <img class="h-7" src="{{asset('assets/images/EASYAUCTION.png')}}" alt="">
                </a>
            </div>
            <div class="header-right-toggle pull-right hidden-md hidden-lg">
                <a href="javascript:void(0)" class="side-menu-button"><i class="fa fa-bars"></i></a>
            </div>
            <nav class="main-navigation text-left hidden-xs hidden-sm">
                <ul id="menu" class="nav-bar">
                    {{-- <li><a href="index.html">Home</a></li> --}}

                    <div>

                        <li><a href="/listings">Listings</a></li>
                    </div>
                    <div id="acc">
                        <li><a href="/admin" target="_blank">{{ $nav }}</a></li>
                        {{-- <li><a href="/admin">Register</a></li> --}}

                    </div>                    
                </ul>
            </nav>
        </div>
    </div>
</header>

<style>
    #menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 110px;
    }

    #acc {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 110px;
    }
</style>
