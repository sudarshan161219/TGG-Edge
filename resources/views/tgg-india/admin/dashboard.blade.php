@extends('tgg-india.layouts.app', [
    'pageCss' => 'resources/css/pages/dashboard.css'
])

@section('title', 'Admin Dashboard | TGG Meta | TGG India')

@php
    use App\Models\Incentive;
    use App\Models\Reward;
    use App\Models\Donation;
    use App\Models\Payment;
    use App\Models\Invoice;
    use App\Models\Receipt;
    use App\Models\Referral;
    use Illuminate\Support\Facades\Schema;

    // ✅ Fetch status-based counts
    $incentiveStatus = Incentive::select('status', \DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    $paymentStatus = Payment::select('status', \DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    $invoiceStatus = Invoice::select('status', \DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    $receiptStatus = Receipt::select('status', \DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    $donationCount = Donation::count();
    $rewardCount = Reward::count();
    $referralCount = Referral::count();

    // ✅ Safe amount calculations
    $incentiveAmount = Incentive::sum('amount');
    $rewardAmount = Reward::sum('amount');

    if (Schema::connection('mysql2')->hasColumn('invoices', 'items')) {
        $invoiceAmount = Invoice::get()
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

    $receiptAmount = Schema::connection('mysql2')->hasColumn('receipts', 'amount') ? Receipt::sum('amount') : 0;
    $donationAmount = Schema::connection('mysql2')->hasColumn('donations', 'amount') ? Donation::sum('amount') : 0;
    $paymentAmount = Schema::connection('mysql2')->hasColumn('payments', 'amount') ? Payment::sum('amount') : 0;
    
    $user = \App\Models\UserSecondary::find(auth('web2')->id());
    
    // ==================== SUMMARY ARRAY FOR STATS ====================
    $summaryStats = [
        [
            'icon' => 'bi-gift',
            'count' => array_sum($incentiveStatus),
            'label' => 'Incentives',
            'color' => '#155DFC',
            'bg' => '#EFF6FF',
            'amount' => $incentiveAmount,
            'statuses' => $incentiveStatus,
        ],
        [
            'icon' => 'bi-trophy',
            'count' => $rewardCount,
            'label' => 'Rewards',
            'color' => '#00A63E',
            'bg' => '#F0FDF4',
            'amount' => $rewardAmount,
        ],
        [
            'icon' => 'bi-heart',
            'count' => $donationCount,
            'label' => 'Donations',
            'color' => '#E60076',
            'bg' => '#FDF2F8',
            'amount' => $donationAmount,
        ],
        [
            'icon' => 'bi-receipt',
            'count' => array_sum($invoiceStatus),
            'label' => 'Invoices',
            'color' => '#F54900',
            'bg' => '#FFEDD4',
            'statuses' => $invoiceStatus,
            'amount' => $invoiceAmount,
        ],
        [
            'icon' => 'bi-file-earmark-text',
            'count' => array_sum($receiptStatus),
            'label' => 'Receipts',
            'color' => '#9810FA',
            'bg' => '#FAF5FF',
            'statuses' => $receiptStatus,
            'amount' => $receiptAmount,
        ],
        [
            'icon' => 'bi-people',
            'count' => $referralCount,
            'label' => 'Referrals',
            'color' => '#033576',
            'bg' => '#DBEAFE',
        ],
    ];

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
            'icon' => 'ri-hand-heart-line',
            'bg' => '#1F3C88',
            'color' => '#FFFFFF',
            'title' => 'Lorem ipsum dolor sit amet',
            'desc' => 'Consectetur adipiscing elit.',
            'link' => '#',
            'link-icon' => 'ri-arrow-right-up-line'
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#1F3C88',
            'color' => '#FFFFFF',
            'title' => 'Duis aute irure dolor in',
            'desc' => 'Reprehenderit in voluptate velit esse',
            'link' => '#',
            'link-icon' => 'ri-arrow-right-up-line'
        ],
        [
            'icon' => 'ri-hand-heart-line',
            'bg' => '#1F3C88',
            'color' => '#FFFFFF',
            'title' => 'Sunt in culpa qui officia',
            'desc' => 'Deserunt mollit anim id est laborum.',
            'link' => '#',
            'link-icon' => 'ri-arrow-right-up-line'
        ]
    ];

    $servicesData = [
        [
            'icon' => 'ri-code-line',
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
            'icon' => 'ri-linkedin-box-line',
            'bg' => '#DBEAFE',
            'color' => '#033576',
            'title' => 'LinkedIn Promotion',
            'desc' => 'Use AI tools to boost reach',
            'link' => '#',
        ],
        [
            'icon' => 'ri-fire-line',
            'bg' => '#FFEDD4',
            'color' => '#F54900',
            'title' => 'IRDA License Holder',
            'desc' => 'Insurance POSP opportunities',
            'link' => '#',
        ],
        [
            'icon' => 'ri-group-line',
            'bg' => '#F3E8FF',
            'color' => '#9810FA',
            'title' => 'Trainer - Biz Dev',
            'desc' => 'Train new associates',
            'link' => '#',
        ],
        [
            'icon' => 'ri-edit-line',
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
                <h2>Welcome to {{ $user->name ?? 'Admin' }}</h2>
                <p><span>Welcome to TGG Meta—</span>
                    It is a dynamic hub where ethical research meets grassroots action. This is where your inquiries, insights, and efforts converge to shape meaningful change through collaborative, well-coordinated projects.
                    As a volunteer or researcher, you are part of a unified ecosystem committed to experiential learning, rigorous documentation, and outcome-oriented exploration. Here, you’ll find streamlined tools to manage assignments, exchange knowledge, and align your work with the broader values of sustainability, compassion, and community empowerment.
                    Let’s co-create solutions that bridge theory and practice, deepen local impact, and contribute to a global narrative of self-reliance and human unity. Welcome aboard and onward with purpose.
                </p>
            </div>
            <div class="active-projects">
                <h2>Platform Overview</h2>
                <ul class="active-projects-list">
                    <li>
                        <h4>Total Engagements</h4>
                        <div><span class="badge"></span> <span class="badge-status-text">{{ array_sum($incentiveStatus) + $rewardCount }} Active</span></div>
                    </li>
                    <li>
                        <h4>Community Interactions</h4>
                        <div><span class="badge"></span> <span class="badge-status-text">{{ $donationCount + $referralCount }}</span></div>
                    </li>
                    <li>
                        <h4>Total Transactions</h4>
                        <div><span class="badge"></span> <span class="badge-status-text">{{ array_sum($paymentStatus) + array_sum($invoiceStatus) }}</span></div>
                    </li>
                    <li>
                        <h4>Acknowledgements</h4>
                        <div><span class="badge"></span> <span class="badge-status-text">{{ array_sum($receiptStatus) }}</span></div>
                    </li>
                </ul>
            </div>
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

    <!-- My Project Section -->
    <div class="section-container">
        <div class="heading-container">
            <div>
                <h2>Platform Statistics</h2>
                <p>Track key metrics across the TGG ecosystem</p>
            </div>
            <a href="">View All Reports</a>
        </div>

        <!-- stats -->
        <div class="stats-container">
            @foreach($summaryStats as $stat)
            <div class="stat-box">
                <div class="my-project-stats-icon-container">
                    <div >
                        <i class="bi {{ $stat['icon'] }} stat-icon" style="color: {{ $stat['color'] }}; font-size: 24px;"></i>
                    </div>
                    <h3 class="stat-value">{{ $stat['count'] }}</h3>
                </div>
                <p class="stat-label">{{ $stat['label'] }}</p>
                @if(isset($stat['amount']))
                    <p class="stat-amount" style="font-size: 12px; color: #666; margin-top: 5px;">
                        @if($stat['label'] == 'Rewards')
                            Total Points: {{ number_format($stat['amount'], 0) }}
                        @else
                            Total Amount: ₹{{ number_format($stat['amount'], 2) }}
                        @endif
                    </p>
                @endif
                @if(!empty($stat['statuses']))
                    <div class="status-breakdown mt-2" style="width: 100%;">
                        @foreach($stat['statuses'] as $status => $total)
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span class="text-capitalize">{{ $status }}</span>
                                <span class="fw-semibold">{{ $total }}</span>
                            </div>
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ ($total / max(1, array_sum($stat['statuses']))) * 100 }}%; background-color: {{ $stat['color'] }};">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endforeach
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
</main>
@endsection