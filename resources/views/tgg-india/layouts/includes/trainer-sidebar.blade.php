@php
    $user = auth('web2')->user();
    $features = collect();
    $hasLiteratures = $hasLinks = $hasVideos = false;
    $otherAccounts = collect();

    if ($user) {
        $features = $user->modules->flatMap->features ?? collect();
        $hasLiteratures = $features->contains('feature_key', 'literatures');
        $hasLinks = $features->contains('feature_key', 'links');
        $hasVideos = $features->contains('feature_key', 'videos');
        $otherAccounts = \App\Models\UserSecondary::where('email', $user->email)
            ->where('id', '!=', $user->id)
            ->get();
    }
    $assignments = \App\Models\AssignmentSecondary::where('assigned_to', auth('web2')->id())->get();
@endphp

<aside class="sidebar">

    <!-- PROFILE -->
    <div class="profile-section">
        <div class="bg-color">
            <div class="avatar-container">
                <img src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}" class="profile-avatar">
            </div>
        </div>

        <div class="profile-card">
            <h3 class="profile-name">{{ $user->name ?? 'Trainer' }}</h3>
            <p class="profile-role">Role <span>{{ ucfirst($user->role_name ?? 'Trainer') }}</span></p>
            <p class="profile-id">RHM No: <span>{{ $user->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <ul class="nav-menu">

        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('tgg-india/dashboard') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.trainer.dashboard') }}" class="nav-link sidebar-nav-link">
                <x-ri-dashboard-line class="icon"/>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item {{ request()->is('user/profile') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.trainer.profile.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-user class="icon"/>
                <span class="nav-label">Profile</span>
            </a>
        </li>

        <!-- Switch Account -->
        @if ($otherAccounts->count() > 0)
            @php $isSwitchActive = request()->is('tgg-india/switch*'); @endphp
            <li class="nav-item has-dropdown {{ $isSwitchActive ? 'is-open' : '' }}">
                <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-user-group class="icon" />
                        <span class="nav-label">Switch Account</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                </a>
                <ul class="submenu" style="{{ $isSwitchActive ? 'height:auto;' : '' }}">
                    @foreach ($otherAccounts as $account)
                        <li>
                            <a href="{{ route('tgg-india.switch.account', $account->id) }}" class="submenu-link" target="_blank">
                                <span class="nav-label">Switch to {{ ucfirst($account->role_name ?? 'N/A') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        <!-- Assignments -->
        @if ($assignments->count() > 0)
            <li class="nav-item {{ request()->is('tgg-meta/tgg-india/trainer/assignments*') ? 'active' : '' }}">
                <a href="{{ route('tgg-india.trainer.assignments.index') }}" class="nav-link sidebar-nav-link">
                    <x-heroicon-o-clipboard-document-list class="icon"/>
                    <span class="nav-label">Assignments</span>
                </a>
            </li>
        @endif

        <!-- Module (Investment) Dropdown -->
        @php
            $moduleName = $user->modules->last()->name ?? 'Module';
            $isModuleActive = request()->is('tgg-meta/tgg-india/trainer/sections*') ||
                              request()->is('tgg-meta/tgg-india/trainer/links*') ||
                              request()->is('tgg-meta/tgg-india/trainer/videos*') ||
                              request()->is('tgg-meta/tgg-india/trainer/chapters*') ||
                              request()->is('tgg-meta/tgg-india/trainer/feature-limits*');
        @endphp
        <li class="nav-item has-dropdown {{ $isModuleActive ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-cube class="icon"/>
                    <span class="nav-label">{{ $moduleName }}</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>
            <ul class="submenu" style="{{ $isModuleActive ? 'height:auto;' : '' }}">

                <!-- Create Section submenu -->
                @php
                    $isCreateActive = request()->is('tgg-meta/tgg-india/trainer/sections*') ||
                                      request()->is('tgg-meta/tgg-india/trainer/links/index*') ||
                                      request()->is('tgg-meta/tgg-india/trainer/videos/index*');
                @endphp
                <li class="nav-item has-dropdown {{ $isCreateActive ? 'is-open' : '' }}">
                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                        <span class="nav-label">Create Section</span>
                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                    </a>
                    <ul class="submenu" style="{{ $isCreateActive ? 'height:auto;' : '' }}">
                        @if ($hasLiteratures)
                            <li><a href="{{ route('tgg-india.trainer.sections.index') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/sections*') ? 'active' : '' }}">Literatures</a></li>
                        @endif
                        @if ($hasLinks)
                            <li><a href="{{ route('tgg-india.trainer.links.index') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/links/index*') ? 'active' : '' }}">Links</a></li>
                        @endif
                        @if ($hasVideos)
                            <li><a href="{{ route('tgg-india.trainer.videos.index') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/videos/index*') ? 'active' : '' }}">Videos</a></li>
                        @endif
                    </ul>
                </li>

                <!-- View Section submenu (dynamic literature/section/chapter tree) -->
                @php
                    $isViewActive = request()->is('tgg-meta/tgg-india/trainer/chapters*') ||
                                    request()->is('tgg-meta/tgg-india/trainer/links/show*') ||
                                    request()->is('tgg-meta/tgg-india/trainer/videos/show*');
                @endphp
                <li class="nav-item has-dropdown {{ $isViewActive ? 'is-open' : '' }}">
                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                        <span class="nav-label">View Section</span>
                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                    </a>
                    <ul class="submenu" style="{{ $isViewActive ? 'height:auto;' : '' }}">

                        @if ($hasLiteratures)
                            @foreach ($user->literatures as $literature)
                                @php
                                    $isLiteratureActive = $literature->sections->contains(function ($section) {
                                        return $section->chapters->contains(function ($chapter) {
                                            return request()->is('tgg-meta/tgg-india/trainer/chapters/' . $chapter->id);
                                        });
                                    });
                                @endphp
                                <li class="nav-item has-dropdown {{ $isLiteratureActive ? 'is-open' : '' }}">
                                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                        <span class="nav-label">{{ $literature->title }}</span>
                                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                                    </a>
                                    <ul class="submenu" style="{{ $isLiteratureActive ? 'height:auto;' : '' }}">
                                        @foreach ($literature->sections as $section)
                                            @php
                                                $isSectionActive = $section->chapters->contains(function ($chapter) {
                                                    return request()->is('tgg-meta/tgg-india/trainer/chapters/' . $chapter->id);
                                                });
                                            @endphp
                                            <li class="nav-item has-dropdown {{ $isSectionActive ? 'is-open' : '' }}">
                                                <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                    <span class="nav-label">{{ $section->title }}</span>
                                                    <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                                                </a>
                                                <ul class="submenu" style="{{ $isSectionActive ? 'height:auto;' : '' }}">
                                                    @foreach ($section->chapters as $chapter)
                                                        <li>
                                                            <a href="{{ route('tgg-india.trainer.chapters.show', $chapter->id) }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/chapters/'.$chapter->id) ? 'active' : '' }}">
                                                                {{ $chapter->title }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        @endif

                        @if ($hasLinks)
                            <li><a href="{{ route('tgg-india.trainer.links.show') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/links/show*') ? 'active' : '' }}">Links</a></li>
                        @endif
                        @if ($hasVideos)
                            <li><a href="{{ route('tgg-india.trainer.videos.show') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/videos/show*') ? 'active' : '' }}">Videos</a></li>
                        @endif
                    </ul>
                </li>

                <!-- Feature Limits -->
                @php $isFeatureLimitActive = request()->is('tgg-meta/tgg-india/trainer/feature-limits*'); @endphp
                <li class="nav-item has-dropdown {{ $isFeatureLimitActive ? 'is-open' : '' }}">
                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                        <span class="nav-label">Feature Limits</span>
                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                    </a>
                    <ul class="submenu" style="{{ $isFeatureLimitActive ? 'height:auto;' : '' }}">
                        <li><a href="{{ route('tgg-india.trainer.feature-limits.index') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/feature-limits') ? 'active' : '' }}">Manage Feature Limits</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <!-- Advancement Dropdown (Invoices & Receipts only) -->
        @php
            $isAdvancementActive = request()->is('tgg-meta/tgg-india/trainer/incentives*') ||
                                   request()->is('tgg-meta/tgg-india/trainer/rewards*') ||
                                   request()->is('tgg-meta/tgg-india/trainer/invoices*') ||
                                   request()->is('tgg-meta/tgg-india/trainer/receipts*');
        @endphp
        <li class="nav-item has-dropdown {{ $isAdvancementActive ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-arrow-trending-up class="icon"/>
                    <span class="nav-label">Advancement</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>
            <ul class="submenu" style="{{ $isAdvancementActive ? 'height:auto;' : '' }}">
                <li><a href="{{ route('tgg-india.trainer.invoices.index') }}" class="submenu-link">Invoice</a></li>
                <li><a href="{{ route('tgg-india.trainer.receipts.index') }}" class="submenu-link">Receipt</a></li>
            </ul>
        </li>

    </ul>

    <!-- LOGOUT -->
    <div class="logout-section">
        <a href="{{ route('tgg-india.logout') }}" class="logout-btn">
            <x-heroicon-o-arrow-right-on-rectangle class="logout-icon"/>
            <span class="nav-label">Log out</span>
        </a>
    </div>

</aside>