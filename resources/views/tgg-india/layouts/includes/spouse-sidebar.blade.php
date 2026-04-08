<!-- @php
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
    $assignments = \App\Models\AssignmentSecondary::where('assigned_to', $user->id)->get();
@endphp -->

<aside class="sidebar">

    <!-- 1. TOGGLE BUTTON -->
    <!-- <div class="toggle-btn-container">
           <x-feathericon-sidebar class="sidebar-toggle-btn"/>
    </div> -->

    <!-- 2. PROFILE CARD -->
    <div class="profile-section">
        <div class="bg-color">
            <div class="avatar-container"> 
                <img src="{{ asset('images/avatar.jpg') }}" alt="User Avatar" class="profile-avatar" onerror="this.src='https://api.dicebear.com/9.x/lorelei/svg'">
            </div>
        </div>

        <div class="profile-card">
            <h3 class="profile-name">{{ Auth::user()->name ?? 'Ravi Kumar' }}</h3>
            <p class="profile-role">Role <span>Associate</span> </p>
            <p class="profile-id">RHM No: <span>RHM/KL/QLN/9999</span></p>
        </div>
    </div>

    <!-- 3. MAIN NAVIGATION -->
    <ul class="nav-menu">
        
        <!-- Dashboard -->
        <li class="nav-item {{ request()->routeIs('tgg-india.spouse.dashboard') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.spouse.dashboard') }}" class="nav-link sidebar-nav-link">
                <x-ri-dashboard-line class="icon" />
                <span class="nav-label">Dashboard</span> 
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item {{ request()->routeIs('tgg-india.spouse.profile.index') ? 'active' : '' }}">
            <a href="{{ route('tgg-india.spouse.profile.index') }}" class="nav-link sidebar-nav-link">
                <x-iconsax-lin-profile class="icon" />
                <span class="nav-label">Profile</span>
            </a>
        </li>

        <!-- Advancement (Dropdown) -->
        <li class="nav-item has-dropdown">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-heroicon-o-arrow-trending-up class="icon" />
                    <span class="nav-label">Advancement</span>
                </div>
                <x-eva-arrow-ios-forward-outline class="icon chevron-icon" />
            </a>
            <ul class="submenu">
                <li>
                    <a href="#" class="submenu-link">
                        <x-tni-invoice-o class="submenu-icon" /> 
                        <span class="nav-label">Invoices</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <x-lucide-receipt class="submenu-icon" /> 
                        <span class="nav-label">Receipt</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Campaign (Dropdown) -->
        <li class="nav-item has-dropdown">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-carbon-bullhorn class="icon" />
                    <span class="nav-label">Campaign</span>
                </div>
                <x-eva-arrow-ios-forward-outline class="icon chevron-icon" />
            </a>
            <ul class="submenu">
                <li>
                    <a href="#" class="submenu-link">
                        <x-carbon-bullhorn class="submenu-icon" /> 
                        <span class="nav-label">Campaigns</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <x-eva-email-outline class="submenu-icon" /> 
                        <span class="nav-label">Email check</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Lead Generation (Dropdown) -->
        <li class="nav-item has-dropdown">
            <a href="javascript:void(0)" class="nav-link sidebar-nav-link dropdown-toggle">
                <div class="dropdown-left">
                    <x-simpleline-people class="icon" />
                    <span class="nav-label">Lead Generation</span>
                </div>
                <x-eva-arrow-ios-forward-outline class="icon chevron-icon" />
            </a>
            <ul class="submenu">
                <li>
                    <a href="#" class="submenu-link">
                        <x-eva-person-add-outline class="submenu-icon" /> 
                        <span class="nav-label">Lead Referral</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <x-sui-graph-bar class="submenu-icon" /> 
                        <span class="nav-label">Lead Generating Tracking</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <!-- 4. LOGOUT BUTTON -->
    <div class="logout-section">
        <a href="#" class="logout-btn">
            <x-tni-logout-o class="logout-icon" />
            <span class="nav-label">Log out</span>
        </a>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();

            const parentItem = this.closest('.has-dropdown');
            const submenu = parentItem.querySelector('.submenu');

            if (!parentItem || !submenu) return;

            // 1. CLOSING (Now checking for 'is-open' instead of 'active')
            if (parentItem.classList.contains('is-open')) {
                submenu.style.height = submenu.scrollHeight + 'px';
                void submenu.offsetHeight; // Force reflow

                // Wait 1 frame for ultra-smooth closing
                requestAnimationFrame(() => {
                    submenu.style.height = '0px';
                });

                parentItem.classList.remove('is-open');
            }

            // 2. OPENING
            else {
                parentItem.classList.add('is-open');

                const targetHeight = submenu.scrollHeight;

                requestAnimationFrame(() => {
                    submenu.style.height = targetHeight + 'px';
                });

                submenu.addEventListener('transitionend', function handler(e) {
                    if (e.propertyName === 'height' && parentItem.classList.contains(
                            'is-open')) {
                        submenu.style.height = 'auto';
                    }
                    submenu.removeEventListener('transitionend', handler);
                });
            }
        });
    });
});
</script>

