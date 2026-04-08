@php
    $user = auth('web2')->user();
    $modules = $user->modules;
    $host = request()->getHost();
    if ($host === 'localhost' || $host === '127.0.0.1') {
        $investmentModules = $user->modules->filter(function ($module) {
            return $module->slug === 'investment-sip' || $module->name === 'Investment sip';
        });
    } else {
        $investmentModules = $user->modules->filter(function ($module) {
            return $module->slug === 'investment-for-beginners' || $module->name === 'INVESTMENT FOR BEGINNERS';
        });
    }

    $features = $user->modules->flatMap->features;
    $hasLiteratures = $features->contains('feature_key', 'literatures');
    $hasLinks = $features->contains('feature_key', 'links');
    $hasVideos = $features->contains('feature_key', 'videos');
    $otherAccounts = \App\Models\UserSecondary::where('email', $user->email)->where('id', '!=', $user->id)->get();
    $literatures = \App\Models\Literature::get();
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
            <h3 class="m-profile-name">{{ $user->name ?? 'Co-Creator' }}</h3>
            <p class="m-profile-role">Role <span>{{ ucfirst($user->role_name ?? 'Co-Creator') }}</span></p>
            <p class="m-profile-id">RHM No: <span>{{ $user->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <!-- BODY (Navigation) -->
    <div class="m-sidebar-body">
        <ul class="m-sidebar-nav">

            <!-- Dashboard -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.co-creator.dashboard') }}" class="m-sidebar-link {{ request()->is('tgg-india/dashboard') ? 'active' : '' }}">
                    <x-ri-dashboard-line class="icon"/> Dashboard
                </a>
            </li>

            <!-- Profile -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.co-creator.profile.index') }}" class="m-sidebar-link {{ request()->is('user/profile') ? 'active' : '' }}">
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
                    <a href="{{ route('tgg-india.co-creator.assignments.index') }}" class="m-sidebar-link {{ request()->is('tgg-edge/tgg-fct/assignee/assignments*') ? 'active' : '' }}">
                        <x-heroicon-o-clipboard-document-list class="icon"/> Assignments
                    </a>
                </li>
            @endif

            <!-- Advancement Dropdown -->
            @php
                $isAdvancementActive = request()->is('tgg-meta/tgg-india/co-creator/incentives*') ||
                                       request()->is('tgg-meta/tgg-india/co-creator/rewards*') ||
                                       request()->is('tgg-meta/tgg-india/co-creator/invoices*') ||
                                       request()->is('tgg-meta/tgg-india/co-creator/receipts*');
            @endphp
            <li class="m-sidebar-item has-dropdown {{ $isAdvancementActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-arrow-trending-up class="icon"/>
                        <span>Advancement</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('tgg-india.co-creator.incentives.index') }}" class="submenu-link">Incentive</a></li>
                    <li><a href="{{ route('tgg-india.co-creator.rewards.index') }}" class="submenu-link">Reward</a></li>
                    <li><a href="{{ route('tgg-india.co-creator.invoices.index') }}" class="submenu-link">Invoice</a></li>
                    <li><a href="{{ route('tgg-india.co-creator.receipts.index') }}" class="submenu-link">Receipt</a></li>
                </ul>
            </li>

            <!-- Links (Sitemap) Dropdown -->
            <li class="m-sidebar-item has-dropdown">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-ri-map-line class="icon"/>
                        <span>Links (Sitemap)</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu">
                    <li><a href="https://tggindia.com/my-account/" class="submenu-link" target="_blank">Journey with TGG Login</a></li>
                </ul>
            </li>

            <!-- Modules Dropdown (dynamic) -->
            @if ($user->modules->isNotEmpty())
                @php
                    $isModulesActive = request()->is('tgg-meta/tgg-india/co-creator/modules*');
                @endphp
                <li class="m-sidebar-item has-dropdown {{ $isModulesActive ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                        <div class="dropdown-left">
                            <x-heroicon-o-cube class="icon"/>
                            <span>Modules</span>
                        </div>
                        <x-heroicon-o-chevron-right class="icon"/>
                    </a>
                    <ul class="submenu" style="{{ $isModulesActive ? 'height:auto;' : '' }}">
                        @foreach ($user->modules as $module)
                            @php
                                $moduleInstance = \App\Models\ModuleInstance::where('module_id', $module->id)->first();
                                $moduleInstanceId = $moduleInstance ? $moduleInstance->id : null;
                                $literatures = \App\Models\Literature::where('module_instance_id', $moduleInstanceId)->get();

                                $isModuleActive = false;
                                $activeLiteratureId = null;
                                $activeSectionId = null;
                                $activeChapterId = null;

                                foreach ($literatures as $lit) {
                                    foreach ($lit->sections as $sec) {
                                        foreach ($sec->chapters as $ch) {
                                            if (request()->is('tgg-meta/tgg-india/co-creator/modules/chapters/' . $ch->id)) {
                                                $isModuleActive = true;
                                                $activeLiteratureId = $lit->id;
                                                $activeSectionId = $sec->id;
                                                $activeChapterId = $ch->id;
                                            }
                                        }
                                    }
                                }
                                if ((request()->is('tgg-meta/tgg-india/co-creator/modules/links') && request()->get('module_instance_id') == $moduleInstanceId) ||
                                    (request()->is('tgg-meta/tgg-india/co-creator/modules/videos') && request()->get('module_instance_id') == $moduleInstanceId)) {
                                    $isModuleActive = true;
                                }
                            @endphp

                            <!-- Single Module -->
                            <li class="has-dropdown {{ $isModuleActive ? 'active' : '' }}" style="margin-left: 0;">
                                <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                    <span>{{ $module->name }}</span>
                                    <x-heroicon-o-chevron-right class="icon"/>
                                </a>
                                <ul class="submenu" style="{{ $isModuleActive ? 'height:auto;' : '' }}">
                                    <!-- Literatures -->
                                    @foreach ($literatures as $literature)
                                        @php $isLiteratureActive = ($activeLiteratureId === $literature->id); @endphp
                                        <li class="has-dropdown {{ $isLiteratureActive ? 'active' : '' }}">
                                            <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                <span>{{ $literature->title }}</span>
                                                <x-heroicon-o-chevron-right class="icon"/>
                                            </a>
                                            <ul class="submenu" style="{{ $isLiteratureActive ? 'height:auto;' : '' }}">
                                                @foreach ($literature->sections as $section)
                                                    @php $isSectionActive = ($activeSectionId === $section->id); @endphp
                                                    <li class="has-dropdown {{ $isSectionActive ? 'active' : '' }}">
                                                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                            <span>{{ $section->title }}</span>
                                                            <x-heroicon-o-chevron-right class="icon"/>
                                                        </a>
                                                        <ul class="submenu" style="{{ $isSectionActive ? 'height:auto;' : '' }}">
                                                            @foreach ($section->chapters as $chapter)
                                                                <li>
                                                                    <a href="{{ route('tgg-india.co-creator.modules.chapters', $chapter->id) }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/co-creator/modules/chapters/'.$chapter->id) ? 'active' : '' }}">
                                                                        {{ $chapter->title }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                                @if ($hasLinks)
                                                    <li><a href="{{ route('tgg-india.co-creator.modules.links') }}?module_instance_id={{ $moduleInstanceId }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/co-creator/modules/links') ? 'active' : '' }}">Links</a></li>
                                                @endif
                                                @if ($hasVideos)
                                                    <li><a href="{{ route('tgg-india.co-creator.modules.videos') }}?module_instance_id={{ $moduleInstanceId }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/co-creator/modules/videos') ? 'active' : '' }}">Videos</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif

            <!-- Referral Dropdown -->
            @php
                $isReferralActive = request()->is('tgg-meta/tgg-india/co-creator/referral/program*') ||
                                    request()->is('tgg-india/admin/referral/tracking*');
            @endphp
            <li class="m-sidebar-item has-dropdown {{ $isReferralActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-share class="icon"/>
                        <span>Referral</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('tgg-india.co-creator.referral.program') }}" class="submenu-link">Referral Program</a></li>
                    <li><a href="{{ route('tgg-india.co-creator.referral.tracking') }}" class="submenu-link">Referral Tracking</a></li>
                </ul>
            </li>

        </ul>
    </div>

    <!-- FOOTER (Logout + Widgets) -->
    <div class="m-sidebar-footer">
        <!-- Logout -->
        <div class="m-logout-section">
            <a href="{{ route('tgg-india.logout') }}" class="m-logout-btn">
                <x-heroicon-o-arrow-right-on-rectangle class="logout-icon"/>
                <span>Log out</span>
            </a>
        </div>
        <!-- Social Links -->
        <div class="m-social-links mt-2">
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