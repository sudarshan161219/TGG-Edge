<div class="need_help_box">
    <div class="need_help_box_icon">
        <x-ri-customer-service-line class="need_help_icon" />
    </div>

    <h5>{{ $title ?? 'Need Help?' }}</h5>
    <p>{{ $description ?? 'Contact your dedicated relationship manager for instant support' }}</p>

    <a href="{{ $link ?? 'https://wa.me/919995329536/?text=Hello%20Admin,%20I%20need%20some%20help.' }}" 
       target="_blank" 
       class="start_chat_btn">
        <x-ri-whatsapp-line class="start_chat_icon" /> {{ $buttonText ?? 'Start Chat' }}
    </a>
</div>