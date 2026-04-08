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

    <!-- PROFILE SECTION (mirroring desktop) -->
    <div class="m-profile-section">
        <div class="m-avatar-container">
            <img src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}" class="m-profile-avatar">
        </div>
        <div class="m-profile-card">
            <h3 class="m-profile-name">{{ $user->name ?? 'Member' }}</h3>
            <p class="m-profile-role">Role <span>{{ ucfirst($user->role_name ?? 'Associate') }}</span></p>
            <p class="m-profile-id">RHM No: <span>{{ $user->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <!-- BODY (Navigation) -->
    <div class="m-sidebar-body">
        <ul class="m-sidebar-nav">

            <!-- Dashboard -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.associate.dashboard') }}" class="m-sidebar-link {{ request()->is('tgg-india/dashboard') ? 'active' : '' }}">
                    <x-ri-dashboard-line class="icon"/> Dashboard
                </a>
            </li>

            <!-- Profile -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.associate.profile.index') }}" class="m-sidebar-link {{ request()->is('user/profile') ? 'active' : '' }}">
                    <x-heroicon-o-user class="icon"/> Profile
                </a>
            </li>

            <!-- Switch Account (if multiple accounts) -->
            @if ($otherAccounts->count() > 0)
                @php $isSwitchActive = request()->is('tgg-india/switch*'); @endphp
                <li class="m-sidebar-item has-dropdown {{ $isSwitchActive ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                        <div class="dropdown-left">
                            <x-heroicon-o-user-group class="submenu-icon" />
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

            <!-- Assignments (if any) -->
            @if ($assignments->count() > 0)
                <li class="m-sidebar-item">
                    <a href="{{ route('tgg-india.associate.assignments.index') }}" class="m-sidebar-link {{ request()->is('tgg-edge/tgg-fct/assignee/assignments*') ? 'active' : '' }}">
                        <x-heroicon-o-clipboard-document-list class="icon"/> Assignments
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
            <li class="m-sidebar-item has-dropdown {{ $isAdvancementActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-arrow-trending-up class="icon"/>
                        <span>Advancement</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('tgg-india.associate.incentives.index') }}" class="submenu-link">Incentive</a></li>
                    <li><a href="{{ route('tgg-india.associate.rewards.index') }}" class="submenu-link">Reward</a></li>
                    <li><a href="{{ route('tgg-india.associate.invoices.index') }}" class="submenu-link">Invoice</a></li>
                    <li><a href="{{ route('tgg-india.associate.receipts.index') }}" class="submenu-link">Receipt</a></li>
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
                    <li><a href="https://www.modicare.com/sign-in" class="submenu-link" target="_blank">Modicare Register</a></li>
                    <li><a href="https://invest.motilaloswal.com/" class="submenu-link" target="_blank">Motilaloswal Register</a></li>
                    <li><a href="https://pos.insureeasy.in/" class="submenu-link" target="_blank">India Insure Register</a></li>
                    <li><a href="{{ url('user/login/') }}" class="submenu-link" target="_blank">TGG Foundation</a></li>
                    <li><a href="{{ url('tgg-meta/tgg-india/login/XCJBDSNJK43RWEFSKDJCXNFL34KRN3DKL3MREFWLMNKL32M/') }}" class="submenu-link" target="_blank">Member Login</a></li>
                </ul>
            </li>

            <!-- Modules (Dynamic Tree) -->
            @if ($module_instance_ids_count > 0)
                @php $isModulesActive = request()->is('tgg-meta/tgg-india/associate/modules*'); @endphp
                <li class="m-sidebar-item has-dropdown {{ $isModulesActive ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                        <div class="dropdown-left">
                            <x-heroicon-o-cube class="icon"/>
                            <span>Modules</span>
                        </div>
                        <x-heroicon-o-chevron-right class="icon"/>
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

                                // Determine active states for this module
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
                                                <!-- Sections -->
                                                @foreach ($literature->sections as $section)
                                                    @php $isSectionActive = ($activeSectionId === $section->id); @endphp
                                                    <li class="has-dropdown {{ $isSectionActive ? 'active' : '' }}">
                                                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                                                            <span>{{ $section->title }}</span>
                                                            <x-heroicon-o-chevron-right class="icon"/>
                                                        </a>
                                                        <ul class="submenu" style="{{ $isSectionActive ? 'height:auto;' : '' }}">
                                                            <!-- Chapters -->
                                                            @foreach ($section->chapters as $chapter)
                                                                <li>
                                                                    <a href="{{ route('tgg-india.associate.modules.chapters', $chapter->id) }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/associate/modules/chapters/'.$chapter->id) ? 'active' : '' }}">
                                                                        {{ $chapter->title }}
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
                                                            Links
                                                        </a>
                                                    </li>
                                                @endif
                                                @if($hasVideos)
                                                    <li>
                                                        <a href="{{ route('tgg-india.associate.modules.videos') }}?module_instance_id={{ $module_instance_id }}" class="submenu-link {{ request()->is('tgg-meta/tgg-india/associate/modules/videos') ? 'active' : '' }}">
                                                            Videos
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
            <li class="m-sidebar-item has-dropdown {{ $isReferralActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-share class="icon"/>
                        <span>Referral</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('tgg-india.associate.referral.program') }}" class="submenu-link">Referral Program</a></li>
                    <li><a href="{{ route('tgg-india.associate.referral.tracking') }}" class="submenu-link">Referral Tracking</a></li>
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

        <!-- TGG NEWS (only on dashboard) -->
        <!-- @if (url()->current() === url('tgg-meta/tgg-india/associate/dashboard'))
            <div class="sidebar-widget mt-3">
                <div class="card tgg_news">
                    <h6 class="card-title">TGG NEWS</h6>
                    <div class="card-inner">
                        <div class="slider" style="height: 180px; overflow-y: auto;">
                            @if (!empty($showcase->tgg_news))
                                @foreach ($showcase->tgg_news as $news)
                                    <div class="slide mb-3">
                                        <iframe width="100%" height="160" src="{{ getEmbedUrl($news) }}" frameborder="0" allowfullscreen></iframe>
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

        <!-- Social Links (optional, like admin mobile) -->
        <div class="m-social-links mt-2">
            <a href="https://www.instagram.com/tggfamily/" class="m-social-icon" target="_blank"><x-ri-instagram-fill /></a>
            <a href="https://www.facebook.com/TGGIndia" class="m-social-icon" target="_blank"><x-ri-facebook-fill /></a>
            <a href="https://www.youtube.com/@tggindia" class="m-social-icon" target="_blank"><x-ri-youtube-fill /></a>
        </div>
    </div>

</div>

<script>
// document.addEventListener('DOMContentLoaded', function() {
//     // Handle all dropdown toggles (including nested ones inside modules)
//     const dropdownToggles = document.querySelectorAll('.m-sidebar-nav .dropdown-toggle, .m-sidebar-footer .dropdown-toggle');

//     function closeAllSubmenus(exceptElement = null) {
//         document.querySelectorAll('.m-sidebar-nav .has-dropdown, .m-sidebar-footer .has-dropdown').forEach(parent => {
//             if (exceptElement && parent === exceptElement) return;
//             parent.classList.remove('active');
//             const sub = parent.querySelector('.submenu');
//             if (sub) sub.style.height = '0px';
//         });
//     }

//     dropdownToggles.forEach(toggle => {
//         toggle.addEventListener('click', function(e) {
//             e.preventDefault();
//             e.stopPropagation();
//             const parentItem = this.closest('.has-dropdown');
//             const submenu = parentItem.querySelector('.submenu');

//             if (parentItem.classList.contains('active')) {
//                 // close this one
//                 submenu.style.height = '0px';
//                 parentItem.classList.remove('active');
//             } else {
//                 // close others first
//                 closeAllSubmenus(parentItem);
//                 // open this one
//                 parentItem.classList.add('active');
//                 submenu.style.height = submenu.scrollHeight + 'px';
//                 submenu.addEventListener('transitionend', function handler() {
//                     if (parentItem.classList.contains('active')) {
//                         submenu.style.height = 'auto';
//                     }
//                     submenu.removeEventListener('transitionend', handler);
//                 });
//             }
//         });
//     });

//     // Close button logic (adapt to your existing overlay system)
//     const closeBtn = document.getElementById('mobileSidebarClose');
//     if (closeBtn) {
//         closeBtn.addEventListener('click', function() {
//             document.querySelector('.m-sidebar-container')?.classList.remove('open');
//             // You can also trigger a custom event or hide an overlay here
//         });
//     }
// });

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