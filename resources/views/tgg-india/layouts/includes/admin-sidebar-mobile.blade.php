<div class="m-sidebar-container">

    <!-- HEADER -->
    <div class="m-sidebar-header">
        <div class="m-sidebar-logo">
            <a class="mobile-logo-link" href="{{ url('https://tggindia.com/') }}">
                <img class="mobile-logo"
                    src="{{ asset('assets/tgg-india/images/svg-viewer.svg') }}"
                    alt="TGG India Logo">
            </a>
        </div>

        <button id="mobileSidebarClose" class="m-close-btn" aria-label="Close menu">
            <x-heroicon-o-x-mark class="close-icon" />
        </button>
    </div>

    <!-- BODY -->
    <div class="m-sidebar-body">

        <ul class="m-sidebar-nav">

            <!-- Dashboard -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.admin.dashboard') }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/dashboard') ? 'active' : '' }}">
                    <x-ri-dashboard-line class="icon"/> Dashboard
                </a>
            </li>

            <!-- Profile -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.admin.profile.index') }}"
                   class="m-sidebar-link {{ request()->is('user/profile') ? 'active' : '' }}">
                    <x-heroicon-o-user class="icon"/> Profile
                </a>
            </li>

            <!-- SHOWCASE -->
            <li class="m-sidebar-item has-dropdown {{ request()->is('tgg-meta/tgg-india/admin/showcases*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-rectangle-stack class="icon"/>
                        <span>Showcase</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/admin/showcases*') ? 'height:auto;' : '' }}">

                    <li><a href="{{ route('tgg-india.admin.showcases.welcome-notes.edit') }}#welcome-notes" class="submenu-link">Welcome Notes</a></li>
                    <li><a href="{{ route('tgg-india.admin.showcases.collaborative-projects.edit') }}#collaborative-projects" class="submenu-link">Collaborative Projects</a></li>
                    <li><a href="{{ route('tgg-india.admin.showcases.main-projects.edit') }}#main-projects" class="submenu-link">Main Projects</a></li>
                    <li><a href="{{ route('tgg-india.admin.showcases.freelance-opportunities.edit') }}#freelance-opportunities" class="submenu-link">Freelance Opportunities</a></li>
                    <li><a href="{{ route('tgg-india.admin.showcases.reward.edit') }}#freelance-opportunities" class="submenu-link">Reward Program Content</a></li>

                    <!-- Referral Program -->
                    <li class="has-dropdown">
                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                            Referral Program
                            <x-heroicon-o-chevron-right class="icon"/>
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
                    <li class="has-dropdown">
                        <a href="javascript:void(0)" class="submenu-link dropdown-toggle">
                            Onboarding Links
                            <x-heroicon-o-chevron-right class="icon"/>
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
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.admin.assignments.index') }}"
                   class="m-sidebar-link {{ request()->is('user/knowledge-research') ? 'active' : '' }}">
                    <x-heroicon-o-clipboard-document-list class="icon"/> Assignments
                </a>
            </li>

            <!-- Venture Bench Support -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.venture-bench-services.index',['role' => auth('web2')->user()->role_key ]) }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/venture-bench-services') ? 'active' : '' }}">
                    <x-heroicon-o-check-badge class="icon"/> Venture Bench Support
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

            <li class="m-sidebar-item has-dropdown {{ $isAdvancementActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-arrow-trending-up class="icon"/>
                        <span>Advancement</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
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
            <li class="m-sidebar-item has-dropdown {{ request()->is('tgg-meta/tgg-india/*/templates*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-megaphone class="icon"/>
                        <span>Campaign</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/*/templates*') ? 'height:auto;' : '' }}">
                    <li><a href="{{ route('tgg-india.templates.index','admin') }}" class="submenu-link">Templates</a></li>
                    <li><a href="{{ route('tgg-india.campaigns.index','admin') }}" class="submenu-link">Campaigns</a></li>
                    <li><a href="{{ route('tgg-india.email-check.index','admin') }}" class="submenu-link">Email Check</a></li>
                </ul>
            </li>

            <!-- Sitemap Links -->
            <li class="m-sidebar-item has-dropdown">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-ri-map-line class="icon" />
                        <span>Links (Sitemap)</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
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
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.admin.modules.index') }}"
                   class="m-sidebar-link {{ request()->is('tgg-india/admin/modules*') ? 'active' : '' }}">
                    <x-heroicon-o-cube class="icon"/> Modules
                </a>
            </li>

            <!-- Feature Limits -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.admin.feature-limits.index') }}"
                   class="m-sidebar-link {{ request()->is('tgg-india/admin/feature-limits*') ? 'active' : '' }}">
                    <x-heroicon-o-adjustments-horizontal class="icon"/> Feature Limits
                </a>
            </li>

            <!-- Applications -->
            <li class="m-sidebar-item has-dropdown {{ request()->is('user/new-applications*') || request()->is('user/processed-applications*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-document-text class="icon"/>
                        <span>Applications</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('user/new-applications*') || request()->is('user/processed-applications*') ? 'height:auto;' : '' }}">
                    <li><a href="{{ route('tgg-india.admin.new-applications') }}" class="submenu-link">New Applications</a></li>
                    <li><a href="{{ route('tgg-india.admin.processed-applications') }}" class="submenu-link">Processed Applications</a></li>
                </ul>
            </li>

            <!-- Referral -->
            <li class="m-sidebar-item has-dropdown {{ request()->is('tgg-meta/tgg-india/admin/referral-program*') || request()->is('tgg-meta/tgg-india/admin/referral-tracking*') || request()->is('tgg-meta/tgg-india/admin/enquiry/referral/tracking*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-share class="icon"/>
                        <span>Referral</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/admin/referral-program*') || request()->is('tgg-meta/tgg-india/admin/referral-tracking*') || request()->is('tgg-meta/tgg-india/admin/enquiry/referral/tracking*') ? 'height:auto;' : '' }}">
                    <li><a href="{{ route('tgg-india.admin.referral.program') }}" class="submenu-link">Referral Program</a></li>
                    <li><a href="{{ route('tgg-india.admin.referral.tracking') }}" class="submenu-link">Referral Tracking</a></li>
                    <li><a href="{{ route('tgg-india.admin.enquiry.referral.tracking') }}" class="submenu-link">Lead Generated Tracking</a></li>
                </ul>
            </li>

            <!-- FAQ Management -->
            <li class="m-sidebar-item has-dropdown {{ request()->is('tgg-india/admin/faq*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-question-mark-circle class="icon"/>
                        <span>FAQ Management</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-india/admin/faq*') ? 'height:auto;' : '' }}">
                    <li><a href="{{ route('tgg-india.admin.faq-categories.index') }}" class="submenu-link">Categories</a></li>
                    <li><a href="{{ route('tgg-india.admin.faqs.index') }}" class="submenu-link">All FAQs</a></li>
                </ul>
            </li>

            <!-- Report Builder -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.report-builder') }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/report-builder') ? 'active' : '' }}">
                    <x-heroicon-o-chart-bar class="icon"/> Report Builder
                </a>
            </li>

            <!-- Settings -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.admin.settings.index') }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/settings*') ? 'active' : '' }}">
                    <x-heroicon-o-cog-6-tooth class="icon"/> Settings
                </a>
            </li>

            <!-- Logout -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.logout') }}"
                   class="m-sidebar-link">
                    <x-heroicon-o-arrow-right-on-rectangle class="icon"/> Log out
                </a>
            </li>

        </ul>

    </div>

    <!-- FOOTER -->
    <div class="m-sidebar-footer">
        <p class="m-footer-title">Follow Us</p>
        <div class="m-social-links">
            <a href="https://www.instagram.com/tggfamily/" class="m-social-icon" target="_blank">
                <x-ri-instagram-fill />
            </a>
            <a href="https://www.facebook.com/TGGIndia" class="m-social-icon" target="_blank">
                <x-ri-facebook-fill />
            </a>
            <a href="https://www.youtube.com/@tggindia" class="m-social-icon" target="_blank">
                <x-ri-youtube-fill />
            </a>
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