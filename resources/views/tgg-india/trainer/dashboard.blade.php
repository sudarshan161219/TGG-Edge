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
    use App\Models\ModuleInstance;

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

    $moduleCount = ModuleInstance::where('user_id', auth('web2')->user()->id)->count();

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
            'count' => $moduleCount,
            'label' => 'Modules',
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
                <h2>Welcome to {{ $user->name ?? 'Trainer' }}</h2>
                <p><span>Welcome to TGG Meta—</span>
                    {!! $showcase->welcome_note_trainer ?? 'a space for responsible humans to transform their lives through ethical entrepreneurship and collective action. Anchor your journey in The Power of 5 and The Art of Gifting.' !!}</p>
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

    <!-- My Project Section -->
    <div class="section-container">
        <div class="heading-container">
            <div>
                <h2>My Sync</h2>
                <p>Track your active initiatives and campaigns</p>
            </div>
            <a href="">View All Projects</a>
        </div>


        <!-- stats -->
        <div class="stats-container">
            @foreach($summaryStats as $stat)
            <div class="stat-box">
                <div class="my-project-stats-icon-container">
                {{-- Added the 'stat-icon' class here --}}
                    <div class="stats-icon-container" style="background-color: {{ $stat['bg'] }};">
                        <x-dynamic-component :component=" $stat['icon']" class="stat-icon"
                            style="color: {{ $stat['color'] }}; " />
                    </div>
                    <h3 class="stat-value">{{ $stat['count'] }}</h3>
                </div>
                <p class="stat-label">{{ $stat['label'] }}</p>

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


        </div>
        <x-need-help-box />
    </div>


    <!-- Assignment Task Type Breakdown Section -->
    @if(!empty($assignmentTaskType))
    <div class="section-container">
        <div class="heading-container">
            <div>
                <h2>Assignment Distribution by Task Type</h2>
                <p>Overview of your assignments across different task categories</p>
            </div>
        </div>
        
        <div class="assignment-breakdown" style="background: white; border-radius: 16px; padding: 24px;">
            <div class="row">
                <div class="col-md-6">
                    @foreach($assignmentTaskType as $taskType => $total)
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span class="text-capitalize">{{ $taskType }}</span>
                            <span class="fw-semibold">{{ $total }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                style="width: {{ ($total / max(1, array_sum($assignmentTaskType))) * 100 }}%">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <canvas id="taskTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
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