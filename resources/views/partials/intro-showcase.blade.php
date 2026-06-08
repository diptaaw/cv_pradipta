<section class="intro-showcase">
    <!-- Prism Layer -->
    <img src="{{ asset('images/intro/intro_prism.png') }}" class="intro-prism" alt="">

    <!-- Intro Sparkles -->
    @include('partials.section-sparkles', ['positions' => [
        ['top' => '15%', 'left' => '10%', 'size' => '16px', 'blur' => '1px', 'dur' => '4s', 'del' => '0.2s'],
        ['top' => '75%', 'left' => '15%', 'size' => '24px', 'blur' => '0.5px', 'dur' => '6s', 'del' => '1.5s'],
        ['top' => '20%', 'left' => '85%', 'size' => '64px', 'blur' => '0px', 'dur' => '5s', 'del' => '0.8s'],
        ['top' => '80%', 'left' => '80%', 'size' => '16px', 'blur' => '1.5px', 'dur' => '7s', 'del' => '2.1s'],
        ['top' => '10%', 'left' => '60%', 'size' => '40px', 'blur' => '0px', 'dur' => '4.5s', 'del' => '0.5s'],
        ['top' => '72%', 'left' => '83%', 'size' => '16px', 'blur' => '0px', 'dur' => '5.5s', 'del' => '1.2s']
    ]])

    <!-- Title Layer -->
    <img src="{{ asset('images/intro/intro_title.png') }}" class="intro-title" alt="Portfolio Intro">
</section>
