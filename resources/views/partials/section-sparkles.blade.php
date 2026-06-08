<div class="section-sparkles">
    @php
        $sparklePositions = $positions ?? [
            ['top' => '15%', 'left' => '85%', 'dur' => '5.5s', 'del' => '0s'],
            ['top' => '75%', 'left' => '10%', 'dur' => '6.5s', 'del' => '1.5s'],
            ['top' => '45%', 'left' => '90%', 'dur' => '4.5s', 'del' => '0.8s'],
        ];
    @endphp
    @foreach ($sparklePositions as $index => $s)
        <img src="{{ asset('images/intro/intro_sparkle.png') }}" 
             class="section-sparkle" 
             alt=""
             style="
                 top: {{ $s['top'] }}; 
                 left: {{ $s['left'] }}; 
                 width: {{ $s['size'] ?? '32px' }}; 
                 height: {{ $s['size'] ?? '32px' }}; 
                 --blur-amt: {{ $s['blur'] ?? '0px' }};
                 --blink-dur: {{ $s['dur'] }}; 
                 --blink-del: {{ $s['del'] }};
                 @if(isset($s['opacity_min'])) --opacity-min: {{ $s['opacity_min'] }}; @endif
                 @if(isset($s['opacity_max'])) --opacity-max: {{ $s['opacity_max'] }}; @endif
             ">
    @endforeach
</div>
