{{-- Reusable flash message block, driven by session keys set in controllers --}}
@foreach (['success' => 'success', 'status' => 'info', 'error' => 'danger', 'warning' => 'warning'] as $key => $variant)
    @if (session($key))
        <div class="alert alert-{{ $variant }} alert-dismissible fade show alert-auto-dismiss" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach
