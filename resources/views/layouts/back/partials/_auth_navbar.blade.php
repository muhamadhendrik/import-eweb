<li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="p-0 nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="{{ asset('back/assets') }}/img/avatars/1.png" alt class="rounded-circle" />
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="mt-0 dropdown-item" href="pages-account-settings-account.html">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('back/assets') }}/img/avatars/1.png" alt class="rounded-circle" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->roles()->first()->name }}</small>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <div class="my-1 dropdown-divider mx-n2"></div>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('users.change-password') }}">
                <i class="ti ti-user me-3 ti-md"></i>
                <span class="align-middle">Change Password</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" onclick="alert('Belum jadi hehe')">
                <i class="ti ti-question-mark me-3 ti-md"></i><span class="align-middle">FAQ</span>
            </a>
        </li>
        <li>
            <div class="my-1 dropdown-divider mx-n2"></div>
        </li>
        <li>
            <div class="px-2 pt-2 pb-1 d-grid">
                <!-- Tombol Logout -->
                <a class="btn btn-sm btn-danger d-flex align-items-center" href="javascript:void(0);"
                    onclick="event.preventDefault(); if (confirm('Are you sure you want to logout?')) document.getElementById('logout-form').submit();">
                    <small>Logout</small>
                    <i class="ti ti-logout ms-2 ti-14px"></i>
                </a>

                <!-- Form Logout -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
</li>
