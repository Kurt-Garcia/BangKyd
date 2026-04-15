<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit Order - {{ $salesOrder->so_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: url('{{ asset('img/BG.jpg') }}') center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }
        
        .main-container {
            min-height: 100vh;
            padding: 2rem 0 8rem 0;
            position: relative;
            z-index: 1;
        }
        
        .header-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            margin-bottom: 2rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .upload-section {
            background: rgba(0, 0, 0, 0.7);
            padding: 2.5rem 2rem;
            color: white;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .text-primary {
            color: #333 !important;
        }
        
        .btn-primary {
            background-color: #333;
            border-color: #333;
        }
        
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #555;
            border-color: #555;
        }
        
        .design-preview-shell {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 1rem;
            margin-top: 1rem;
            backdrop-filter: blur(10px);
        }

        .design-preview-img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            background: rgba(0, 0, 0, 0.15);
        }

        .design-empty-state {
            border: 2px dashed rgba(255, 255, 255, 0.35);
            border-radius: 14px;
            padding: 2rem 1rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.85);
        }

        .design-empty-state i {
            font-size: 2.25rem;
            display: block;
            margin-bottom: 0.75rem;
        }

        .design-carousel .carousel-item {
            text-align: center;
        }

        .design-carousel .carousel-item img {
            height: 300px;
            width: 100%;
            object-fit: contain;
        }
        
        .products-section {
            margin-top: 2rem;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
            margin-bottom: 6rem;
            padding-bottom: 2rem;
        }
        
        .product-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            backdrop-filter: blur(10px);
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border-color: #333;
        }
        
        .product-card.selected {
            border-color: #333;
            background: rgba(255, 255, 255, 0.98);
        }
        
        .product-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #333;
            padding: 1.5rem 6.25rem 1.5rem 3.25rem;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .product-card.selected .product-header {
            background: rgba(0, 0, 0, 0.05);
            color: #333;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .product-body {
            padding: 1.5rem;
            color: #444;
        }
        
        .product-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #333;
            color: white;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 2;
        }
        
        .player-count {
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: absolute;
            top: 1rem;
            left: 1rem;
            font-size: 0.8rem;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            z-index: 2;
        }
        
        .order-summary {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            padding: 1.5rem;
            border-top: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 -10px 30px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .section-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
            letter-spacing: 1px;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .btn-floating {
            position: fixed;
            bottom: 6rem;
            right: 2rem;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1001;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-floating:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
            background: black;
        }
        
        .btn-floating:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container-fluid">
            <!-- Header Section -->
            <div class="header-section">
                <div class="text-center p-5">
                    <h1 class="display-4 fw-bold mb-2" style="color: #333; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);"><i class="bi bi-clipboard-check"></i> Order Form</h1>
                    <p class="text-muted fs-5 mb-0">{{ $salesOrder->so_name }} <span class="badge bg-secondary ms-2">{{ $salesOrder->so_number }}</span></p>
                </div>
                
                @if(session('error'))
                    <div class="alert alert-danger mx-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if($salesOrder->draft_data)
                    <div class="alert alert-warning mx-4">
                        <i class="bi bi-exclamation-circle"></i> <strong>Resubmission Requested:</strong> You can edit your previous submission below. Please review and make any necessary corrections.
                    </div>
                @endif
                
                <!-- Upload Design Section -->
                <form action="{{ route('order.submit', $salesOrder->unique_link) }}" method="POST" enctype="multipart/form-data" id="orderForm">
                    @csrf
                    <div class="upload-section">
                        <h3><i class="bi bi-images"></i> Upload Your Design</h3>
                        <p class="mb-2">Share your jersey design with us (up to 3 images)</p>

                        <input type="file" class="d-none" id="designImages" name="images[]" accept="image/*" multiple onchange="handleDesignImagesChange()">

                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" class="btn btn-outline-light" onclick="document.getElementById('designImages').click()">
                                <i class="bi bi-cloud-upload"></i> Upload Images
                            </button>
                            <span class="small text-white-50" id="designCountText"></span>
                        </div>

                        <div class="design-preview-shell" id="designPreviewShell">
                            <div id="designPreviewArea"></div>
                        </div>

                        @if($errors->has('images') || $errors->has('images.0') || $errors->has('images.1') || $errors->has('images.2'))
                            <div class="mt-2">
                                @error('images')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                                @for($i = 0; $i < 3; $i++)
                                    @error('images.' . $i)
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                @endfor
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Products Section -->
            <div class="products-section">
                <h2 class="section-title">Choose Your Products</h2>
                <p class="text-center text-white mb-4">Click on a product card to add players</p>
                
                <div id="productsError" class="alert alert-danger d-none mx-4">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Error:</strong> 
                    <span id="errorMessage">Failed to load products.</span>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="retryLoadProducts()">
                        <i class="bi bi-arrow-clockwise"></i> Retry
                    </button>
                </div>
                
                <div class="products-grid" id="productsGrid">
                    <div class="text-center text-white">
                        <div class="loading-spinner"></div>
                        <p class="mt-2">Loading products...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Footer -->
    <div class="order-summary" id="orderSummary" style="display: none;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-1"><i class="bi bi-cart"></i> Order Summary</h6>
                    <small class="text-muted">Total Players: <span id="playerCount">0</span></small>
                </div>
                <div class="col-md-4 text-end">
                    <h5 class="mb-0" id="totalAmount">₱0.00</h5>
                    <small class="text-muted">Total Amount</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Submit Button -->
    <button type="button" class="btn-floating d-none" id="submitBtn" title="Submit Order" onclick="openConfirmationModal()">
        <i class="bi bi-send-fill"></i>
    </button>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
                <div class="modal-header bg-dark text-white" style="border-bottom: none;">
                    <h5 class="modal-title" id="modalProductTitle">
                        <i class="bi bi-person-plus"></i> Add Players
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Add all players who want this product. You can add multiple players at once.
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold">Players List</h6>
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" onclick="addModalPlayer()">
                            <i class="bi bi-plus-circle"></i> Add Player
                        </button>
                    </div>
                    
                    <div id="modalPlayersContainer">
                        <div class="text-center text-muted py-5 bg-light rounded-3 border-dashed" id="modalEmptyState" style="border: 2px dashed #dee2e6;">
                            <i class="bi bi-person-plus text-secondary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No Players Added Yet</h5>
                            <p class="text-muted">Click the "Add Player" button above to get started.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveModalPlayers()">
                        <i class="bi bi-check-circle"></i> Save Players
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
                <div class="modal-header bg-dark text-white" style="border-bottom: none;">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Your Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info">
                        <strong>Please review your order carefully before submitting.</strong>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Sales Order</h6>
                            <p class="fw-bold">{{ $salesOrder->so_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer Name</h6>
                            <p>{{ $salesOrder->so_name }}</p>
                        </div>
                    </div>

                    <div id="confirmationOrderSummary"></div>

                    <hr>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Payment Summary</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">Total Quantity:</p>
                                    <p class="mb-1">Price per piece:</p>
                                    <p class="mb-1 fw-bold fs-5">Total Amount:</p>
                                    <p class="mb-1 text-danger">Down Payment (50%):</p>
                                    <p class="mb-0 text-success">Balance:</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1"><span id="confirmQty">0</span> pcs</p>
                                    <p class="mb-1"><span id="confirmPrice">₱0.00</span></p>
                                    <p class="mb-1 fw-bold fs-5">₱<span id="confirmTotal">0.00</span></p>
                                    <p class="mb-1 text-danger">₱<span id="confirmDown">0.00</span></p>
                                    <p class="mb-0 text-success">₱<span id="confirmBalance">0.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back & Edit</button>
                    <button type="button" class="btn btn-success" onclick="submitForm()">
                        <i class="bi bi-check2-circle"></i> Confirm & Submit Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Draft data bootstrap (for resubmission persistence) -->
    <script>
        window.__draftPlayers = @json($salesOrder->draft_data['players'] ?? []);
        window.__draftImages = @json($salesOrder->draft_data['images'] ?? []);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let isSubmitting = false;
        let availableProducts = [];
        let productsLoaded = false;
        let orderData = {}; // Store players by product ID
        let currentProductId = null;
        let modalPlayerIndex = 0;
        let hydratedFromDraft = false;
        const storageBaseUrl = @json(asset('storage'));

        function normalizeStorageUrl(path) {
            const cleaned = (path || '').toString().replace(/^\/+/, '');
            return `${storageBaseUrl}/${cleaned}`;
        }

        function normalizeUppercaseInput(el) {
            if (!el) return;
            const start = el.selectionStart;
            const end = el.selectionEnd;
            const upper = (el.value || '').toUpperCase();
            if (el.value !== upper) {
                el.value = upper;
                if (typeof start === 'number' && typeof end === 'number') {
                    el.setSelectionRange(start, end);
                }
            }
        }

        function sanitizeJerseyNumberValue(raw) {
            let value = (raw || '').toString();
            value = value.replace(/[^\d+\-]/g, '');
            const sign = value[0] === '+' || value[0] === '-' ? value[0] : '';
            value = value.replace(/[+\-]/g, '');
            return `${sign}${value}`;
        }

        function isAllowedJerseyNumberKey(e) {
            if (!e || typeof e.key !== 'string') return true;
            const key = e.key;
            if (e.ctrlKey || e.metaKey || e.altKey) return true;
            if (key === 'Backspace' || key === 'Delete' || key === 'Tab' || key === 'Enter') return true;
            if (key === 'ArrowLeft' || key === 'ArrowRight' || key === 'ArrowUp' || key === 'ArrowDown') return true;
            if (key === 'Home' || key === 'End') return true;
            if (/^\d$/.test(key)) return true;
            if (key === '+' || key === '-') return true;
            return false;
        }

        function renderDesignPreview(urls) {
            const previewArea = document.getElementById('designPreviewArea');
            const countText = document.getElementById('designCountText');

            const safeUrls = Array.isArray(urls) ? urls.filter(Boolean) : [];
            countText.textContent = safeUrls.length > 0 ? `${safeUrls.length} image${safeUrls.length > 1 ? 's' : ''} selected` : '';

            if (!previewArea) return;

            if (safeUrls.length === 0) {
                previewArea.innerHTML = `
                    <div class="design-empty-state">
                        <i class="bi bi-image"></i>
                        <div class="fw-semibold">No images selected</div>
                        <div class="small text-white-50 mt-1">Click “Upload Images” to add up to 3 design photos.</div>
                    </div>
                `;
                return;
            }

            if (safeUrls.length === 1) {
                previewArea.innerHTML = `
                    <div class="text-center">
                        <img src="${safeUrls[0]}" class="design-preview-img" alt="Design Preview">
                    </div>
                `;
                return;
            }

            const slides = safeUrls.map((url, idx) => {
                const active = idx === 0 ? 'active' : '';
                return `
                    <div class="carousel-item ${active}">
                        <img src="${url}" class="design-preview-img" alt="Design Preview ${idx + 1}">
                    </div>
                `;
            }).join('');

            const indicators = safeUrls.map((_, idx) => {
                const active = idx === 0 ? 'active' : '';
                const ariaCurrent = idx === 0 ? 'true' : 'false';
                return `<button type="button" data-bs-target="#designCarousel" data-bs-slide-to="${idx}" class="${active}" aria-current="${ariaCurrent}" aria-label="Slide ${idx + 1}"></button>`;
            }).join('');

            previewArea.innerHTML = `
                <div id="designCarousel" class="carousel slide design-carousel" data-bs-ride="false">
                    <div class="carousel-indicators">${indicators}</div>
                    <div class="carousel-inner">${slides}</div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#designCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#designCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            `;
        }

        function getDraftDesignUrls() {
            const draft = Array.isArray(window.__draftImages) ? window.__draftImages : [];
            return draft.filter(Boolean).slice(0, 3).map(normalizeStorageUrl);
        }

        function handleDesignImagesChange() {
            const input = document.getElementById('designImages');
            if (!input) return;

            const files = Array.from(input.files || []);
            if (files.length > 3) {
                const dt = new DataTransfer();
                files.slice(0, 3).forEach(f => dt.items.add(f));
                input.files = dt.files;
                alert('You can upload up to 3 images only.');
            }

            const finalFiles = Array.from(input.files || []);
            if (finalFiles.length === 0) {
                renderDesignPreview(getDraftDesignUrls());
                return;
            }

            Promise.all(finalFiles.map(file => new Promise(resolve => {
                const reader = new FileReader();
                reader.onload = e => resolve(e.target.result);
                reader.readAsDataURL(file);
            }))).then(urls => renderDesignPreview(urls));
        }

        // Load available products
        async function loadProducts() {
            const productsGrid = document.getElementById('productsGrid');
            const productsError = document.getElementById('productsError');
            const errorMessage = document.getElementById('errorMessage');
            
            try {
                // Show loading state
                productsError.classList.add('d-none');
                
                console.log('Fetching products from:', '{{ route("api.products") }}');
                console.log('Current page URL:', window.location.href);
                
                let apiUrl = '{{ route("api.products") }}';
                // For Laravel dev server, ensure we use the correct base URL
                if (window.location.host.includes(':8000') || window.location.host.includes('127.0.0.1')) {
                    apiUrl = window.location.origin + '/api/products';
                }
                // If the generated route doesn't include the subdirectory, construct it manually for Apache
                else if (window.location.pathname.includes('/BangKyd/')) {
                    apiUrl = window.location.origin + '/BangKyd/api/products';
                }
                console.log('Using API URL:', apiUrl);
                
                const response = await fetch(apiUrl);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const contentType = (response.headers.get('content-type') || '').toLowerCase();
                if (!contentType.includes('application/json')) {
                    const rawText = await response.text();
                    console.error('Non-JSON response from products API:', rawText.slice(0, 300));
                    throw new Error('Products API returned a non-JSON response. Please check that /api/products is publicly accessible and returning JSON.');
                }

                const products = await response.json();
                console.log('Raw API response:', products);
                
                if (!Array.isArray(products)) {
                    console.error('API response is not an array:', products);
                    throw new Error('Invalid response format from products API');
                }
                
                // Server already filters for active products, so we can use them directly
                availableProducts = products;
                
                console.log('Available products (pre-filtered by server):', availableProducts);
                
                // Hydrate previous selection from draft (if any)
                hydrateOrderDataFromDraft();
                
                if (availableProducts.length === 0) {
                    console.warn('No products returned from API');
                    throw new Error('No products available. Please add products in the admin panel.');
                }
                
                productsLoaded = true;
                displayProducts();
                updateOrderSummary();
                
            } catch (error) {
                console.error('Error loading products:', error);
                productsLoaded = false;
                
                // Show error state
                productsError.classList.remove('d-none');
                errorMessage.textContent = error.message || 'Failed to load available products. Please try again.';
                
                // Show error in products grid
                productsGrid.innerHTML = `
                    <div class="col-12 text-center text-white">
                        <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                        <p class="mt-2">${error.message}</p>
                    </div>
                `;
            }
        }

        function hydrateOrderDataFromDraft() {
            if (hydratedFromDraft) return;
            const draftPlayers = Array.isArray(window.__draftPlayers) ? window.__draftPlayers : [];
            if (draftPlayers.length === 0) {
                hydratedFromDraft = true;
                return;
            }
            const grouped = {};
            draftPlayers.forEach(p => {
                if (!p || !p.product_id) return;
                if (!grouped[p.product_id]) grouped[p.product_id] = [];
                grouped[p.product_id].push({
                    jersey_name: (p.jersey_name || '').toString().toUpperCase(),
                    jersey_number: sanitizeJerseyNumberValue(p.jersey_number || ''),
                    jersey_size: p.jersey_size || ''
                });
            });
            orderData = grouped;
            hydratedFromDraft = true;
            console.log('Hydrated orderData from draft:', orderData);
        }

        function displayProducts() {
            const productsGrid = document.getElementById('productsGrid');
            let html = '';
            
            availableProducts.forEach(product => {
                const playerCount = orderData[product.id] ? orderData[product.id].length : 0;
                html += `
                    <div class="product-card ${playerCount > 0 ? 'selected' : ''}" onclick="openProductModal(${product.id})" id="product-card-${product.id}">
                        <div class="product-header">
                            <div class="product-badge">₱${parseFloat(product.price).toFixed(2)}</div>
                            <h5 class="mb-1"><i class="bi bi-box"></i> ${product.name}</h5>
                            <small>Click to add players</small>
                            ${playerCount > 0 ? `<div class="player-count">${playerCount}</div>` : ''}
                        </div>
                        <div class="product-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Price per piece</small>
                                    <h6 class="text-primary">₱${parseFloat(product.price).toFixed(2)}</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Players</small>
                                    <h6 class="${playerCount > 0 ? 'text-success' : 'text-muted'}">${playerCount} selected</h6>
                                </div>
                            </div>
                            ${playerCount > 0 ? 
                                `<div class="mt-2">
                                    <small class="text-success"><i class="bi bi-check-circle"></i> ${playerCount} player${playerCount > 1 ? 's' : ''} added</small>
                                </div>` : 
                                `<div class="mt-2">
                                    <small class="text-muted"><i class="bi bi-plus-circle"></i> Click to add players</small>
                                </div>`
                            }
                        </div>
                    </div>
                `;
            });
            
            productsGrid.innerHTML = html;
        }

        function openProductModal(productId) {
            currentProductId = productId;
            const product = availableProducts.find(p => p.id == productId);
            if (!product) return;
            
            // Update modal title
            document.getElementById('modalProductTitle').innerHTML = 
                `<i class="bi bi-person-plus"></i> Add Players - ${product.name} (₱${parseFloat(product.price).toFixed(2)})`;
            
            // Load existing players for this product
            loadModalPlayers();
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        }

        function loadModalPlayers() {
            const container = document.getElementById('modalPlayersContainer');
            if (!orderData[currentProductId] || orderData[currentProductId].length === 0) {
                container.innerHTML = `<div class="text-center text-muted py-5 bg-light rounded-3 border-dashed" id="modalEmptyState" style="border: 2px dashed #dee2e6;"><i class="bi bi-person-plus text-secondary" style="font-size: 3rem;"></i><h5 class="mt-3">No Players Added Yet</h5><p class="text-muted">Click the "Add Player" button above to get started.</p></div>`;
                return;
            }
            const emptyState = document.getElementById('modalEmptyState');
            if (emptyState) emptyState.remove();
            let html = '';
            
            orderData[currentProductId].forEach((player, index) => {
                html += createModalPlayerCard(player, index);
            });
            
            container.innerHTML = html;
            modalPlayerIndex = orderData[currentProductId].length;
        }

        function createModalPlayerCard(player = {}, index = null) {
            const cardIndex = index !== null ? index : modalPlayerIndex++;
            return `
                <div class="card mb-3 modal-player-card shadow-sm" data-index="${cardIndex}" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.1);">
                    <div class="card-header bg-light" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-person"></i> Player ${cardIndex + 1}</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeModalPlayer(${cardIndex})">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jersey Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control jersey-name" value="${(player.jersey_name || '').toString().toUpperCase()}" style="text-transform: uppercase;"
                                       placeholder="Player name or text on jersey" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jersey Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control jersey-number" value="${sanitizeJerseyNumberValue(player.jersey_number || '')}"
                                       inputmode="numeric" autocomplete="off" placeholder="Jersey #" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jersey Size <span class="text-danger">*</span></label>
                                <select class="form-select jersey-size" required>
                                    <option value="">Select Size</option>
                                    <option value="XS" ${player.jersey_size === 'XS' ? 'selected' : ''}>XS</option>
                                    <option value="S" ${player.jersey_size === 'S' ? 'selected' : ''}>S</option>
                                    <option value="M" ${player.jersey_size === 'M' ? 'selected' : ''}>M</option>
                                    <option value="L" ${player.jersey_size === 'L' ? 'selected' : ''}>L</option>
                                    <option value="XL" ${player.jersey_size === 'XL' ? 'selected' : ''}>XL</option>
                                    <option value="2XL" ${player.jersey_size === '2XL' ? 'selected' : ''}>2XL</option>
                                    <option value="3XL" ${player.jersey_size === '3XL' ? 'selected' : ''}>3XL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function addModalPlayer() {
            const container = document.getElementById('modalPlayersContainer');
            if (!container) return;
            const emptyState = document.getElementById('modalEmptyState');
            if (emptyState) emptyState.remove();
            
            // Create new player card
            const playerHtml = createModalPlayerCard();
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = playerHtml;
            const playerCard = tempDiv.firstElementChild;
            
            container.appendChild(playerCard);
        }

        function removeModalPlayer(index) {
            const playerCard = document.querySelector(`[data-index="${index}"]`);
            if (playerCard) {
                playerCard.remove();
                
                // Show empty state if no players
                const container = document.getElementById('modalPlayersContainer');
                if (container.querySelectorAll('.modal-player-card').length === 0) {
                    container.innerHTML = `<div class="text-center text-muted py-5 bg-light rounded-3 border-dashed" id="modalEmptyState" style="border: 2px dashed #dee2e6;"><i class="bi bi-person-plus text-secondary" style="font-size: 3rem;"></i><h5 class="mt-3">No Players Added Yet</h5><p class="text-muted">Click the "Add Player" button above to get started.</p></div>`;
                }
            }
        }

        function saveModalPlayers() {
            const playerCards = document.querySelectorAll('#modalPlayersContainer .modal-player-card');
            const players = [];
            
            playerCards.forEach(card => {
                const jerseyName = (card.querySelector('.jersey-name').value || '').trim().toUpperCase();
                const jerseyNumber = sanitizeJerseyNumberValue((card.querySelector('.jersey-number').value || '').trim());
                const jerseySize = card.querySelector('.jersey-size').value;
                
                if (jerseyName && jerseyNumber && jerseySize) {
                    players.push({
                        jersey_name: jerseyName,
                        jersey_number: jerseyNumber,
                        jersey_size: jerseySize
                    });
                }
            });
            
            // Save to order data
            if (players.length > 0) {
                if (!orderData[currentProductId]) {
                    orderData[currentProductId] = [];
                }
                orderData[currentProductId] = players;
            } else {
                // Remove product if no players
                delete orderData[currentProductId];
            }
            
            // Update display
            displayProducts();
            updateOrderSummary();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            modal.hide();
        }

        function clearModalPlayers() {
            // Remove all players for this product
            delete orderData[currentProductId];
            
            // Reload modal
            loadModalPlayers();
            
            // Update display
            displayProducts();
            updateOrderSummary();
        }

        function updateOrderSummary() {
            const orderSummary = document.getElementById('orderSummary');
            const totalAmount = document.getElementById('totalAmount');
            const playerCountSpan = document.getElementById('playerCount');
            const submitBtn = document.getElementById('submitBtn');
            
            let totalPlayers = 0;
            let totalAmount_calc = 0;
            
            // Calculate totals
            Object.keys(orderData).forEach(productId => {
                const product = availableProducts.find(p => p.id == productId);
                const players = orderData[productId];
                if (product && players) {
                    totalPlayers += players.length;
                    totalAmount_calc += players.length * parseFloat(product.price);
                }
            });
            
            if (totalPlayers === 0) {
                orderSummary.style.display = 'none';
                submitBtn.classList.add('d-none');
            } else {
                orderSummary.style.display = 'block';
                submitBtn.classList.remove('d-none');
                
                playerCountSpan.textContent = totalPlayers;
                totalAmount.textContent = `₱${totalAmount_calc.toFixed(2)}`;
            }
        }

        function validateBeforeConfirmation() {
            if (Object.keys(orderData).length === 0) {
                alert('Please add at least one player before submitting the order.');
                return false;
            }

            const designUpload = document.getElementById('designImages');
            const hasDraftImages = Array.isArray(window.__draftImages) && window.__draftImages.length > 0;
            if ((!designUpload || !designUpload.files || designUpload.files.length === 0) && !hasDraftImages) {
                alert('Please upload a design before submitting the order.');
                return false;
            }

            return true;
        }

        function updateConfirmationModal() {
            const summaryEl = document.getElementById('confirmationOrderSummary');
            const qtyEl = document.getElementById('confirmQty');
            const priceEl = document.getElementById('confirmPrice');
            const totalEl = document.getElementById('confirmTotal');
            const downEl = document.getElementById('confirmDown');
            const balanceEl = document.getElementById('confirmBalance');

            let totalPlayers = 0;
            let totalAmount_calc = 0;
            const prices = [];
            const productLines = [];

            Object.keys(orderData).forEach(productId => {
                const product = availableProducts.find(p => p.id == productId);
                const players = orderData[productId];
                if (!product || !players) return;
                const qty = players.length;
                const price = parseFloat(product.price);
                const lineTotal = qty * price;
                totalPlayers += qty;
                totalAmount_calc += lineTotal;
                prices.push(price);
                productLines.push({
                    name: product.name,
                    qty,
                    lineTotal
                });
            });

            if (qtyEl) qtyEl.textContent = totalPlayers.toString();
            if (totalEl) totalEl.textContent = totalAmount_calc.toFixed(2);
            const down = totalAmount_calc * 0.5;
            if (downEl) downEl.textContent = down.toFixed(2);
            if (balanceEl) balanceEl.textContent = (totalAmount_calc - down).toFixed(2);

            const distinctPrices = Array.from(new Set(prices.map(p => p.toFixed(2))));
            if (priceEl) {
                if (distinctPrices.length === 1 && distinctPrices[0]) {
                    priceEl.textContent = `₱${distinctPrices[0]}`;
                } else {
                    priceEl.textContent = 'MIXED';
                }
            }

            if (!summaryEl) return;

            if (productLines.length === 0) {
                summaryEl.innerHTML = '';
                return;
            }

            const itemsHtml = productLines.map(line => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">${line.name}</div>
                        <small class="text-muted">${line.qty} player${line.qty > 1 ? 's' : ''}</small>
                    </div>
                    <div class="fw-bold">₱${line.lineTotal.toFixed(2)}</div>
                </li>
            `).join('');

            summaryEl.innerHTML = `
                <div class="card bg-light mb-3">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            ${itemsHtml}
                        </ul>
                    </div>
                </div>
            `;
        }

        function openConfirmationModal() {
            if (isSubmitting) return;
            if (!validateBeforeConfirmation()) return;
            updateConfirmationModal();
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }

        function prepareFormSubmission() {
            if (isSubmitting) return false;
            
            // Validate we have orders
            if (Object.keys(orderData).length === 0) {
                alert('Please add at least one player before submitting the order.');
                return false;
            }
            
            // Check if design is uploaded or present in draft
            const designUpload = document.getElementById('designImages');
            const hasDraftImages = Array.isArray(window.__draftImages) && window.__draftImages.length > 0;
            if ((!designUpload || !designUpload.files || designUpload.files.length === 0) && !hasDraftImages) {
                alert('Please upload a design before submitting the order.');
                return false;
            }
            
            // Prepare form data
            const form = document.getElementById('orderForm');
            
            // Clear any existing product inputs
            const existingInputs = form.querySelectorAll('input[name^="players["], input[name^="products["]');
            existingInputs.forEach(input => input.remove());
            
            // Add product data to form
            let playerIndex = 0;
            Object.keys(orderData).forEach(productId => {
                const players = orderData[productId];
                players.forEach(player => {
                    const inputs = [
                        { name: `players[${playerIndex}][product_id]`, value: productId },
                        { name: `players[${playerIndex}][jersey_name]`, value: player.jersey_name },
                        { name: `players[${playerIndex}][jersey_number]`, value: player.jersey_number },
                        { name: `players[${playerIndex}][jersey_size]`, value: player.jersey_size }
                    ];
                    
                    inputs.forEach(inputData => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = inputData.name;
                        input.value = inputData.value;
                        form.appendChild(input);
                    });
                    
                    playerIndex++;
                });
            });
            
            isSubmitting = true;
            console.log('Form submission prepared with', playerIndex, 'players');
            return true;
        }

        function submitFormManually() {
            // Call the same validation and preparation function
            if (prepareFormSubmission()) {
                const form = document.getElementById('orderForm');
                if (form) {
                    console.log('Submitting form manually...');
                    form.submit();
                } else {
                    console.error('Form not found!');
                    alert('Error: Could not find the form to submit.');
                }
            }
        }

        function submitForm() {
            if (prepareFormSubmission()) {
                const form = document.getElementById('orderForm');
                if (form) {
                    form.submit();
                }
            }
        }

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            const modalPlayersContainer = document.getElementById('modalPlayersContainer');
            if (modalPlayersContainer) {
                modalPlayersContainer.addEventListener('input', function(e) {
                    const target = e.target;
                    if (!target || !target.classList) return;
                    if (target.classList.contains('jersey-name')) {
                        normalizeUppercaseInput(target);
                    } else if (target.classList.contains('jersey-number')) {
                        const cleaned = sanitizeJerseyNumberValue(target.value);
                        if (target.value !== cleaned) target.value = cleaned;
                    }
                });

                modalPlayersContainer.addEventListener('keydown', function(e) {
                    const target = e.target;
                    if (!target || !target.classList) return;
                    if (!target.classList.contains('jersey-number')) return;
                    if (!isAllowedJerseyNumberKey(e)) e.preventDefault();
                });
            }

            renderDesignPreview(getDraftDesignUrls());
            loadProducts();
        });
    </script>
</body>
</html>
