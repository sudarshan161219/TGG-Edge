@extends('tgg-india.layouts.app', [
    'pageCss' => 'resources/css/pages/dashboard.css'
])

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

    $assignments = AssignmentSecondary::where('assigned_to', auth('web2')->id())
                ->latest()
                ->get();

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
            'icon' => 'bi-receipt',
            'count' => array_sum($invoiceStatus),
            'label' => 'Invoices',
            'color' => '#155DFC',
            'bg' => '#EFF6FF',
            'amount' => $invoiceAmount,
            'statuses' => $invoiceStatus,
        ],
        [
            'icon' => 'bi-file-earmark-text',
            'count' => array_sum($receiptStatus),
            'label' => 'Receipts',
            'color' => '#9810FA',
            'bg' => '#FAF5FF',
            'amount' => $receiptAmount,
            'statuses' => $receiptStatus,
        ],
        [
            'icon' => 'bi-people',
            'count' => $mainAssignmentCount,
            'label' => 'Assignments',
            'color' => '#00A63E',
            'bg' => '#F0FDF4',
            'statuses' => $assignmentStatus,
        ],
        [
            'icon' => 'bi-heart',
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
        'image' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=600&q=80', // Solar
        'title' => 'New Solar Initiative Launched for Rural Partners',
        'desc' => 'Empowering rural communities with sustainable energy solutions and new partnership opportunities.',
        'link' => '#'
        ],
        [
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80', // Digital
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
                <h2>Welcome to {{ $user->name ?? 'Facilitator' }}</h2>
                <p><span>Welcome to TGG Meta—</span>
                    {!! $showcase->welcome_note_facilitator ?? 'a space for responsible humans to transform their lives through ethical entrepreneurship and collective action. Anchor your journey in The Power of 5 and The Art of Gifting.' !!}</p>
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
                <p>Track your active initiatives and campaigns</p>
            </div>
            <a href="">View All</a>
        </div>

        <!-- stats -->
        <div class="stats-container">
            @foreach($summaryStats as $stat)
            <div class="stat-box">
                <div class="my-project-stats-icon-container">
                    <div>
                        <i class="bi {{ $stat['icon'] }} stat-icon" style="color: {{ $stat['color'] }}; font-size: 24px;"></i>
                    </div>
                    <h3 class="stat-value">{{ $stat['count'] }}</h3>
                </div>
                <p class="stat-label">{{ $stat['label'] }}</p>
                @if(isset($stat['amount']))
                    <p class="stat-amount" style="font-size: 12px; color: #666; margin-top: 5px;">₹{{ number_format($stat['amount'], 2) }}</p>
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

        <!-- Assignments (Scrollable Y only) -->
        @if ($assignments->count() > 0)
        <div class="assignment-list mt-3 overflow-auto" style="max-height: 300px;">
            <div class="heading-container">
                <div>
                    <h2>Assignments</h2>
                </div>
                <a href="{{ route('tgg-india.freelancer.assignments.index') }}">View All</a>
            </div>

            @foreach($assignments as $assignment)
                <div class="border rounded p-2 mb-2">

                    <div class="d-flex justify-content-between">
                        <strong>{{ $assignment->title }}</strong>

                        <span class="badge 
                            @if($assignment->status == 'pending') bg-warning
                            @elseif($assignment->status == 'in_progress') bg-primary
                            @else bg-success
                            @endif">
                            {{ ucfirst($assignment->status) }}
                        </span>
                    </div>

                    <small class="text-muted">
                        Due: {{ $assignment->due_date ?? 'N/A' }}
                    </small>

                </div>
            @endforeach

        </div>
        @endif
    </div>

    <!--  Latest Blogs & News Section -->
    <div class="section-container">
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
                <x-ri-arrow-right-s-line class="icon" />

            </a>
            @endforeach
        </div>
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