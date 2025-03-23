<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{ $title }}</h3>
            <p class="text-subtitle text-muted text-capitalize">{{ $subtitle ?? '' }}</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first text-end">
            @if (isset($url))
                <a href="{{ $url }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left-circle"></i> Back
                </a>
            @endif
        </div>
    </div>
</div>
