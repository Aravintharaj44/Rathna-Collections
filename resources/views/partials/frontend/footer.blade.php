<footer class="rc-footer pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ asset('images/rc-mark.svg') }}" alt="Rathna Collections" class="rc-footer-logo">
                    <h5 class="mb-0">Rathna Collections</h5>
                </div>
                <p class="small">
                    Quality textile fashion for Men, Women &amp; Kids. Trusted fabrics, modern styles,
                    delivered to your doorstep.
                </p>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Shop</h6>
                <ul class="list-unstyled small">
                    @foreach ($navCategories ?? [] as $cat)
                        <li><a class="link-light text-decoration-none" href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a class="link-light text-decoration-none" href="{{ route('shop.index') }}">All Products</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Information</h6>
                <ul class="list-unstyled small">
                    @forelse ($footerPages ?? [] as $page)
                        <li><a class="link-light text-decoration-none" href="{{ route('page.show', $page->slug) }}">{{ $page->title }}</a></li>
                    @empty
                        <li><span class="text-secondary-emphasis">Coming soon</span></li>
                    @endforelse
                </ul>
            </div>
            <div class="col-lg-4">
                <h6>Newsletter</h6>
                <p class="small">Subscribe for offers and new arrivals.</p>
                <form class="d-flex gap-2" action="{{ route('subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Your email" required>
                    <button class="btn btn-sm btn-primary" type="submit">Subscribe</button>
                </form>
                @error('email')<small class="text-warning">{{ $message }}</small>@enderror
            </div>
        </div>
        <hr class="border-light border-opacity-25">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small">
            <span>&copy; {{ date('Y') }} Rathna Collections. All rights reserved.</span>
            <div class="d-flex gap-3 fs-5 mt-2 mt-md-0">
                @if ($fb = \App\Models\Setting::get('facebook_url'))<a class="link-light" href="{{ $fb }}"><i class="bi bi-facebook"></i></a>@endif
                @if ($ig = \App\Models\Setting::get('instagram_url'))<a class="link-light" href="{{ $ig }}"><i class="bi bi-instagram"></i></a>@endif
                @if ($wa = \App\Models\Setting::get('whatsapp_url'))<a class="link-light" href="{{ $wa }}"><i class="bi bi-whatsapp"></i></a>@endif
            </div>
        </div>
    </div>
</footer>
