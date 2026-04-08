<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NeedHelpBox extends Component
{
    // Declare public properties for dynamic data
    public $title;
    public $description;
    public $link;
    public $buttonText;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $title = 'Need Help?',
        $description = 'Contact your dedicated relationship manager for instant support',
        $link = 'https://wa.me/919995329536/?text=Hello%20Admin,%20I%20need%20some%20help.',
        $buttonText = 'Start Chat'
    )
    {
        // Assign the values to public properties
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.need-help-box');
    }
}