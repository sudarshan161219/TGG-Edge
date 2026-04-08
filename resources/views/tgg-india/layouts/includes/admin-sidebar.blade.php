{{-- @php
    if(auth()->user()->user_role == 1){
        $dashboardRoute = route('user.admin-dashboard'); 

    }elseif(auth()->user()->user_role == 2){
        $dashboardRoute = route('user.researcher-dashboard'); 

    }elseif(auth()->user()->user_role == 3){
        $dashboardRoute = route('user.volunteer-dashboard'); 

    }else{
        $dashboardRoute = route('user.dashboard'); 
    } 
@endphp --}}

<aside class="sidebar">

    <!-- PROFILE -->
    <div class="profile-section">
        <div class="bg-color">
            <div class="avatar-container">
                <img src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}" class="profile-avatar">
            </div>
        </div>

        <div class="profile-card">
            <h3 class="profile-name">{{ Auth::user()->name ?? 'Admin' }}</h3>
            <p class="profile-role">Role <span>Admin</span></p>
            <p class="profile-id">RHM No: <span>{{ Auth::user()->rhm_number ?? 'N/A' }}</span></p>
        </div>
    </div>

    <ul class="nav-menu">

        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('tgg-meta/tgg-india/dashboard') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.admin.dashboard') }}" class="nav-link sidebar-nav-link">
                <x-ri-dashboard-line class="icon"/>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item {{ request()->is('user/profile') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.admin.profile.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-user class="icon"/>
                <span class="nav-label">Profile</span>
            </a>
        </li>

        <!-- Showcase -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-meta/tgg-india/admin/showcases*') ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-rectangle-stack class="icon"/>
                    <span class="nav-label">Showcase</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/admin/showcases*') ? 'height:auto;' : '' }}">

                <li><a href="{{ route('tgg-india.admin.showcases.welcome-notes.edit') }}#welcome-notes" class="submenu-link">
                    <span class="nav-label">Welcome Notes</span></a></li>

                <li><a href="{{ route('tgg-india.admin.showcases.collaborative-projects.edit') }}#collaborative-projects" class="submenu-link">
                    <span class="nav-label">Collaborative Projects</span></a></li>

                <li><a href="{{ route('tgg-india.admin.showcases.main-projects.edit') }}#main-projects" class="submenu-link">
                    <span class="nav-label">Main Projects</span></a></li>

                <li><a href="{{ route('tgg-india.admin.showcases.freelance-opportunities.edit') }}#freelance-opportunities" class="submenu-link">
                    <span class="nav-label">Freelance Opportunities</span></a></li>

                <li><a href="{{ route('tgg-india.admin.showcases.reward.edit') }}#freelance-opportunities" class="submenu-link">
                    <span class="nav-label">Reward Program Content</span></a></li>

                <!-- Referral Program -->
                <li class="nav-item has-dropdown">
                    <a href="javascript:void(0)" class="submenu-link nav-link sidebar-nav-link dropdown-toggle">
                        <span class="nav-label">Referral Program</span>
                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                    </a>

                    <ul class="submenu">
                        <li><a href="{{ route('tgg-india.admin.showcases.referral.edit') }}#main-projects" class="submenu-link">Admin Description</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-link.edit',['admin']) }}" class="submenu-link">Admin Link</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-description.edit',['associate']) }}#main-projects" class="submenu-link">Associate Description</a></li>
                        <li><a href="#" class="submenu-link">Associate Link</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-description.edit',['co-creator'])}}" class="submenu-link">Co-Creator Description</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-link.edit',['co-creator']) }}" class="submenu-link">Co-Creator Link</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-description.edit',['spouse'])}}" class="submenu-link">Spouse Description</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-link.edit',['spouse']) }}" class="submenu-link">Spouse Link</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-description.edit',['facilitator'])}}" class="submenu-link">Facilitator Description</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-link.edit',['facilitator']) }}" class="submenu-link">Facilitator Link</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-description.edit',['freelancer'])}}" class="submenu-link">Freelancer Description</a></li>
                        <li><a href="{{ route('tgg-india.admin.showcases.referral-link.edit',['freelancer']) }}" class="submenu-link">Freelancer Link</a></li>
                    </ul>
                </li>

                <!-- Onboarding Links -->
                <li class="nav-item has-dropdown">
                    <a href="javascript:void(0)" class="submenu-link nav-link sidebar-nav-link dropdown-toggle">
                        <span class="nav-label">Onboarding Links</span>
                        <x-heroicon-o-chevron-right class="icon chevron-icon"/>
                    </a>

                    <ul class="submenu">
                        <li><a href="https://thegoldengreens.com/tgg-meta/tgg-india/onboarding/DKJSFH3489SDFLSJDFPLKLDSJFL75934RU/3" target="_blank" class="submenu-link">Associate</a></li>
                        <li><a href="https://thegoldengreens.com/tgg-meta/tgg-india/onboarding/DKJSFH3489SDFLSJDFPLKLDSJFL75934RU/6" target="_blank" class="submenu-link">Freelancer</a></li>
                        <li><a href="https://thegoldengreens.com/tgg-meta/tgg-india/onboarding/DKJSFH3489SDFLSJDFPLKLDSJFL75934RU/7" target="_blank" class="submenu-link">Co Creator</a></li>
                        <li><a href="https://thegoldengreens.com/tgg-meta/tgg-india/onboarding/DKJSFH3489SDFLSJDFPLKLDSJFL75934RU/8" target="_blank" class="submenu-link">Facilitator</a></li>
                    </ul>
                </li>

            </ul>
        </li>

        <!-- Assignments -->
        <li class="nav-item">
            <a href="{{ route('tgg-india.admin.assignments.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-clipboard-document-list class="icon"/>
                <span class="nav-label">Assignments</span>
            </a>
        </li>

        <!-- Venture -->
        <li class="nav-item">
            <a href="{{ route('tgg-india.venture-bench-services.index',['role' => auth('web2')->user()->role_key ]) }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-check-badge class="icon"/>
                <span class="nav-label">Venture Bench Support</span>
            </a>
        </li>

        <!-- Advancement -->
        @php
            $isAdvancementActive =
                request()->is('tgg-meta/tgg-india/admin/incentives*') ||
                request()->is('tgg-meta/tgg-india/admin/rewards*') ||
                request()->is('tgg-meta/tgg-india/admin/donations*') ||
                request()->is('tgg-meta/tgg-india/admin/payments*') ||
                request()->is('tgg-meta/tgg-india/admin/invoices*') ||
                request()->is('tgg-meta/tgg-india/admin/receipts*');
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
                <li><a href="{{ route('tgg-india.admin.incentives.index') }}" class="submenu-link">Incentive</a></li>
                <li><a href="{{ route('tgg-india.admin.rewards.index') }}" class="submenu-link">Reward</a></li>
                <li><a href="{{ route('tgg-india.admin.donations.index') }}" class="submenu-link">Donation</a></li>
                <li><a href="{{ route('tgg-india.admin.invoices.index') }}" class="submenu-link">Invoice</a></li>
                <li><a href="{{ route('tgg-india.admin.receipts.index') }}" class="submenu-link">Receipt</a></li>
            </ul>
        </li>

        <!-- Campaign -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-meta/tgg-india/*/templates*') ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-megaphone class="icon"/>
                    <span class="nav-label">Campaign</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/*/templates*') ? 'height:auto;' : '' }}">
                <li><a href="{{ route('tgg-india.templates.index', 'admin') }}" class="submenu-link">Templates</a></li>
                <li><a href="{{ route('tgg-india.campaigns.index', 'admin') }}" class="submenu-link">Campaigns</a></li>
                <li><a href="{{ route('tgg-india.email-check.index', 'admin') }}" class="submenu-link">Email Check</a></li>
            </ul>
        </li>

        <!-- Sitemap Links -->
        <li class="nav-item has-dropdown">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-ri-map-line class="icon" />
                    <span class="nav-label">Links (Sitemap)</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu">
                <li><a href="{{ route('tgg-india.show') }}" class="submenu-link" target="_blank">Login</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/trainer/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Trainer Register</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/associate/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Associates Register</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/rhm-club/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Rhm Club Register</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/co-creator/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Co-Creator Register</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/facilitator/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Facilitator Register</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/freelancer/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Freelancers Register</a></li>
                <li><a href="{{ url('tgg-meta/tgg-india/register/spouse/DSLKFN43KREFWLDCMXKLWNEMR34RKL32NWMEDKQWJASNCKNRWDECNK3EW') }}" class="submenu-link" target="_blank">Spouse Register</a></li>
                <li><a href="{{ url('https://www.modicare.com/sign-in') }}" class="submenu-link" target="_blank">Modicare Register</a></li>
                <li><a href="{{ url('https://invest.motilaloswal.com/') }}" class="submenu-link" target="_blank">Motilaloswal Register</a></li>
                <li><a href="{{ url('https://pos.insureeasy.in/') }}" class="submenu-link" target="_blank">India Insure Register</a></li>
                <li><a href="{{ url('user/login/') }}" class="submenu-link" target="_blank">TGG Foundation</a></li>
            </ul>
        </li>

        <!-- Modules -->
        <li class="nav-item">
            <a href="{{ route('tgg-india.admin.modules.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-cube class="icon"/>
                <span class="nav-label">Modules</span>
            </a>
        </li>

        <!-- Feature Limits -->
        <li class="nav-item">
            <a href="{{ route('tgg-india.admin.feature-limits.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-adjustments-horizontal class="icon"/>
                <span class="nav-label">Feature Limits</span>
            </a>
        </li>

        <!-- Applications -->
        <li class="nav-item has-dropdown {{ request()->is('user/new-applications*') || request()->is('user/processed-applications*') ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-document-text class="icon"/>
                    <span class="nav-label">Applications</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('user/new-applications*') || request()->is('user/processed-applications*') ? 'height:auto;' : '' }}">
                <li><a href="{{ route('tgg-india.admin.new-applications') }}" class="submenu-link">New Applications</a></li>
                <li><a href="{{ route('tgg-india.admin.processed-applications') }}" class="submenu-link">Processed Applications</a></li>
            </ul>
        </li>

        <!-- Referral -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-meta/tgg-india/admin/referral-program*') || request()->is('tgg-meta/tgg-india/admin/referral-tracking*') || request()->is('tgg-meta/tgg-india/admin/enquiry/referral/tracking*') ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-share class="icon"/>
                    <span class="nav-label">Referral</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/admin/referral-program*') || request()->is('tgg-meta/tgg-india/admin/referral-tracking*') || request()->is('tgg-meta/tgg-india/admin/enquiry/referral/tracking*') ? 'height:auto;' : '' }}">
                <li><a href="{{ route('tgg-india.admin.referral.program') }}" class="submenu-link">Referral Program</a></li>
                <li><a href="{{ route('tgg-india.admin.referral.tracking') }}" class="submenu-link">Referral Tracking</a></li>
                <li><a href="{{ route('tgg-india.admin.enquiry.referral.tracking') }}" class="submenu-link">Lead Generated Tracking</a></li>
            </ul>
        </li>

        <!-- FAQ Management -->
        <li class="nav-item has-dropdown {{ request()->is('tgg-india/admin/faq*') ? 'is-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-question-mark-circle class="icon"/>
                    <span class="nav-label">FAQ Management</span>
                </div>
                <x-heroicon-o-chevron-right class="icon chevron-icon"/>
            </a>

            <ul class="submenu" style="{{ request()->is('tgg-india/admin/faq*') ? 'height:auto;' : '' }}">
                <li><a href="{{ route('tgg-india.admin.faq-categories.index') }}" class="submenu-link">Categories</a></li>
                <li><a href="{{ route('tgg-india.admin.faqs.index') }}" class="submenu-link">All FAQs</a></li>
            </ul>
        </li>

        <!-- Report Builder -->
        <li class="nav-item">
            <a href="{{ route('tgg-india.report-builder') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-chart-bar class="icon"/>
                <span class="nav-label">Report Builder</span>
            </a>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a href="{{ route('tgg-india.admin.settings.index') }}" class="nav-link sidebar-nav-link">
                <x-heroicon-o-cog-6-tooth class="icon"/>
                <span class="nav-label">Settings</span>
            </a>
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