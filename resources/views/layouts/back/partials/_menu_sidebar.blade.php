<ul class="py-1 menu-inner">
    <!-- Dashboards -->
    {{-- <li class="menu-item {{ (request()->is('/')) ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-smart-home"></i>
            <div data-i18n="Dashboard">Dashboard</div>
        </a>
    </li> --}}

    <!-- Apps & Pages -->
    <li class="menu-header small">
        <span class="menu-header-text" data-i18n="Menu">Menu</span>
    </li>
    @php
    $arr_admin_menu = array();
    // return $data_menu_admin;
    foreach($menus as $menu_admin)
    {
        $menu_admin_name 	= $menu_admin->name;
        $link				= $menu_admin->link;
        $parent_type 		= $menu_admin->parent_type;
        $parent_icon		= $menu_admin->parent_icon;
        $permission_key		= $menu_admin->permission_key;
        $permission_option	= $menu_admin->permission_option;

        $arr_menu_admin_name = explode('>', $menu_admin_name);
        // dd($menu_admin_name);
        $arr_menu_admin_name = array_map('trim', $arr_menu_admin_name);

        $jumlah_submenu = count($arr_menu_admin_name);
        // dd($arr_menu_admin_name);

        if($permission_key == "")
        {
            if($jumlah_submenu == 1)
            {
                $first = $arr_menu_admin_name[0];
                $nama_menu = $arr_menu_admin_name[0];

                if($parent_icon != '')
                {
                    $arr_icon[$first] = $parent_icon;
                }

                $arr_admin_menu[$first] = array(
                    'nama_menu'			=> $nama_menu,
                    'link'				=> $link,
                    'icon'				=> (isset($arr_icon[$first]) ? $arr_icon[$first] : array()),
                );
            }
        }
        else
        {
            if(Gate::allows($permission_key.'-view'))
            {
                if($jumlah_submenu == 1)
                {
                    $first = $arr_menu_admin_name[0];
                    $nama_menu = $arr_menu_admin_name[0];

                    if($parent_icon != '')
                    {
                        $arr_icon[$first] = $parent_icon;
                    }

                    $arr_admin_menu[$first] = array(
                        'nama_menu'			=> $nama_menu,
                        'link'				=> $link,
                        'icon'				=> (isset($arr_icon[$first]) ? $arr_icon[$first] : array()),
                    );
                }

                if($jumlah_submenu == 2)
                {
                    $first = $arr_menu_admin_name[0];
                    $nama_menu = $arr_menu_admin_name[1];

                    if($parent_icon != '')
                    {
                        $arr_icon[$first] = $parent_icon;
                    }

                    $sub_menu[$first][] = array(
                        'nama_menu'	=> $nama_menu,
                        'link'		=> $link,
                    );

                    $arr_admin_menu[$first] = array(
                        'nama_menu'			=> $first,
                        'link'				=> '#',
                        'icon'				=> (isset($arr_icon[$first]) ? $arr_icon[$first] : array()),
                        'sub_menu'			=> (isset($sub_menu[$first]) ? $sub_menu[$first] : array()),
                    );
                }

                if($jumlah_submenu == 3)
                {
                    $first = $arr_menu_admin_name[0];
                    $nama_menu = $arr_menu_admin_name[1];
                    $nama_menu_child = $arr_menu_admin_name[2];

                    if($parent_icon != '')
                    {
                        $arr_icon[$first] = $parent_icon;
                    }

                    $child_menu[$first][$nama_menu][] = array(
                        'nama_menu' => $nama_menu_child,
                        'link' => $link
                    );

                    $exists_sub_menu = array_search($nama_menu, array_column($sub_menu[$first], 'nama_menu'));
                    $exists_sub_menu = ($exists_sub_menu == '') ? null : $exists_sub_menu;

                    if ($exists_sub_menu != null && isset($sub_menu[$first][$exists_sub_menu])) {
                        $sub_menu[$first][$exists_sub_menu] = array(
                            'nama_menu'	=> $nama_menu,
                            'link'		=> '#',
                            'sub_menu' => (isset($child_menu[$first][$nama_menu]) ? $child_menu[$first][$nama_menu] : array())
                        );
                    }else {
                        $sub_menu[$first][] = array(
                            'nama_menu'	=> $nama_menu,
                            'link'		=> '#',
                            'sub_menu' => (isset($child_menu[$first][$nama_menu]) ? $child_menu[$first][$nama_menu] : array())
                        );
                    }

                    $arr_admin_menu[$first] = array(
                        'nama_menu'			=> $first,
                        'link'				=> '#',
                        'icon'				=> (isset($arr_icon[$first]) ? $arr_icon[$first] : array()),
                        'sub_menu'			=> (isset($sub_menu[$first]) ? $sub_menu[$first] : array()),
                    );
                }
            }
        }
    }
    @endphp

    @foreach($arr_admin_menu as $key => $menu)
        @if(isset($menu['sub_menu']))
            @php
            $parent_active = '';
            foreach($menu['sub_menu'] as $sub_menu)
            {
                if(request()->is($sub_menu['link']) || request()->is($sub_menu['link'].'/*'))
                {
                    $parent_active = 'active open';
                }
            }
            @endphp
            <li class="menu-item {{ $parent_active }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="{{ is_string($menu['icon']) ? $menu['icon'] : '' }}"></i>
                    <div data-i18n="{{ $menu['nama_menu'] }}">{{ $menu['nama_menu'] }}</div>
                </a>

                <ul class="menu-sub">
                    {{-- {{ (request()->is($sub_menu['link'].'/*')) ? 'active' : '' }} --}}
                    @foreach($menu['sub_menu'] as $key2 => $sub_menu)
                        <li class="menu-item {{ (request()->is($sub_menu['link'])) ? 'active' : '' }}">
                            <a href="{{ url($sub_menu['link']) }}" class="menu-link">
                                <div data-i18n="{{ $sub_menu['nama_menu'] }}">{{ $sub_menu['nama_menu'] }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            @if($menu['link'] != '#')
            <li class="menu-item {{ (request()->is($menu['link'])) ? 'active' : '' }} {{ (request()->is($menu['link'].'/*')) ? 'active' : '' }}">
                <a href="{{ url($menu['link']) }}" class="menu-link">
                    <i class="{{ is_string($menu['icon']) ? $menu['icon'] : '' }}"></i>
                    <div data-i18n="{{ $menu['nama_menu'] }}">{{ $menu['nama_menu'] }}</div>
                </a>
            </li>
            @endif
        @endif
	@endforeach
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link" onclick="event.preventDefault(); if (confirm('Are you sure you want to logout?')) document.getElementById('logout-form').submit();">
            <i class="menu-icon tf-icons ti ti-logout"></i>
            <div data-i18n="Logout">Logout</div>
        </a>
    </li>
</ul>
