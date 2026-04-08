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

<aside class="sidebar">

    <!-- PROFILE -->
    <div class="profile-section">
        <div class="bg-color">
            <div class="avatar-container">
                <img src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}" class="profile-avatar">
            </div>
        </div>

        <div class="profile-card">
            <h3 class="profile-name">{{ $user->name ?? 'Co-Creator' }}</h3>
            <p class="profile-role">Role <span>{{ ucfirst($user->role_name ?? 'Co-Creator') }}</span></p>
            <p class="profile-id">RHM No: <span>{{ $user->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <ul class="nav-menu">

        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('tgg-india/dashboard') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.co-creator.dashboard') }}" class="nav-link sidebar-nav-link">
                <x-ri-dashboard-line class="icon"/>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item {{ request()->is('user/profile') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.co-creator.profile.index') }}" class="nav-link sidebar-nav-link">
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
            <li class="nav-item {{ request()->is('tgg-edge/tgg-fct/assignee/assignments*') ? 'active' : '' }}">
                <a href="{{ route('tgg-india.co-creator.assignments.index') }}" class="nav-link sidebar-nav-link">
                    <x-heroicon-o-clipboard-document-list class="icon"/>
                    <span class="nav-label">Assignments</span>
                </a>
            </li>
        @endif

        <!-- Advancement Dropdown (Incentive, Reward, Invoice, Receipt) -->
        @php
            $isAdvancementActive = request()->is('tgg-meta/tgg-india/co-creator/incentives*') ||
                                   request()->is('tgg-meta/tgg-india/co-creator/rewards*') ||
                                   request()->is('tgg-meta/tgg-india/co-creator/invoices*') ||
                                   request()->is('tgg-meta/tgg-india/co-creator/receipts*');
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
                <li><a href="{{ route('tgg-india.co-creator.incentives.index') }}" class="submenu-link">Incentive</a></li>
                <li><a href="{{ route('tgg-india.co-creator.rewards.index') }}" class="submenu-link">Reward</a></li>
                <li><a href="{{ route('tgg-india.co-creator.invoices.index') }}" class="submenu-link">Invoice</a></li>
                <li><a href="{{ route('tgg-india.co-creator.receipts.index') }}" class="submenu-link">Receipt</a></li>
            </ul>
        </li>

        <!-- Links (Sitemap) Dropdown -->
        <li class="nav-item has-dropdown">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-ri-map-line class="icon"/>
                    <span class="nav-label">Links (Sitemap)</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
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
            <li class="nav-item has-dropdown {{ $isModulesActive ? 'is-open' : '' }}">
                <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-cube class="icon"/>
                        <span class="nav-label">Modules</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon chevron-icon"/>
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
                        <li class="nav-item has-dropdown {{ $isModuleActive ? 'is-open' : '' }}" style="margin-left: 0;">
                            <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                <span class="nav-label">{{ $module->name }}</span>
                                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                            </a>
                            <ul class="submenu" style="{{ $isModuleActive ? 'height:auto;' : '' }}">
                                <!-- Literatures -->
                                @foreach ($literatures as $literature)
                                    @php $isLiteratureActive = ($activeLiteratureId === $literature->id); @endphp
                                    <li class="nav-item has-dropdown {{ $isLiteratureActive ? 'is-open' : '' }}">
                                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                            <span class="nav-label">{{ $literature->title }}</span>
                                            <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                                        </a>
                                        <ul class="submenu" style="{{ $isLiteratureActive ? 'height:auto;' : '' }}">
                                            @foreach ($literature->sections as $section)
                                                @php $isSectionActive = ($activeSectionId === $section->id); @endphp
                                                <li class="nav-item has-dropdown {{ $isSectionActive ? 'is-open' : '' }}">
                                                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                        <span class="nav-label">{{ $section->title }}</span>
                                                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                                                    </a>
                                                    <ul class="submenu" style="{{ $isSectionActive ? 'height:auto;' : '' }}">
                                                        @foreach ($section->chapters as $chapter)
                                                            <li>
                                                                <a href="{{ route('tgg-india.co-creator.modules.chapters', $chapter->id) }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/co-creator/modules/chapters/'.$chapter->id) ? 'active' : '' }}">
                                                                    <span class="nav-label">{{ $chapter->title }}</span>
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
        <li class="nav-item has-dropdown {{ $isReferralActive ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-share class="icon"/>
                    <span class="nav-label">Referral</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>
            <ul class="submenu" style="{{ $isReferralActive ? 'height:auto;' : '' }}">
                <li><a href="{{ route('tgg-india.co-creator.referral.program') }}" class="submenu-link">Referral Program</a></li>
                <li><a href="{{ route('tgg-india.co-creator.referral.tracking') }}" class="submenu-link">Referral Tracking</a></li>
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