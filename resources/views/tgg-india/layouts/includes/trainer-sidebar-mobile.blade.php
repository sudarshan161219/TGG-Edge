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

<div class="m-sidebar-container">

    <!-- HEADER with Logo & Close -->
    <div class="m-sidebar-header">
        <div class="m-sidebar-logo">
            <a class="mobile-logo-link" href="{{ url('https://tggindia.com/') }}">
                <img class="mobile-logo" src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}" alt="TGG India Logo">
            </a>
        </div>
        <button id="mobileSidebarClose" class="m-close-btn" aria-label="Close menu">
            <x-heroicon-o-x-mark class="close-icon" />
        </button>
    </div>

    <!-- PROFILE SECTION -->
    <div class="m-profile-section">
        <div class="m-avatar-container">
            <img src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}" class="m-profile-avatar">
        </div>
        <div class="m-profile-card">
            <h3 class="m-profile-name">{{ $user->name ?? 'Trainer' }}</h3>
            <p class="m-profile-role">Role <span>{{ ucfirst($user->role_name ?? 'Trainer') }}</span></p>
            <p class="m-profile-id">RHM No: <span>{{ $user->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <!-- BODY (Navigation) -->
    <div class="m-sidebar-body">
        <ul class="m-sidebar-nav">

            <!-- Dashboard -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.trainer.dashboard') }}" class="m-sidebar-link {{ request()->is('tgg-india/dashboard') ? 'active' : '' }}">
                    <x-ri-dashboard-line class="icon"/> Dashboard
                </a>
            </li>

            <!-- Profile -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.trainer.profile.index') }}" class="m-sidebar-link {{ request()->is('user/profile') ? 'active' : '' }}">
                    <x-heroicon-o-user class="icon"/> Profile
                </a>
            </li>

            <!-- Switch Account -->
            @if ($otherAccounts->count() > 0)
                @php $isSwitchActive = request()->is('tgg-india/switch*'); @endphp
                <li class="m-sidebar-item has-dropdown {{ $isSwitchActive ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                        <div class="dropdown-left">
                            <x-heroicon-o-user-group class="icon" />
                            <span>Switch Account</span>
                        </div>
                        <x-heroicon-o-chevron-right class="icon"/>
                    </a>
                    <ul class="submenu">
                        @foreach ($otherAccounts as $account)
                            <li>
                                <a href="{{ route('tgg-india.switch.account', $account->id) }}" class="submenu-link" target="_blank">
                                    Switch to {{ ucfirst($account->role_name ?? 'N/A') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif

            <!-- Assignments -->
            @if ($assignments->count() > 0)
                <li class="m-sidebar-item">
                    <a href="{{ route('tgg-india.trainer.assignments.index') }}" class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/trainer/assignments*') ? 'active' : '' }}">
                        <x-heroicon-o-clipboard-document-list class="icon"/> Assignments
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
            <li class="m-sidebar-item has-dropdown {{ $isModuleActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-cube class="icon"/>
                        <span>{{ $moduleName }}</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu" style="{{ $isModuleActive ? 'height:auto;' : '' }}">

                    <!-- Create Section submenu -->
                    @php
                        $isCreateActive = request()->is('tgg-meta/tgg-india/trainer/sections*') ||
                                          request()->is('tgg-meta/tgg-india/trainer/links/index*') ||
                                          request()->is('tgg-meta/tgg-india/trainer/videos/index*');
                    @endphp
                    <li class="has-dropdown {{ $isCreateActive ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                            <span>Create Section</span>
                            <x-heroicon-o-chevron-right class="icon"/>
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
                    <li class="has-dropdown {{ $isViewActive ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                            <span>View Section</span>
                            <x-heroicon-o-chevron-right class="icon"/>
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
                                    <li class="has-dropdown {{ $isLiteratureActive ? 'active' : '' }}">
                                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                            <span>{{ $literature->title }}</span>
                                            <x-heroicon-o-chevron-right class="icon"/>
                                        </a>
                                        <ul class="submenu" style="{{ $isLiteratureActive ? 'height:auto;' : '' }}">
                                            @foreach ($literature->sections as $section)
                                                @php
                                                    $isSectionActive = $section->chapters->contains(function ($chapter) {
                                                        return request()->is('tgg-meta/tgg-india/trainer/chapters/' . $chapter->id);
                                                    });
                                                @endphp
                                                <li class="has-dropdown {{ $isSectionActive ? 'active' : '' }}">
                                                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                        <span>{{ $section->title }}</span>
                                                        <x-heroicon-o-chevron-right class="icon"/>
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
                    <li class="has-dropdown {{ $isFeatureLimitActive ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                            <span>Feature Limits</span>
                            <x-heroicon-o-chevron-right class="icon"/>
                        </a>
                        <ul class="submenu" style="{{ $isFeatureLimitActive ? 'height:auto;' : '' }}">
                            <li><a href="{{ route('tgg-india.trainer.feature-limits.index') }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/trainer/feature-limits') ? 'active' : '' }}">Manage Feature Limits</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- Advancement Dropdown (Invoices & Receipts) -->
            @php
                $isAdvancementActive = request()->is('tgg-meta/tgg-india/trainer/incentives*') ||
                                       request()->is('tgg-meta/tgg-india/trainer/rewards*') ||
                                       request()->is('tgg-meta/tgg-india/trainer/invoices*') ||
                                       request()->is('tgg-meta/tgg-india/trainer/receipts*');
            @endphp
            <li class="m-sidebar-item has-dropdown {{ $isAdvancementActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-arrow-trending-up class="icon"/>
                        <span>Advancement</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu" style="{{ $isAdvancementActive ? 'height:auto;' : '' }}">
                    <li><a href="{{ route('tgg-india.trainer.invoices.index') }}" class="submenu-link">Invoice</a></li>
                    <li><a href="{{ route('tgg-india.trainer.receipts.index') }}" class="submenu-link">Receipt</a></li>
                </ul>
            </li>

        </ul>
    </div>

    <!-- FOOTER (Logout + Social Links) -->
    <div class="m-sidebar-footer">
        <div class="m-logout-section">
            <a href="{{ route('tgg-india.logout') }}" class="m-logout-btn">
                <x-heroicon-o-arrow-right-on-rectangle class="logout-icon"/>
                <span>Log out</span>
            </a>
        </div>
        <div class="m-social-links">
            <a href="https://www.instagram.com/tggfamily/" class="m-social-icon" target="_blank"><x-ri-instagram-fill /></a>
            <a href="https://www.facebook.com/TGGIndia" class="m-social-icon" target="_blank"><x-ri-facebook-fill /></a>
            <a href="https://www.youtube.com/@tggindia" class="m-social-icon" target="_blank"><x-ri-youtube-fill /></a>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();

            const parentItem = this.closest('.has-dropdown');
            const submenu = parentItem.querySelector('.submenu');

            if (parentItem.classList.contains('active')) {
                submenu.style.height = submenu.scrollHeight + 'px';
                void submenu.offsetHeight;
                submenu.style.height = '0px';
                parentItem.classList.remove('active');
            } else {
                parentItem.classList.add('active');
                submenu.style.height = submenu.scrollHeight + 'px';

                submenu.addEventListener('transitionend', function handler() {
                    if (parentItem.classList.contains('active')) {
                        submenu.style.height = 'auto';
                    }
                    submenu.removeEventListener('transitionend', handler);
                });
            }
        });
    });
});
</script>