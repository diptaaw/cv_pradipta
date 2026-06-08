@php
    $whatsappPhone = '628991899977';
    $prefilledMessage = rawurlencode("Hi! I found your portfolio website and I'm interested in your portfolio template.");
    $whatsappUrl = "https://wa.me/{$whatsappPhone}?text={$prefilledMessage}";
@endphp

<div class="pixel-companion" id="cat-companion" data-url="{{ $whatsappUrl }}">
    <img src="{{ asset('images/ui/icon_bubblechat.png') }}" class="cat-chat-bubble" id="cat-chat-bubble" alt="Message Me!">
    <img src="{{ asset('images/ui/icon_cat.png') }}" class="cat-sprite" id="cat-sprite" alt="Cosmic Pixel Cat Companion">
    <div class="cat-sparkles-container" id="cat-sparkles-container"></div>
</div>
