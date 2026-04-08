@extends('tgg-india.layouts.app')

@section('title', 'Dashboard | TGG Meta | TGG India')

@php
    use App\Models\Incentive;
    use App\Models\Reward;
    use App\Models\Donation;
    use App\Models\Payment;
    use App\Models\Invoice;
    use App\Models\Receipt;
    use App\Models\Referral;
    use App\Models\Enquiry;
    use App\Models\AssignmentSecondary;

    use Illuminate\Support\Facades\Schema;

    $invoiceStatus = Invoice::select('status', \DB::raw('count(*) as total'))
        ->where('source_id', auth('web2')->id())
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    // ✅ Receipt Status
    $receiptStatus = Receipt::select('status', \DB::raw('count(*) as total'))
        ->where('source_id', auth('web2')->id())
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    // ✅ Assignment Status
    $assignmentStatus = AssignmentSecondary::select('status', \DB::raw('count(*) as total'))
        ->where(function ($query) {
            $query->where('created_by', auth('web2')->id())->orWhere('assigned_to', auth('web2')->id());
        })
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    // ✅ Assignment Task Type Breakdown
    $assignmentTaskType = AssignmentSecondary::select('task_type', \DB::raw('count(*) as total'))
        ->where('created_by', auth('web2')->id())
        ->orWhere('assigned_to', auth('web2')->id())
        ->groupBy('task_type')
        ->pluck('total', 'task_type')
        ->toArray();

    // ✅ Assignment Count
    $assignmentCount = AssignmentSecondary::where(function ($query) {
        $query->where('created_by', auth('web2')->id())->orWhere('assigned_to', auth('web2')->id());
    })
        ->whereNull('parent_id')
        ->count();

    $mainAssignmentCount = AssignmentSecondary::where(function ($query) {
        $query->where('created_by', auth('web2')->id())->orWhere('assigned_to', auth('web2')->id());
    })->count();

    $subAssignmentCount = AssignmentSecondary::where(function ($query) {
        $query->where('created_by', auth('web2')->id())->orWhere('assigned_to', auth('web2')->id());
    })
        ->where('parent_id', '!=', null)
        ->count();

    // ✅ Enquiry Count
    $enquiryCount = Enquiry::where('referral_code', auth('web2')->user()->referral_code)->count();

    // ✅ Safe amount calculations
    if (Schema::connection('mysql2')->hasColumn('invoices', 'items')) {
        $invoiceAmount = Invoice::where('source_id', auth('web2')->id())
            ->get()
            ->sum(function ($invoice) {
                $items = $invoice->items;
                if (is_array($items)) {
                    return collect($items)->sum('amount');
                }
                return 0;
            });
    } else {
        $invoiceAmount = 0;
    }

    if (Schema::connection('mysql2')->hasColumn('receipts', 'items')) {
        $receiptAmount = Receipt::where('source_id', auth('web2')->id())
            ->get()
            ->sum(function ($receipt) {
                $items = $receipt->items;
                if (is_array($items)) {
                    return collect($items)->sum('amount');
                }
                return 0;
            });
    } else {
        $receiptAmount = 0;
    }

    // ==================== SUMMARY ARRAY ====================
    $summaryStats = [
        [
            'icon' => 'ri-receipt-line',
            'count' => array_sum($invoiceStatus),
            'label' => 'Invoices',
            'color' => '#155DFC',
            'bg' => '#EFF6FF',
            'amount' => $invoiceAmount,
            'statuses' => $invoiceStatus,
        ],
        [
            'icon' => 'ri-file-text-line',
            'count' => array_sum($receiptStatus),
            'label' => 'Receipts',
            'color' => '#9810FA',
            'bg' => '#FAF5FF',
            'amount' => $receiptAmount,
            'statuses' => $receiptStatus,
        ],
        [
            'icon' => 'ri-team-line',
            'count' => $mainAssignmentCount,
            'label' => 'Assignments',
            'color' => '#00A63E',
            'bg' => '#F0FDF4',
            'statuses' => $assignmentStatus,
        ],
        [
            'icon' => 'ri-heart-line',
            'count' => $enquiryCount,
            'label' => 'Enquiries',
            'color' => '#F54900',
            'bg' => '#FFEDD4',
        ],
    ];

    $user = \App\Models\UserSecondary::find(auth('web2')->id());
    
    $announcements = [
        [
            'title' => 'New Partner Program Launch, Checkout for more details',
            'date' => \Carbon\Carbon::parse('2026-03-01'),
            'views' => 200
        ],
        [
            'title' => 'TGG new Service added',
            'date' => \Carbon\Carbon::parse('2026-03-01'),
            'views' => 200
        ],
        [
            'title' => 'Lets save the world with TGG',
            'date' => \Carbon\Carbon::parse('2026-03-01'),
            'views' => 200
        ]
    ];

    $recentOrders = [
        [
            'order_number' => 'Order#001',
            'icon' => 'ri-shopping-cart-2-line',
            'customer' => 'Rahul',
            'date' => now()->format('M d, Y'),
            'status' => 'Pending',
            'text_color' => '#b45309',
            'bg_color' => '#fef3c7',
        ],
        [
            'order_number' => 'Order#002',
            'icon' => 'ri-shopping-cart-2-line',
            'customer' => 'Priya',
            'date' => now()->format('M d, Y'),
            'status' => 'Completed',
            'text_color' => '#15803d',
            'bg_color' => '#dcfce7',
        ],
        [
            'order_number' => 'Order#003',
            'icon' => 'ri-shopping-cart-2-line',
            'customer' => 'Amit',
            'date' => now()->format('M d, Y'),
            'status' => 'Processing',
            'text_color' => '#1d4ed8',
            'bg_color' => '#dbeafe',
        ]
    ];

    $happinessProgram = [
        [
            'card-image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=600&q=80',
            'icon' => 'ri-gift-line',
            'icon-color' => '#E60076',
            'heading' => 'Art Of Gifting',
            'para' => 'Discover curated gift collections and meaningful presents to...',
            'link' => 'https://happiness.org/meditation'
        ],
        [
            'card-image' => 'https://images.unsplash.com/photo-1518241353330-0f7941c2d9b5?auto=format&fit=crop&w=600&q=80',
            'icon' => 'ri-flight-takeoff-line',
            'icon-color' => '#155DFC',
            'heading' => 'Travel News and Updates',
            'para' => 'Stay informed with the latest travel deals, destination guides,',
            'link' => 'https://happiness.org/breathwork'
        ],
        [
            'card-image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=600&q=80',
            'icon' => 'ri-hand-heart-line',
            'icon-color' => '#000000',
            'heading' => 'Upcoming Projects',
            'para' => 'Stay informed with the latest travel deals, destination guides',
            'link' => 'https://happiness.org/community'
        ]
    ];

    $RevenueReadyKitData = [
        [
            'icon' => 'ri-shopping-bag-3-line',
            'bg' => '#1F3C88',
            'color' => '#FFFFFF',
            'title' => 'Direct Selling',
            'desc' => 'Sell directly and earn through personal networks.',
            'link' => '#',
            'link-icon' => 'ri-arrow-right-up-line'
        ],
        [
            'icon' => 'ri-line-chart-line',
            'bg' => '#1F3C88',
            'color' => '#FFFFFF',
            'title' => 'Investments',
            'desc' => 'Grow your wealth with smart investment choices.',
            'link' => '#',
            'link-icon' => 'ri-arrow-right-up-line'
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#1F3C88',
            'color' => '#FFFFFF',
            'title' => 'Insurance',
            'desc' => 'Secure your future with reliable protection plans.',
            'link' => '#',
            'link-icon' => 'ri-arrow-right-up-line'
        ]
    ];

    $servicesData = [
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#EFF6FF',
            'color' => '#155DFC',
            'title' => 'Web Development',
            'desc' => 'Financial services firm offering wealth management solutions.',
            'link' => '#',
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#FDF2F8',
            'color' => '#E60076',
            'title' => 'Digital Marketing',
            'desc' => 'Financial services firm offering wealth management solutions.',
            'link' => '#',
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#DBEAFE',
            'color' => '#033576',
            'title' => 'Legal Support',
            'desc' => 'Comprehensive insurance solutions for individuals and businesses.',
            'link' => '#',
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#00A63E1A',
            'color' => '#00A63E',
            'title' => 'TGG News',
            'desc' => 'Our core non-profit initiative for sustainable development.',
            'link' => '#',
        ],
    ];

    $freelanceOpportunity = [
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#DBEAFE',
            'color' => '#033576',
            'title' => 'LinkedIn Promotion',
            'desc' => 'Use AI tools to boost reach',
            'link' => '#',
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#FFEDD4',
            'color' => '#F54900',
            'title' => 'IRDA License Holder',
            'desc' => 'Insurance POSP opportunities',
            'link' => '#',
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#F3E8FF',
            'color' => '#9810FA',
            'title' => 'Trainer - Biz Dev',
            'desc' => 'Train new associates',
            'link' => '#',
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#DCFCE7',
            'color' => '#00A63E',
            'title' => 'Content Writer',
            'desc' => 'Eco-blogging contributions',
            'link' => '#',
        ]
    ];

    $newsArticles = [
        [
            'image' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=600&q=80',
            'title' => 'New Solar Initiative Launched for Rural Partners',
            'desc' => 'Empowering rural communities with sustainable energy solutions and new partnership opportunities.',
            'link' => '#'
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
            'title' => 'Digital Marketing Strategies for Associate Success in 2026',
            'desc' => 'Stay ahead of the curve with our latest guide on leveraging AI and social platforms for growth.',
            'link' => '#'
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80',
            'title' => 'Building Stronger Teams Through Collaboration and Trust',
            'desc' => 'Discover actionable insights on fostering a culture of teamwork and mutual respect in the workplace.',
            'link' => '#'
        ]
    ];
@endphp

@section('content')
<main>
    <div class="heading-container">
        <h1>Dashboard</h1>
        <p>
            <x-ri-calendar-event-line class="icon" />Today: {{ now()->format('M d, Y') }}
        </p>
    </div>

    <div class="top-section">
        <div class="top-section-container-left">
            <div class="welcome-card">
                <h2>Welcome to {{ $user->name ?? 'Associate' }}</h2>
                <p><span>Welcome to TGG Meta—</span>
                    {!! $showcase->welcome_note_member ?? 'a space for responsible humans to transform their lives through ethical entrepreneurship and collective action. Anchor your journey in The Power of 5 and The Art of Gifting.' !!}</p>
            </div>
            <!-- <div class="active-projects">
                <h2>My Projects</h2>
                <ul class="active-projects-list">
                    <li>
                        <h4>Your ongoing Projects</h4>
                        <div><span class="badge"></span> <span class="badge-status-text">{{ $mainAssignmentCount }} Active</span></div>
                    </li>
                </ul>
            </div> -->
        </div>

        <div class="top-section-container-right">
            <div class="announcement-header">
                <h2>Latest Announcements</h2>
                <a href="">View All</a>
            </div>

            <ul class="latest-announcements-list">
                @foreach($announcements as $announcement)
                <li>
                    <h4 style="margin: 0 0 5px 0;">{{ $announcement['title'] }}</h4>
                    <div class="date-views-container">
                        <x-ri-calendar-event-line class="calender-icon" />
                        <span class="date">{{ $announcement['date']->format('M j, Y') }}</span>
                        <span class="dot"></span>
                        <span class="views">{{ number_format($announcement['views']) }} views</span>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>


    <!-- Revenue Ready Kit -->
    <div class="section-container">
        <div class="heading-container">
            <div>
                <h2>Revenue Ready Kit</h2>

            </div>
            <a href="">View All</a>
        </div>

        <div class="revenue-ready-kit">
            @foreach($RevenueReadyKitData as $item)
            <div class="revenue-card">

                <div class="icon-wrapper main-icon"
                    style="background-color: {{ $item['bg'] }}; color: {{ $item['color'] }};">
                    <x-dynamic-component :component="  $item['icon']" class="stat-icon"
                        style="color: {{  $item['color'] }}; " />
                </div>

                <h3 class="title">{{ $item['title'] }}</h3>
                <p class="desc">{{ $item['desc'] }}</p>

                <div class="card-action-row">

                    <a href="{{ $item['link'] }}" class="get-more-btn">
                        <span class="btn-text">Get More</span>
                    </a>


                    <div class="icon-wrapper btn-icon"
                        style="background-color: {{ $item['bg'] }}; color: {{ $item['color'] }};">
                        <x-dynamic-component :component="$item['link-icon']" class="stat-icon"
                            style="color: {{ $item['color'] }};" />
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>


    <!-- Happiness Program  Section -->
    <div class="section-container">

        <div class="happiness-program-heading-container">
            <div class="heading-container-text">
                <h2>Happiness Program</h2>
                <p>Enhance your lifestyle with exclusive member benefits and personalized services</p>
            </div>


            <div class="heading-container-more">
                <p><span> Services Available</span></p>
                <span>
                    <x-ri-arrow-up-s-line class="card-icon" />
                </span>
            </div>

        </div>

        <!-- 1. Dynamic Cards Loop -->
        <div class="happiness-program-card-container">
            @php
                // Define the collections with optional icons and colors
                $collections = [
                    'woodpecker_collection' => [
                        'title' => 'Woodpecker Collection',
                        'icon' => 'ri-gift-line',
                        'icon_color' => '#FF5733'
                    ],
                    'travel_and_events' => [
                        'title' => 'Travel & Events',
                        'icon' => 'ri-flight-takeoff-line',
                        'icon_color' => '#337AFF'
                    ],
                    'tgg_foundation' => [
                        'title' => 'TGG Foundation',
                        'icon' => 'ri-hand-heart-line',
                        'icon_color' => '#33FF77'
                    ]
                ];
            @endphp

            @foreach($collections as $key => $collection)
                @if(!empty($showcase->$key) && is_array($showcase->$key))
                    @foreach($showcase->$key as $item)
                        <div class="happiness-card">
                            <div class="card-image-wrapper">
                                <img src="{{ $item['img'] }}" alt="{{ $collection['title'] }}">
                                
                                {{-- Floating icon for the collection --}}
                                <div class="floating-icon">
                                    <x-dynamic-component 
                                        :component="$collection['icon'] ?? 'ri-gift-line'" 
                                        class="card-icon" 
                                        style="color: {{ $collection['icon_color'] ?? '#000' }};" 
                                    />
                                </div>
                            </div>

                            <div class="card-content">
                                {{-- Use note if exists, else fallback title --}}
                                @if(!empty($item['note']))
                                    <h3 class="card-heading">{{ \Illuminate\Support\Str::limit(strip_tags($item['note']), 25) }}</h3>
                                @else
                                    <h3 class="card-heading">{{ $collection['title'] }}</h3>
                                @endif
                                <p class="card-desc">{{ $collection['title'] }}</p>
                                @if(!empty($item['link']))
                                    <a href="{{ $item['link'] }}" class="card-link">Explore &rarr;</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>

        <!-- 2. Static "Coming Soon" Card -->
        <div class="future-service-widget">
            <div class="widget-content-stack">
                <div class="widget-icon-box">
                    <!-- Assuming you are using Blade/Alpine or similar for the Heroicon -->
                    <x-heroicon-o-plus class="icon-svg" />
                </div>
                <h3 class="widget-title">More Services Coming Soon</h3>
                <p class="widget-text">We're constantly adding new benefits to enhance your experience.</p>
            </div>
        </div>

    </div>

    <!-- Freelancing Opportunities and Upcoming Projects Section -->
    <div class="bottom-section">
        <!-- Freelancing Opportunities -->
        <div class="section-container">
            <div class="heading-container">
                <div>
                    <h2>Freelancing Opportunities</h2>
                    <p>Earn extra by leveraging your skills</p>
                </div>
            </div>

            <div class="freelance-opportunity-list">
                @if (!empty($showcase->investment_opportunities))
                    @foreach($showcase->investment_opportunities as $item)
                    <div class="freelance-list-item">
                        <div class="badge-icon" style="background-color: #DBEAFE; color: #033576;">
                            <x-dynamic-component :component="'ri-hand-heart-line'" class="list-icon-svg"
                                style="color: #033576;" />
                        </div>
                        <div class="list-content">
                            <h4 class="list-title">{{ $item['title'] }}</h4>
                            <p class="list-desc">{!! htmlspecialchars($opportunity['note'] ?? '', ENT_QUOTES) !!}</p>
                        </div>
                        <x-ri-arrow-right-s-line class="sidebar-icon" />
                    </div>
                    @endforeach
                @else
                    <p>No opportunities available</p>
                @endif
            </div>

            <a class="view-all-opportunities-btn" href="">View All Opportunities</a>
        </div>

    </div>

    <!-- Venture Bench Support -->
    <div class="section-container">
        <div class="heading-container">
            <div>
                <h2>Venture Bench Support</h2>
            </div>
            <a href="{{ route('tgg-india.venture-bench-services.index', ['role' => auth('web2')->user()->role_key]) }}">View All</a>
        </div>

        <div class="venture-bench-support">
            @foreach(getVentureBenchSupportDashbaordData() as $item)
            <div class="venture-bench-card">
                <div class="icon-wrapper main-icon"
                    style="background-color: {{ $item['bg'] }}; color: {{ $item['color'] }};">
                    <x-dynamic-component :component="$item['icon']" class="stat-icon"
                        style="color: {{ $item['color'] }};" />
                </div>
                <div class="venture-bench-card-text-info">
                    <h3 class="title">{{ $item['title'] }}</h3>
                    @php
                       $item['desc'] = implode(', ', $item['points']);
                    @endphp
                    <p class="desc">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    
    <!--  Latest Blogs & News Section -->
    <div class="latest_Blogs_News_Section_container" >
        <div class="section-container latest_Blogs_News_Section">
            <div class="heading-container">
                <div>
                    <h2>Latest Blogs & News</h2>

                </div>
                <a href="">View All</a>
            </div>

            <div class="news-list-container">
                @foreach($newsArticles as $article)
                <a href="{{ $article['link'] }}" class="news-list-item">

                    <!-- Thumbnail Image -->
                    <div class="news-image-wrapper">
                        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="news-thumbnail">
                    </div>

                    <!-- Text Content (Title Only) -->
                    <div class="news-content">
                        <h4 class="news-title">{{ $article['title'] }}</h4>
                    </div>

                    <!-- Right Arrow -->
                    <x-ri-arrow-right-s-line class="news-arrow-icon" />

                </a>
                @endforeach
            </div>

            <!-- tgg news -->
            <div class="heading-container mt-3">
                <div>
                    <h2>TGG News</h2>

                </div>
                <!-- <a href="">View All</a> -->
            </div>

            <div class="news-list-container">
                <div class="card-inner">
                    <div class="slider" style="height: 220px !important; max-width: 100% !important">
                        @if (!empty($showcase->tgg_news))
                            @foreach ($showcase->tgg_news as $news)
                                <div class="slide news-list-item">
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
        <x-need-help-box />
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(!empty($assignmentTaskType))
        const ctx = document.getElementById('taskTypeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($assignmentTaskType)),
                datasets: [{
                    data: @json(array_values($assignmentTaskType)),
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#20c997', '#6f42c1', '#fd7e14', '#6610f2'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return `${ctx.label}: ${ctx.parsed} assignments`;
                            }
                        }
                    }
                }
            }
        });
    @endif
</script>
@endpush