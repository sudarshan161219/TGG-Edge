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
                <a href="{{ route('tgg-india.freelancer.dashboard') }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/freelancer/dashboard') ? 'active' : '' }}">
                    <x-ri-dashboard-line class="icon" /> Dashboard
                </a>
            </li>

            <!-- Profile -->
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.freelancer.profile.index') }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/freelancer/profile') ? 'active' : '' }}">
                    <x-heroicon-o-user class="icon" /> Profile
                </a>
            </li>

            <!-- Switch Account -->
            @if ($otherAccounts->count() > 0)
            <li class="m-sidebar-item has-dropdown {{ request()->is('tgg-meta/tgg-india/freelancer/switch*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-arrow-path class="icon"/>
                        <span>Switch Account</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/freelancer/switch*') ? 'height:auto;' : '' }}">
                    @foreach ($otherAccounts as $account)
                    <li>
                        <a href="{{ route('tgg-india.switch.account', $account->id) }}"
                           target="_blank" class="submenu-link">
                            <x-heroicon-o-user-group class="submenu-icon" />
                            Switch to {{ ucfirst($account->role_name ?? 'N/A') }} Dashboard
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif

            <!-- Assignments -->
            @if ($assignments->count() > 0)
            <li class="m-sidebar-item">
                <a href="{{ route('tgg-india.freelancer.assignments.index') }}"
                   class="m-sidebar-link {{ request()->is('tgg-meta/tgg-india/freelancer/assignee/assignments*') ? 'active' : '' }}">
                    <x-heroicon-o-clipboard-document-list class="icon"/> Assignments
                </a>
            </li>
            @endif

            <!-- Advancement -->
            <li class="m-sidebar-item has-dropdown 
            {{ request()->is('tgg-meta/tgg-india/freelancer/invoices*') || request()->is('tgg-meta/tgg-india/freelancer/receipts*') ? 'active' : '' }}">
                
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-arrow-trending-up class="icon"/>
                        <span>Advancement</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/freelancer/invoices*') || request()->is('tgg-meta/tgg-india/freelancer/receipts*') ? 'height:auto;' : '' }}">
                    <li>
                        <a href="{{ route('tgg-india.freelancer.invoices.index') }}" class="submenu-link">
                            <x-heroicon-o-document-text class="submenu-icon"/> Invoice
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tgg-india.freelancer.receipts.index') }}" class="submenu-link">
                            <x-heroicon-o-receipt-percent class="submenu-icon"/> Receipt
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Campaign -->
            <li class="m-sidebar-item has-dropdown 
            {{ request()->is('tgg-meta/tgg-india/freelancer/*/templates*') ? 'active' : '' }}">
                
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-megaphone class="icon"/>
                        <span>Campaign</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/freelancer/*/templates*') ? 'height:auto;' : '' }}">
                    <li>
                        <a href="{{ route('tgg-india.campaigns.index', 'freelancer') }}" class="submenu-link">
                            <x-heroicon-o-paper-airplane class="submenu-icon"/> Campaigns
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tgg-india.email-check.index', 'freelancer') }}" class="submenu-link">
                            <x-heroicon-o-envelope class="submenu-icon"/> Email Check
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Lead Generation -->
            <li class="m-sidebar-item has-dropdown 
            {{ request()->is('tgg-meta/tgg-india/freelancer/referral/program*') || request()->is('tgg-india/freelancer/referral/tracking*') ? 'active' : '' }}">
                
                <a href="javascript:void(0)" class="m-sidebar-link dropdown-toggle">
                    <div class="dropdown-left">
                        <x-heroicon-o-user-group class="icon"/>
                        <span>Lead Generation</span>
                    </div>
                    <x-heroicon-o-chevron-right class="icon"/>
                </a>

                <ul class="submenu" style="{{ request()->is('tgg-meta/tgg-india/freelancer/referral/program*') || request()->is('tgg-india/freelancer/referral/tracking*') ? 'height:auto;' : '' }}">
                    <li>
                        <a href="{{ route('tgg-india.freelancer.referral.program') }}" class="submenu-link">
                            <x-heroicon-o-link class="submenu-icon"/> Lead Referral Link
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tgg-india.freelancer.enquiry.referral.tracking') }}" class="submenu-link">
                            <x-heroicon-o-chart-bar class="submenu-icon"/> Lead Generated Tracking
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

        <hr class="m-sidebar-divider">

        <!-- WEBSITE LINKS -->
        <h6 class="m-sidebar-section-title">TGG Website</h6>
        <ul class="m-sidebar-nav">
            <li><a href="https://tggindia.com/" class="m-sidebar-link">Home</a></li>
            <li><a href="https://tggindia.com/about-us/" class="m-sidebar-link">About Us</a></li>
            <li><a href="http://tggindia.com/our-services/" class="m-sidebar-link">Our Services</a></li>
            <li><a href="https://tggindia.com/journey-with-tgg/" class="m-sidebar-link">Journey with TGG</a></li>
            <li><a href="https://tggindia.com/blog-post/" class="m-sidebar-link">Blog</a></li>
            <li><a href="https://tggindia.com/login/" class="m-sidebar-link">Login</a></li>
            <li><a href="https://tggindia.com/contact-us/" class="m-sidebar-link">Contact Us</a></li>
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