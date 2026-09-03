<div class="card card-campaign shadow-sm">
    <div class="position-relative">
        @if($campaign->featured_image)
            <img src="{{ asset('storage/' . $campaign->featured_image) }}" class="card-campaign-img" alt="{{ $campaign->title }}">
        @else
            <div class="card-campaign-img d-flex align-items-center justify-content-center bg-emerald-subtle text-success">
                <i class="bi bi-hospital fs-1"></i>
            </div>
        @endif

        <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1">
            @if($campaign->verification_badge)
                <span class="badge-verification shadow-sm">
                    <i class="bi bi-patch-check-fill text-warning"></i> Verificada
                </span>
            @endif
        </div>

        <div class="position-absolute top-0 end-0 m-3">
            <span class="badge bg-dark bg-opacity-75 text-white shadow-sm">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $campaign->location_province }}
            </span>
        </div>
    </div>

    <div class="card-body d-flex flex-column p-4">
        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
            <span class="badge bg-light text-dark border"><i class="bi bi-tag me-1"></i> {{ ucfirst($campaign->category) }}</span>
            <span><i class="bi bi-clock me-1"></i> {{ $campaign->created_at->diffForHumans() }}</span>
        </div>

        <h5 class="card-title font-heading mb-2">
            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-decoration-none text-dark hover-primary">
                {{ Str::limit($campaign->title, 60) }}
            </a>
        </h5>

        <p class="card-text text-muted small mb-3 flex-grow-1">
            {{ Str::limit($campaign->short_description, 110) }}
        </p>

        <!-- Progress -->
        <div class="progress-fundme">
            <div class="progress-bar-fundme" role="progressbar" style="width: {{ $campaign->progress_percentage }}%;" aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <div>
                <span class="fw-bold text-success fs-5">{{ number_format($campaign->raised_amount, 0, ',', '.') }} Kz</span>
                <span class="text-muted small"> angariados</span>
            </div>
            <div class="text-end">
                <span class="fw-bold text-dark">{{ $campaign->progress_percentage }}%</span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center text-muted small border-top pt-3">
            <span><i class="bi bi-bullseye me-1"></i> Meta: {{ number_format($campaign->target_amount, 0, ',', '.') }} Kz</span>
            @if($campaign->status === 'published')
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-primary-fundme btn-sm px-3 rounded-pill">Apoiar <i class="bi bi-arrow-right ms-1"></i></a>
            @else
                <span class="badge {{ $campaign->status_badge_class }}">{{ $campaign->status_label }}</span>
            @endif
        </div>
    </div>
</div>
