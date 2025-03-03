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
<nav class="sidebar-menu slide-from-left">
    <div class="nano">
        <div class="content">
            <nav class="responsive-menu">
                <ul>
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
</nav>