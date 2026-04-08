<?php

use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\UserSecondary;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;


if (!function_exists('featureList')) {
    function featureList()
    {
        return [
            [
                'id'    => 1,
                'key'   => 'literatures',
                'name'  => 'Literatures',
            ],
            [
                'id'    => 2,
                'key'   => 'links',
                'name'  => 'Links',
            ],
            [
                'id'    => 3,
                'key'   => 'videos',
                'name'  => 'Videos',
            ],
            [
                'id'    => 4,
                'key'   => 'linkedins',
                'name'  => 'LinkedIns',
            ],
        ];
    }
}


function getEmbedUrl($url) {
    // For youtu.be links
    if (preg_match('/youtu\.be\/([^\?]+)/', $url, $matches)) {
        return "https://www.youtube.com/embed/" . $matches[1];
    }

    // For youtube.com/watch?v= links
    if (preg_match('/v=([^\&]+)/', $url, $matches)) {
        return "https://www.youtube.com/embed/" . $matches[1];
    }

    return $url; // fallback
}

if (! function_exists('statusWithColor')) {
    function statusWithColor(?string $status): string
    {
        if (!$status) {
            return '<span class="badge page-button bg-secondary">N/A</span>';
        }

        $status = strtolower($status);

        return match ($status) {
            'pending'     => '<span class="badge page-button bg-warning text-dark">' . ucfirst($status) . '</span>',
            'in_progress' => '<span class="badge page-button bg-primary">' . ucfirst(str_replace('_', ' ', $status)) . '</span>',
            'accepted'    => '<span class="badge page-button bg-info text-dark">' . ucfirst($status) . '</span>',
            'paid'    => '<span class="badge page-button bg-info text-dark">' . ucfirst($status) . '</span>',
            'rejected'    => '<span class="badge page-button bg-danger">' . ucfirst($status) . '</span>',
            'unpaid'    => '<span class="badge page-button bg-danger">' . ucfirst($status) . '</span>',
            'completed'   => '<span class="badge page-button bg-success">' . ucfirst($status) . '</span>',
            default       => '<span class="badge page-button bg-secondary">' . ucfirst($status) . '</span>',
        };
    }
}

if (! function_exists('statusWithColorApproval')) {
    function statusWithColorApproval(string|int|null $status): string
    {
        if ($status === null || $status === '') {
            return '<span class="badge page-button bg-secondary">N/A</span>';
        }

        $status = strtolower((string) $status);

        return match ($status) {
            'pending', '0'  => '<span class="badge page-button bg-warning text-dark">Pending</span>',
            'accepted', '1' => '<span class="badge page-button bg-success">Accepted</span>',
            'rejected', '2' => '<span class="badge page-button bg-danger">Rejected</span>',
            default          => '<span class="badge page-button bg-secondary">' . ucfirst($status) . '</span>',
        };
    }
}

if (! function_exists('statusWithColorStep')) {
    function statusWithColorStep(int|string|null $step): string
    {
        if ($step === null || $step === '') {
            return '<span class="badge bg-secondary">N/A</span>';
        }

        $step = (int) $step;

        return match ($step) {
            0       => '<span class="badge bg-warning text-dark">Pending</span>',
            1       => '<span class="badge bg-success">Completed</span>',
            default => '<span class="badge bg-secondary">Other</span>',
        };
    }
}


function generateUniqueReferralCode($length = 8)
{
    do {
        $code = strtoupper(\Str::random($length));
    } while (UserSecondary::where('referral_code', $code)->exists());

    return $code;
}

if (!function_exists('taskTypes')) {
    function taskTypes()
    {
        return [
            'Verification'   => 'Verification',
            'Interview'      => 'Interview',
            'Interview'  => 'Documentation',
            'Lead Generation' => 'Lead Generation',
            'On Boarding'     => 'On Boarding',
            'Training'       => 'Training', // you can add more here
            'Freelance Assignment' => 'Freelance Assignment'

        ];
    }
}




if (!function_exists('validatePattern')) {

    function validatePattern($type, $value)
    {
        $patterns = [
            'ifsc' => [
                'regex' => '/^[A-Z]{4}0[A-Z0-9]{6}$/',
                'example' => 'Ifsc Code Example: SBIN0001234'
            ],

            // ===== BANK DETAILS =====
            'account_number' => [
                // Common Indian Bank Account Format (9 to 18 digits)
                'regex' => '/^[0-9]{9,18}$/',
                'example' => 'Account number should be 9 to 18 digits'
            ],
            'ifsc_code' => [ // alias, same as ifsc
                'regex' => '/^[A-Z]{4}0[A-Z0-9]{6}$/',
                'example' => 'Ifsc Code Example Example: HDFC0001234'
            ],

            // ===== PERSONAL ID =====
            'pan' => [
                'regex' => '/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'example' => 'Pan Number should be - Example: ABCDE1234F'
            ],
            'aadhaar' => [
                'regex' => '/^[2-9]{1}[0-9]{11}$/',
                'example' => '12 Digit Aadhaar – should not start with 0 or 1'
            ],
            'voter_id' => [
                'regex' => '/^[A-Z]{3}[0-9]{7}$/',
                'example' => 'Voter id should be Example: XYZ1234567'
            ],

            'driving_license' => [
                // Example: MH1420012345678 or UP1420012345678
                'regex' => '/^[A-Z]{2}[0-9]{2}[0-9]{11,14}$/',
                'example' => 'Driving License should be Example: MH1420012345678'
            ],

            // ===== CUSTOM =====
            'rhm_number' => [
                // Format: RHM/KL/QLN/1 (supports any 2–4 letter state and district)
                'regex' => '/^RHM\/[A-Z]{2,3}\/[A-Z]{2,4}\/[0-9]{1,4}$/',
                'example' => 'Rhm Number Correct Format is: RHM/KL/QLN/1'
            ],
        ];

        $type = strtolower($type);

        if (!isset($patterns[$type])) {
            return [false, 'Invalid pattern type requested'];
        }

        // Validate
        if (preg_match($patterns[$type]['regex'], trim($value))) {
            return [true, 'Valid'];
        }

        // Failed — return example format
        return [false, $patterns[$type]['example']];
    }
}


function generateInvoiceNumber($sourceId)
{
    $sourceUser = UserSecondary::find($sourceId);
    $sourceUserLastInvoice = Invoice::where('source_id', $sourceId)->count();
    return 'INV-'. str_replace('/', '-', $sourceUser->rhm_number).'-' . ($sourceUserLastInvoice + 1);
}

function generateReceiptNumber($sourceId)
{
    $sourceUser = UserSecondary::find($sourceId);
    $sourceUserLastInvoice = Receipt::where('source_id', $sourceId)->count();
    return 'RPT-'. str_replace('/', '-', $sourceUser->rhm_number).'-' . ($sourceUserLastInvoice + 1);
}

function getTypeOfEngagementOptions()
{
    return [
        1 => 'Technical Service',
        2 => 'Professional Service',
    ];
}

function getReasonOfReward()
{
    return [
        'RHM Center' => 'RHM Center',
        'TGG AID' => 'TGG AID',
        'TGG GRANT' => 'TGG GRANT',
    ];
}

function getDonationType()
{
    return [
        1 => '80G',
        2 => 'FCRA',
    ];
}

function  getChatMessageType()
{
    return [
        0 => 'general',
        1 => 'onboarding',
        2 => 'technology',
    ];
}


if (!function_exists('setting')) {

    /**
     * Get setting value by dot notation
     * Example: setting('general.site_name')
     */
    function setting(string $key, $default = null)
    {
        [$group, $settingKey] = array_pad(explode('.', $key, 2), 2, null);

        if (!$group || !$settingKey) {
            return $default;
        }

        return Cache::rememberForever("setting.{$group}.{$settingKey}", function () use ($group, $settingKey, $default) {
            return Setting::getValue($group, $settingKey, $default);
        });
    }
}


if (!function_exists('entitlement_plan')) {

    function entitlement_plan(int $value, $default = 'basic')
    {
        $rules = setting('general.entitlement_rules', []);

        foreach ($rules as $rule) {
            if (
                $value >= $rule['min'] &&
                (is_null($rule['max']) || $value <= $rule['max'])
            ) {
                return $rule['plan'];
            }
        }

        return $default;
    }
}


if (!function_exists('appraisal_plan')) {

    function appraisal_plan(int $value, $default = 'basic')
    {
        $rules = setting('general.appraisal_rules', []);

        foreach ($rules as $rule) {
            if (
                $value >= $rule['min'] &&
                (is_null($rule['max']) || $value <= $rule['max'])
            ) {
                return $rule['plan'];
            }
        }

        return $default;
    }
}



// function getVentureBenchSupportDashbaordData(){
  
//         return   $services = [
//                 [
//                     'title' => 'Website Development',
//                     'icon' => 'bi-code-slash',
//                     'logo' => 'images/services/web-development.svg',
//                     'points' => ['Static & dynamic websites', 'Responsive & SEO friendly', 'Secure and scalable build'],
//                     'bg' => '#EFF6FF',
//                     'color' => '#155DFC',
//                 ],
//                 [
//                     'title' => 'Digital Marketing',
//                     'icon' => 'bi-megaphone',
//                     'logo' => 'images/services/digital-marketing.svg',
//                     'points' => ['SEO & social media campaigns', 'Content & brand promotion', 'Analytics & reporting'],
//                     'bg' => '#FDF2F8',
//                     'color' => '#E60076',
//                 ],
//                 [
//                     'title' => 'TGG News',
//                     'icon' => 'bi-broadcast',
//                     'logo' => 'images/services/news.svg',
//                     'points' => ['AI podcast & video promotion', 'Monthly media exposure', 'Community outreach'],
//                     'bg' => '#DBEAFE',
//                     'color' => '#033576',
//                 ],
//                 [
//                     'title' => 'Business Development Support',
//                     'icon' => 'bi-briefcase',
//                     'logo' => 'images/services/business.svg',
//                     'points' => ['Market strategy planning', 'Growth & partnerships', 'Operational consulting'],
//                     'bg' => '#F3E5F5',
//                     'color' => '#8E24AA',
//                 ],
//                 [
//                     'title' => 'Incorporation Support',
//                     'icon' => 'bi-building',
//                     'logo' => 'images/services/incorporation.svg',
//                     'points' => ['Company registration', 'Compliance assistance', 'Location-based guidance'],
//                     'bg' => '#E0F7FA',
//                     'color' => '#00ACC1',
//                 ],
//                 [
//                     'title' => 'Accounting & Auditing Support',
//                     'icon' => 'bi-calculator',
//                     'logo' => 'images/services/accounting.svg',
//                     'points' => ['Bookkeeping & audits', 'Financial compliance', 'Reporting & review'],
//                     'bg' => '#FFFDE7',
//                     'color' => '#FBC02D',
//                 ],
//                 [
//                     'title' => 'Legal Support',
//                     'icon' => 'bi-shield-check',
//                     'logo' => 'images/services/legal.svg',
//                     'points' => ['Legal documentation', 'Contracts & policies', 'Regulatory guidance'],
//                     'bg' => '#FFEBEE',
//                     'color' => '#E53935',
//                 ],
//                 [
//                     'title' => 'AI Powered Problem Solving',
//                     'icon' => 'bi-cpu',
//                     'logo' => 'images/services/ai.svg',
//                     'points' => ['Instant AI-based solutions', 'Decision support', 'Knowledge assistance'],
//                     'bg' => '#EDE7F6',
//                     'color' => '#5E35B1',
//                 ],
//                 [
//                     'title' => 'App Development',
//                     'icon' => 'bi-phone',
//                     'logo' => 'images/services/app.svg',
//                     'points' => ['Android & iOS apps', 'User-friendly UI/UX', 'Performance optimized'],
//                     'bg' => '#E1F5FE',
//                     'color' => '#039BE5',
//                 ],
//                 [
//                     'title' => 'Custom Software',
//                     'icon' => 'bi-gear',
//                     'logo' => 'images/services/software.svg',
//                     'points' => ['Tailor-made solutions', 'Process automation', 'Secure & scalable systems'],
//                     'bg' => '#FBE9E7',
//                     'color' => '#F4511E',
//                 ],
//                 // ✅ New Service 1
//                 [
//                     'title' => 'Travel Services',
//                     'icon' => 'bi-airplane',
//                     'logo' => 'images/services/travel.svg',
//                     'points' => [
//                         'Domestic & international bookings',
//                         'Corporate travel management',
//                         'Visa & travel assistance'
//                     ],
//                     'bg' => '#E0F2F1',
//                     'color' => '#00897B',
//                 ],

//                 // ✅ New Service 2
//                 [
//                     'title' => 'Loyalty Program',
//                     'icon' => 'bi-gift',
//                     'logo' => 'images/services/loyalty.svg',
//                     'points' => [
//                         'Reward & referral programs',
//                         'Customer engagement strategies',
//                         'Point-based incentive systems'
//                     ],
//                     'bg' => '#FCE4EC',
//                     'color' => '#D81B60',
//                 ],
//             ];
// }

function getVentureBenchSupportDashbaordData(){
  
    return $services = [
        [
            'title' => 'Website Development',
            'icon' => 'ri-code-line', // Replaced bi-code-slash
            'logo' => 'images/services/web-development.svg',
            'points' => ['Static & dynamic websites', 'Responsive & SEO friendly', 'Secure and scalable build'],
            'bg' => '#EFF6FF',
            'color' => '#155DFC',
        ],
        [
            'title' => 'Digital Marketing',
            'icon' => 'ri-megaphone-line', // Replaced bi-megaphone
            'logo' => 'images/services/digital-marketing.svg',
            'points' => ['SEO & social media campaigns', 'Content & brand promotion', 'Analytics & reporting'],
            'bg' => '#FDF2F8',
            'color' => '#E60076',
        ],
        [
            'title' => 'TGG News',
            'icon' => 'ri-broadcast-line', // Replaced bi-broadcast
            'logo' => 'images/services/news.svg',
            'points' => ['AI podcast & video promotion', 'Monthly media exposure', 'Community outreach'],
            'bg' => '#DBEAFE',
            'color' => '#033576',
        ],
        [
            'title' => 'Business Development Support',
            'icon' => 'ri-briefcase-line', // Replaced bi-briefcase
            'logo' => 'images/services/business.svg',
            'points' => ['Market strategy planning', 'Growth & partnerships', 'Operational consulting'],
            'bg' => '#F3E5F5',
            'color' => '#8E24AA',
        ],
        [
            'title' => 'Incorporation Support',
            'icon' => 'ri-building-line', // Replaced bi-building
            'logo' => 'images/services/incorporation.svg',
            'points' => ['Company registration', 'Compliance assistance', 'Location-based guidance'],
            'bg' => '#E0F7FA',
            'color' => '#00ACC1',
        ],
        [
            'title' => 'Accounting & Auditing Support',
            'icon' => 'ri-calculator-line', // Replaced bi-calculator
            'logo' => 'images/services/accounting.svg',
            'points' => ['Bookkeeping & audits', 'Financial compliance', 'Reporting & review'],
            'bg' => '#FFFDE7',
            'color' => '#FBC02D',
        ],
        [
            'title' => 'Legal Support',
            'icon' => 'ri-shield-check-line', // Replaced bi-shield-check
            'logo' => 'images/services/legal.svg',
            'points' => ['Legal documentation', 'Contracts & policies', 'Regulatory guidance'],
            'bg' => '#FFEBEE',
            'color' => '#E53935',
        ],
        [
            'title' => 'AI Powered Problem Solving',
            'icon' => 'ri-cpu-line', // Replaced bi-cpu
            'logo' => 'images/services/ai.svg',
            'points' => ['Instant AI-based solutions', 'Decision support', 'Knowledge assistance'],
            'bg' => '#EDE7F6',
            'color' => '#5E35B1',
        ],
        [
            'title' => 'App Development',
            'icon' => 'ri-smartphone-line', // Replaced bi-phone
            'logo' => 'images/services/app.svg',
            'points' => ['Android & iOS apps', 'User-friendly UI/UX', 'Performance optimized'],
            'bg' => '#E1F5FE',
            'color' => '#039BE5',
        ],
        [
            'title' => 'Custom Software',
            'icon' => 'ri-settings-line', // Replaced bi-gear
            'logo' => 'images/services/software.svg',
            'points' => ['Tailor-made solutions', 'Process automation', 'Secure & scalable systems'],
            'bg' => '#FBE9E7',
            'color' => '#F4511E',
        ],
        // ✅ New Service 1
        [
            'title' => 'Travel Services',
            'icon' => 'ri-flight-takeoff-line', // Replaced bi-airplane
            'logo' => 'images/services/travel.svg',
            'points' => [
                'Domestic & international bookings',
                'Corporate travel management',
                'Visa & travel assistance'
            ],
            'bg' => '#E0F2F1',
            'color' => '#00897B',
        ],

        // ✅ New Service 2
        [
            'title' => 'Loyalty Program',
            'icon' => 'ri-gift-line', // Replaced bi-gift
            'logo' => 'images/services/loyalty.svg',
            'points' => [
                'Reward & referral programs',
                'Customer engagement strategies',
                'Point-based incentive systems'
            ],
            'bg' => '#FCE4EC',
            'color' => '#D81B60',
        ],
    ];
}