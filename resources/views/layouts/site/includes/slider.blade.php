<section class="slider-area">
    <div class="slider owl-carousel">
        @if(!empty($data['sliders']))
            @foreach($data['sliders'] as $slide)
            <div class="slider-item" style="background-image: url({{ asset('/uploads/slider/' . $slide->image) }});">
                <div class="container">
                    <div class="slider-content">
                        <h2 class="title d-none">{{$slide->title ?? ''}}</h2>
                        <p class="desc d-none">{{$slide->description ?? ''}}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>
