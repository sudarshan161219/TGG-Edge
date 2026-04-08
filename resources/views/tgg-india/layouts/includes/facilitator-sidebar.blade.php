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
            <h3 class="profile-name">{{ $user->name }}</h3>
            <p class="profile-role">Role <span>Facilitator</span></p>
            <p class="profile-id">RHM No: <span>{{ $user->rhm_number }}</span></p>
        </div>
    </div>

    <!-- NAV -->
    <ul class="nav-menu">

        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('tgg-meta/tgg-india/facilitator/dashboard') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.facilitator.dashboard') }}" class="nav-link sidebar-nav-link">
                <x-ri-dashboard-line class="icon"/>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item {{ request()->is('tgg-meta/tgg-india/facilitator/profile') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.facilitator.profile.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-user class="icon"/>
                <span class="nav-label">Profile</span>
            </a>
        </li>

        <!-- Switch Account -->
        @if ($otherAccounts->count() > 0)
        <li class="nav-item {{ request()->is('tgg-meta/tgg-india/facilitator/profile') ? 'active' : '' }} has-dropdown {{ request()->is('tgg-meta/tgg-india/facilitator/switch*') ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-arrow-path class="icon"/>
                    <span class="nav-label">Switch Account</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/facilitator/switch*') ? 'height:auto;' : '' }}">
                @foreach ($otherAccounts as $account)
                <li>
                    <a href="{{ route('tgg-india.switch.account', $account->id) }}" target="_blank" class="submenu-link">
                        <x-heroicon-o-user-group class="submenu-icon" />
                        <span class="nav-label">
                            Switch to {{ ucfirst($account->role_name ?? 'N/A') }} Dashboard
                        </span>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        @endif

        <!-- Assignments -->
        @if ($assignments->count() > 0)
        <li class="nav-item {{ request()->is('tgg-meta/tgg-india/facilitator/assignee/assignments*') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.facilitator.assignments.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-clipboard-document-list class="icon"/>
                <span class="nav-label">Assignments</span>
            </a>
        </li>
        @endif

        <!-- Campaign -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-meta/tgg-india/facilitator/*/templates*') ? 'active is-open' : '' }}">
            
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-megaphone class="icon"/>
                    <span class="nav-label">Campaign</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/facilitator/*/templates*') ? 'height:auto;' : '' }}">
                
                <li>
                    <a href="{{ route('tgg-india.campaigns.index', 'facilitator') }}" class="submenu-link">
                        <x-heroicon-o-paper-airplane class="submenu-icon"/>
                        <span class="nav-label">Campaigns</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tgg-india.email-check.index', 'facilitator') }}" class="submenu-link">
                        <x-heroicon-o-envelope class="submenu-icon"/>
                        <span class="nav-label">Email Check</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Advancement -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-meta/tgg-india/facilitator/invoices*') || request()->is('tgg-meta/tgg-india/facilitator/receipts*') ? 'active is-open' : '' }}">
            
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-arrow-trending-up class="icon"/>
                    <span class="nav-label">Advancement</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/facilitator/invoices*') || request()->is('tgg-meta/tgg-india/facilitator/receipts*') ? 'height:auto;' : '' }}">
                
                <li>
                    <a href="{{ route('tgg-india.facilitator.invoices.index') }}" class="submenu-link">
                        <x-heroicon-o-document-text class="submenu-icon"/>
                        <span class="nav-label">Invoice</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tgg-india.facilitator.receipts.index') }}" class="submenu-link">
                        <x-heroicon-o-receipt-percent class="submenu-icon"/>
                        <span class="nav-label">Receipt</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Lead Generation -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-meta/tgg-india/facilitator/referral/program*') || request()->is('tgg-india/facilitator/referral/tracking*') ? 'active is-open' : '' }}">
            
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-user-group class="icon"/>
                    <span class="nav-label">Lead Generation</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/facilitator/referral/program*') || request()->is('tgg-india/facilitator/referral/tracking*') ? 'height:auto;' : '' }}">
                
                <li>
                    <a href="{{ route('tgg-india.facilitator.referral.program') }}" class="submenu-link">
                        <x-heroicon-o-link class="submenu-icon"/>
                        <span class="nav-label">Lead Referral Link</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tgg-india.facilitator.enquiry.referral.tracking') }}" class="submenu-link">
                        <x-heroicon-o-chart-bar class="submenu-icon"/>
                        <span class="nav-label">Lead Generated Tracking</span>
                    </a>
                </li>

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