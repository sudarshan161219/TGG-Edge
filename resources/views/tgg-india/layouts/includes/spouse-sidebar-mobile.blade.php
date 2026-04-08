<div class="m-sidebar-container">
    <!-- 1. HEADER -->
    <div class="m-sidebar-header">
        <div class="m-sidebar-logo">
            <a class="mobile-logo-link" href="{{ url('https://tggindia.com/') }}">
                <img class="mobile-logo"
                    src="https://tggindia.com/wp-content/uploads/2020/09/cropped-logo_png_final-1024x281.png"
                    alt="TGG India Logo">
            </a>

        </div>
        <button id="mobileSidebarClose" class="m-close-btn" aria-label="Close menu">
            <x-fontisto-close class="close-icon" />
        </button>
    </div>

    <!-- 2. BODY (Scrollable Area) -->
    <div class="m-sidebar-body">

        <!-- App Links -->
        <ul class="m-sidebar-nav">
            <li class="m-sidebar-item">
                <a href="" class="m-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <x-ri-dashboard-line class="icon" /> Dashboard
                </a>
            </li>
            <li class="m-sidebar-item">
                <a href="" class="m-sidebar-link">
                    <x-iconsax-lin-profile class="icon" /> My Profile
                </a>
            </li>

            <!-- Advancement (Dropdown) -->
            <li class="m-sidebar-item has-dropdown">
                <a href="javascript:void(0)" class="m-sidebar-link  dropdown-toggle">
                    <div class="dropdown-left">
                        <!-- <i class="fas fa-chart-line"></i> -->
                        <x-heroicon-o-arrow-trending-up class="icon" />

                        <span>Advancement</span>


                    </div>
                    <x-eva-arrow-ios-forward-outline class="icon" />

                </a>
                <ul class="submenu">
                    <li>
                        <a href="#" class="submenu-link">
                            <x-tni-invoice-o class="submenu-icon" /> Invoices
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link">
                            <x-lucide-receipt class="submenu-icon" /> Receipt
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Campaign (Dropdown) -->
            <li class="m-sidebar-item has-dropdown">
                <a href="javascript:void(0)" class="m-sidebar-link  dropdown-toggle">
                    <div class="dropdown-left">


                        <x-carbon-bullhorn class="icon" />
                        <span>Campaign</span>



                    </div>
                    <x-eva-arrow-ios-forward-outline class="icon" />

                </a>
                <ul class="submenu">
                    <li>
                        <a href="#" class="submenu-link">
                            <x-carbon-bullhorn class="submenu-icon" /> Campaigns
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link">
                            <x-eva-email-outline class="submenu-icon" /> Email check
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Lead Generation (Dropdown) -->
            <li class="m-sidebar-item has-dropdown">
                <a href="javascript:void(0)" class="m-sidebar-link  dropdown-toggle">
                    <div class="dropdown-left">

                        <x-simpleline-people class="icon" />
                        <span>Lead Generation</span>

                    </div>
                    <x-eva-arrow-ios-forward-outline class="icon" />

                </a>
                <ul class="submenu">
                    <li>
                        <a href="#" class="submenu-link">
                            <x-eva-person-add-outline class="submenu-icon" /> Lead Referral
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link">
                            <x-sui-graph-bar class="submenu-icon" /> Lead Generating Tracking
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <hr class="m-sidebar-divider">

        <!-- Website Links -->
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

    <!-- 3. FOOTER (Social Icons) -->
    <div class="m-sidebar-footer">
        <p class="m-footer-title">Follow Us</p>
        <div class="m-social-links">
            <a href="https://www.instagram.com/tggfamily/" class="m-social-icon" target="_blank">
                <x-entypo-instagram-with-circle />
            </a>
            <a href="https://www.facebook.com/TGGIndia" class="m-social-icon" target="_blank">
                <x-entypo-facebook-with-circle />
            </a>
            <a href="https://www.youtube.com/@tggindia" class="m-social-icon" target="_blank">
                <x-entypo-youtube-with-circle />
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

            // 1. IF ALREADY OPEN: Close it smoothly
            if (parentItem.classList.contains('active')) {
                // Temporarily set height to explicit pixels before crushing it to 0
                submenu.style.height = submenu.scrollHeight + 'px';

                // Force the browser to reflow so it registers the pixel height
                void submenu.offsetHeight;

                // Now trigger the animation to 0
                submenu.style.height = '0px';
                parentItem.classList.remove('active');
            }

            // 2. IF CLOSED: Open it smoothly
            else {
                parentItem.classList.add('active');
                // Calculate actual height of the hidden content and animate to it
                submenu.style.height = submenu.scrollHeight + 'px';

                // Optional cleanup: Once the animation finishes, switch height to 'auto' 
                // just in case the screen resizes and the content wraps/grows
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