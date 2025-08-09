<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">
    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">

        {{-- Dashboard --}}
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.home') }}"
                class="c-sidebar-nav-link {{ request()->routeIs('admin.home') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                {{ trans('global.dashboard') }}
            </a>
        </li>

        {{-- ============================= --}}
        {{-- 📂 CMS Management --}}
        {{-- Show if user can access ANY CMS child --}}
        {{-- ============================= --}}
        @if (auth()->user()->can('category_access') ||
                auth()->user()->can('article_access') ||
                auth()->user()->can('comment_access') ||
                auth()->user()->can('cms_access'))
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/categories*') || request()->is('admin/articles*') || request()->is('admin/comments*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="c-sidebar-nav-icon fas fa-newspaper"></i>
                    CMS Management
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.categories.*') ? 'c-active' : '' }}">
                                <i class="c-sidebar-nav-icon fas fa-folder"></i>
                                Categories
                            </a>
                        </li>
                    @endcan
                    @can('article_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.articles.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.articles.*') ? 'c-active' : '' }}">
                                <i class="c-sidebar-nav-icon fas fa-file-alt"></i>
                                Articles
                            </a>
                        </li>
                    @endcan
                    @can('comment_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.comments.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.comments.*') ? 'c-active' : '' }}">
                                <i class="c-sidebar-nav-icon fas fa-comments"></i>
                                Comments
                            </a>
                        </li>
                    @endcan

                    {{-- Optional: Public feed shortcut (opens new tab) --}}
                    @can('article_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('frontend.articles.index') }}" target="_blank" rel="noopener"
                                class="c-sidebar-nav-link">
                                <i class="c-sidebar-nav-icon fas fa-external-link-alt"></i>
                                Public Feed
                            </a>

                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        {{-- ============================= --}}
        {{-- 👤 User Management --}}
        {{-- ============================= --}}
        @can('user_management_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon"></i>
                    User Management
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.users.*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                Users
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.roles.*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon"></i>
                                Roles
                            </a>
                        </li>
                    @endcan
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.permissions.*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon"></i>
                                Permissions
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- 🌍 Location Details --}}
        @can('detail_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/cities*') || request()->is('admin/states*') || request()->is('admin/countries*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="c-sidebar-nav-icon fas fa-map"></i>
                    {{ trans('cruds.detail.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('city_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.cities.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.cities.*') ? 'c-active' : '' }}">
                                <i class="c-sidebar-nav-icon fas fa-map-marker-alt"></i>
                                {{ trans('cruds.city.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('state_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.states.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.states.*') ? 'c-active' : '' }}">
                                <i class="c-sidebar-nav-icon fas fa-map-signs"></i>
                                {{ trans('cruds.state.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('country_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.countries.index') }}"
                                class="c-sidebar-nav-link {{ request()->routeIs('admin.countries.*') ? 'c-active' : '' }}">
                                <i class="c-sidebar-nav-icon fas fa-flag"></i>
                                {{ trans('cruds.country.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- 📜 Audit Logs --}}
        @can('audit_log_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="c-sidebar-nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon"></i>
                    Audit Logs
                </a>
            </li>
        @endcan

        {{-- ⚙ Settings --}}
        @can('setting_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.settings.index') }}"
                    class="c-sidebar-nav-link {{ request()->routeIs('admin.settings.*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                    Settings
                </a>
            </li>
        @endcan

        {{-- 🔑 Change Password --}}
        @can('profile_password_edit')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('profile.password.edit') }}"
                    class="c-sidebar-nav-link {{ request()->routeIs('profile.password.edit') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-key c-sidebar-nav-icon"></i>
                    Change Password
                </a>
            </li>
        @endcan

        {{-- 🚪 Logout --}}
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link"
                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>
</div>
