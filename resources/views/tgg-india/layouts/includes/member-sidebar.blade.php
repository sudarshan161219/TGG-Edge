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

    $module_instance_ids = \App\Models\ModuleInstanceAssign::where('user_id', $user->id)->pluck('module_instance_ids')->toArray();
    $module_instance_ids_count = \App\Models\ModuleInstanceAssign::where('user_id', $user->id)->count();
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
            <h3 class="profile-name">{{ $user->name ?? 'Member' }}</h3>
            <p class="profile-role">Role <span>{{ ucfirst($user->role_name ?? 'Associate') }}</span></p>
            <p class="profile-id">RHM No: <span>{{ $user->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <ul class="nav-menu">

        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('tgg-india/dashboard') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.associate.dashboard') }}" class="nav-link sidebar-nav-link">
                <x-ri-dashboard-line class="icon"/>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item {{ request()->is('user/profile') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.associate.profile.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-user class="icon"/>
                <span class="nav-label">Profile</span>
            </a>
        </li>

        <!-- Switch Account (if multiple accounts) -->
        @if ($otherAccounts->count() > 0)
            @php
                $isSwitchActive = request()->is('tgg-india/switch*');
            @endphp
            <li class="nav-item has-dropdown {{ $isSwitchActive ? 'is-open' : '' }}">
                <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-user-group class="submenu-icon" />
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

        <!-- Assignments (if any) -->
        @if ($assignments->count() > 0)
            <li class="nav-item {{ request()->is('tgg-edge/tgg-fct/assignee/assignments*') ? 'active' : '' }}">
                <a href="{{ route('tgg-india.associate.assignments.index') }}" class="nav-link sidebar-nav-link">
                    <x-heroicon-o-clipboard-document-list class="icon"/>
                    <span class="nav-label">Assignments</span>
                </a>
            </li>
        @endif

        <!-- Advancement Dropdown -->
        @php
            $isAdvancementActive = request()->is('tgg-meta/tgg-india/associate/incentives*') ||
                                   request()->is('tgg-meta/tgg-india/associate/rewards*') ||
                                   request()->is('tgg-meta/tgg-india/associate/invoices*') ||
                                   request()->is('tgg-meta/tgg-india/associate/receipts*');
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
                <li><a href="{{ route('tgg-india.associate.incentives.index') }}" class="submenu-link">Incentive</a></li>
                <li><a href="{{ route('tgg-india.associate.rewards.index') }}" class="submenu-link">Reward</a></li>
                <li><a href="{{ route('tgg-india.associate.invoices.index') }}" class="submenu-link">Invoice</a></li>
                <li><a href="{{ route('tgg-india.associate.receipts.index') }}" class="submenu-link">Receipt</a></li>
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
                <li><a href="https://www.modicare.com/sign-in" class="submenu-link" target="_blank">Modicare Register</a></li>
                <li><a href="https://invest.motilaloswal.com/" class="submenu-link" target="_blank">Motilaloswal Register</a></li>
                <li><a href="https://pos.insureeasy.in/" class="submenu-link" target="_blank">India Insure Register</a></li>
                <li><a href="{{ url('user/login/') }}" class="submenu-link" target="_blank">TGG Foundation</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/login/XCJBDSNJK43RWEFSKDJCXNFL34KRN3DKL3MREFWLMNKL32M/') }}" class="submenu-link" target="_blank">Member Login</a></li>
            </ul>
        </li>

        <!-- Modules (Dynamic Tree) -->
        @if ($module_instance_ids_count > 0)
            @php
                $isModulesActive = request()->is('tgg-meta/tgg-india/associate/modules*');
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
                    @php
                        if (!empty($module_instance_ids[0])) {
                            sort($module_instance_ids[0]);
                        }
                    @endphp
                    @foreach ($module_instance_ids[0] ?? [] as $module_instance_id)
                        @php
                            $module_instance = \App\Models\ModuleInstance::where('id', $module_instance_id)->first();
                            $module = $module_instance ? \App\Models\Module::find($module_instance->module_id) : null;
                            if (!$module) continue;
                            $literatures = \App\Models\Literature::where('module_instance_id', $module_instance_id)->get();

                            // Determine if this module is active (any chapter/link/video inside)
                            $isModuleActive = false;
                            $activeLiteratureId = null;
                            $activeSectionId = null;
                            $activeChapterId = null;

                            foreach ($literatures as $lit) {
                                foreach ($lit->sections as $sec) {
                                    foreach ($sec->chapters as $ch) {
                                        if (request()->is('tgg-meta/tgg-india/associate/modules/chapters/' . $ch->id)) {
                                            $isModuleActive = true;
                                            $activeLiteratureId = $lit->id;
                                            $activeSectionId = $sec->id;
                                            $activeChapterId = $ch->id;
                                        }
                                    }
                                }
                            }
                            if ((request()->is('tgg-meta/tgg-india/associate/modules/links') && request()->get('module_instance_id') == $module_instance_id) ||
                                (request()->is('tgg-meta/tgg-india/associate/modules/videos') && request()->get('module_instance_id') == $module_instance_id)) {
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
                                    @php
                                        $isLiteratureActive = ($activeLiteratureId === $literature->id);
                                    @endphp
                                    <li class="nav-item has-dropdown {{ $isLiteratureActive ? 'is-open' : '' }}">
                                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                            <span class="nav-label">{{ $literature->title }}</span>
                                            <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                                        </a>
                                        <ul class="submenu" style="{{ $isLiteratureActive ? 'height:auto;' : '' }}">
                                            <!-- Sections -->
                                            @foreach ($literature->sections as $section)
                                                @php
                                                    $isSectionActive = ($activeSectionId === $section->id);
                                                @endphp
                                                <li class="nav-item has-dropdown {{ $isSectionActive ? 'is-open' : '' }}">
                                                    <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                        <span class="nav-label">{{ $section->title }}</span>
                                                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                                                    </a>
                                                    <ul class="submenu" style="{{ $isSectionActive ? 'height:auto;' : '' }}">
                                                        <!-- Chapters -->
                                                        @foreach ($section->chapters as $chapter)
                                                            <li>
                                                                <a href="{{ route('tgg-india.associate.modules.chapters', $chapter->id) }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/associate/modules/chapters/'.$chapter->id) ? 'active' : '' }}">
                                                                    <span class="nav-label">{{ $chapter->title }}</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                            <!-- Links & Videos -->
                                            @if($hasLinks)
                                                <li>
                                                    <a href="{{ route('tgg-india.associate.modules.links') }}?module_instance_id={{ $module_instance_id }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/associate/modules/links') ? 'active' : '' }}">
                                                        <span class="nav-label">Links</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if($hasVideos)
                                                <li>
                                                    <a href="{{ route('tgg-india.associate.modules.videos') }}?module_instance_id={{ $module_instance_id }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/associate/modules/videos') ? 'active' : '' }}">
                                                        <span class="nav-label">Videos</span>
                                                    </a>
                                                </li>
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
            $isReferralActive = request()->is('tgg-meta/tgg-india/associate/referral/program*') ||
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
                <li><a href="{{ route('tgg-india.associate.referral.program') }}" class="submenu-link">Referral Program</a></li>
                <li><a href="{{ route('tgg-india.associate.referral.tracking') }}" class="submenu-link">Referral Tracking</a></li>
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

    <!-- EXTRA WIDGETS (News & WhatsApp) -->
    <!-- @if (url()->current() === url('tgg-meta/tgg-india/associate/dashboard'))
        <div class="sidebar-widget mt-4">
            <div class="card tgg_news">
                <h3 class="card-title">TGG NEWS</h3>
                <div class="card-inner">
                    <div class="slider" style="height: 220px !important; width: auto !important">
                        @if (!empty($showcase->tgg_news))
                            @foreach ($showcase->tgg_news as $news)
                                <div class="slide" style="height: 220px !important; width: auto !important; margin: 10px !important;">
                                    <iframe width="100%" height="200" src="{{ getEmbedUrl($news) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                            @endforeach
                        @else
                            <p>No news available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif -->

</aside>