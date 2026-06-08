<div id="global-intro-sparkles" class="global-sparkles">
    @php
        $sparkles = [
            ['top' => '20%', 'left' => '15%', 'dur' => '4s', 'del' => '0.2s'],
            ['top' => '70%', 'left' => '80%', 'dur' => '6s', 'del' => '1.5s'],
            ['top' => '30%', 'left' => '75%', 'dur' => '5s', 'del' => '0.8s'],
            ['top' => '80%', 'left' => '25%', 'dur' => '7s', 'del' => '2.1s'],
            ['top' => '50%', 'left' => '10%', 'dur' => '4.5s', 'del' => '0.5s'],
            ['top' => '15%', 'left' => '85%', 'dur' => '5.5s', 'del' => '1.2s'],
            ['top' => '60%', 'left' => '45%', 'dur' => '8s', 'del' => '3.0s'],
            ['top' => '85%', 'left' => '60%', 'dur' => '6.5s', 'del' => '0.9s'],
        ];
    @endphp
    @foreach ($sparkles as $index => $s)
        <img src="{{ asset('images/intro/intro_sparkle.png') }}" 
             class="global-sparkle" 
             alt=""
             style="top: {{ $s['top'] }}; left: {{ $s['left'] }}; --blink-dur: {{ $s['dur'] }}; --blink-del: {{ $s['del'] }};">
    @endforeach
</div>
