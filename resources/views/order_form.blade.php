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
            background: url('{{ asset('img/BG.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .image-upload-box {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .image-upload-box:hover {
            border-color: #f04826;
            background-color: #fff5f3;
        }
        .image-preview {
            max-width: 100%;
            max-height: 150px;
            margin-top: 10px;
            border-radius: 8px;
        }
        .player-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="card">
            <div class="card-header text-white" style="background: url('{{ asset('img/BG.jpg') }}') center center; background-size: cover; position: relative;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);"></div>
                <div class="d-flex justify-content-between align-items-center" style="position: relative; z-index: 1;">
                    <div>
                        <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Order Form</h4>
                        <small>{{ $salesOrder->so_name }} ({{ $salesOrder->so_number }})</small>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-light text-dark fs-6">
                            ₱{{ number_format($salesOrder->product->price, 2) }} / pcs
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($salesOrder->draft_data)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle"></i> <strong>Resubmission Requested:</strong> You can edit your previous submission below. Please review and make any necessary corrections.
                    </div>
                @endif

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> <strong>Price:</strong> ₱{{ number_format($salesOrder->product->price, 2) }} per jersey | <strong>Down Payment:</strong> 50% upon order confirmation
                </div>

                <form action="{{ route('order.submit', $salesOrder->unique_link) }}" method="POST" enctype="multipart/form-data" id="orderForm">
                    @csrf
                    
                    <h5 class="mb-3"><i class="bi bi-images"></i> Upload Design Images (Up to 3)</h5>
                    <div class="row mb-4">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="col-md-4 mb-3">
                            <label class="image-upload-box" for="image{{ $i }}">
                                <input type="file" class="d-none" id="image{{ $i }}" name="images[]" accept="image/*" onchange="previewImage(this, {{ $i }})">
                                <div id="preview{{ $i }}">
                                    @if($salesOrder->draft_data && isset($salesOrder->draft_data['images'][$i-1]))
                                        <img src="{{ asset('storage/' . $salesOrder->draft_data['images'][$i-1]) }}" class="image-preview" alt="Previous Upload">
                                        <p class="mb-0 text-muted mt-2"><small>Click to change</small></p>
                                    @else
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0 text-muted">Click to upload<br><small>Image {{ $i }}</small></p>
                                    @endif
                                </div>
                            </label>
                            @error('images.' . ($i-1))
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @endfor
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Player Information</h5>
                        <button type="button" class="btn btn-sm text-white" style="background: url('{{ asset('img/BG.jpg') }}') center center; background-size: cover; position: relative; overflow: hidden;" onclick="addPlayer()">
                            <span style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"></span>
                            <span style="position: relative; z-index: 1;"><i class="bi bi-plus-circle"></i> Add Player</span>
                        </button>
                    </div>

                    <div id="playersContainer">
                        <div class="player-card" data-player="1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Player 1</h6>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removePlayer(1)" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="players[0][full_name]" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Jersey Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="players[0][jersey_name]" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Jersey Number <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="players[0][jersey_number]" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Jersey Size <span class="text-danger">*</span></label>
                                    <select class="form-select" name="players[0][jersey_size]" required>
                                        <option value="">Select Size</option>
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="2XL">2XL</option>
                                        <option value="3XL">3XL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="button" class="btn btn-lg text-white" style="background: url('{{ asset('img/BG.jpg') }}') center center; background-size: cover; position: relative; overflow: hidden;" onclick="showConfirmation()">
                            <span style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"></span>
                            <span style="position: relative; z-index: 1;"><i class="bi bi-check-circle"></i> Review & Submit Order</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <small class="text-white">{{ \App\Models\SystemSetting::get('business_name', 'BangKyd ERP') }}</small>
            @if(\App\Models\SystemSetting::get('business_phone') || \App\Models\SystemSetting::get('business_email'))
                <br>
                <small class="text-white">
                    @if(\App\Models\SystemSetting::get('business_phone'))
                        <i class="bi bi-telephone"></i> {{ \App\Models\SystemSetting::get('business_phone') }}
                    @endif
                    @if(\App\Models\SystemSetting::get('business_phone') && \App\Models\SystemSetting::get('business_email'))
                        |
                    @endif
                    @if(\App\Models\SystemSetting::get('business_email'))
                        <i class="bi bi-envelope"></i> {{ \App\Models\SystemSetting::get('business_email') }}
                    @endif
                </small>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirm Your Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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

                    <div id="orderSummary"></div>

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
                                    <p class="mb-1">₱<span id="confirmPrice">0.00</span></p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let playerCount = 1;
        const pricePerPcs = {{ $salesOrder->product->price ?? 0 }};
        
        // Load draft data if exists
        @if($salesOrder->draft_data && isset($salesOrder->draft_data['players']))
        const draftPlayers = @json($salesOrder->draft_data['players']);
        @else
        const draftPlayers = null;
        @endif

        function previewImage(input, index) {
            const preview = document.getElementById('preview' + index);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="image-preview" alt="Preview">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addPlayer() {
            playerCount++;
            const container = document.getElementById('playersContainer');
            const playerIndex = container.children.length;
            
            const playerCard = document.createElement('div');
            playerCard.className = 'player-card';
            playerCard.setAttribute('data-player', playerCount);
            playerCard.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Player ${playerCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removePlayer(${playerCount})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="players[${playerIndex}][full_name]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Jersey Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="players[${playerIndex}][jersey_name]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Jersey Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="players[${playerIndex}][jersey_number]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Jersey Size <span class="text-danger">*</span></label>
                        <select class="form-select" name="players[${playerIndex}][jersey_size]" required>
                            <option value="">Select Size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                            <option value="3XL">3XL</option>
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(playerCard);
        }

        function removePlayer(playerId) {
            const playerCard = document.querySelector(`[data-player="${playerId}"]`);
            if (playerCard) {
                playerCard.remove();
                // Renumber remaining players
                updatePlayerNumbers();
            }
        }

        function updatePlayerNumbers() {
            const players = document.querySelectorAll('.player-card');
            players.forEach((player, index) => {
                const heading = player.querySelector('h6');
                heading.textContent = `Player ${index + 1}`;
                player.setAttribute('data-player', index + 1);
            });
        }

        function showConfirmation() {
            // Validate form first
            const form = document.getElementById('orderForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get player count
            const playerCards = document.querySelectorAll('.player-card');
            const playerCount = playerCards.length;

            // Calculate amounts
            const totalAmount = playerCount * pricePerPcs;
            const downPayment = totalAmount * 0.5;
            const balance = totalAmount - downPayment;

            // Update summary
            document.getElementById('confirmQty').textContent = playerCount;
            document.getElementById('confirmPrice').textContent = pricePerPcs.toFixed(2);
            document.getElementById('confirmTotal').textContent = totalAmount.toFixed(2);
            document.getElementById('confirmDown').textContent = downPayment.toFixed(2);
            document.getElementById('confirmBalance').textContent = balance.toFixed(2);

            // Build player list summary
            let playerList = '<h6>Players:</h6><ol>';
            playerCards.forEach((card) => {
                const fullName = card.querySelector('input[name*="[full_name]"]').value;
                const jerseyName = card.querySelector('input[name*="[jersey_name]"]').value;
                const jerseyNumber = card.querySelector('input[name*="[jersey_number]"]').value;
                const jerseySize = card.querySelector('select[name*="[jersey_size]"]').value;
                playerList += `<li><strong>${fullName}</strong> - Jersey: "${jerseyName}" #${jerseyNumber} (${jerseySize})</li>`;
            });
            playerList += '</ol>';
            document.getElementById('orderSummary').innerHTML = playerList;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }

        function submitForm() {
            document.getElementById('orderForm').submit();
        }

        // Load draft data on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (draftPlayers && draftPlayers.length > 0) {
                const container = document.getElementById('playersContainer');
                
                // Clear the default empty player card
                container.innerHTML = '';
                
                // Add player cards from draft data
                draftPlayers.forEach((player, index) => {
                    const playerCard = document.createElement('div');
                    playerCard.className = 'player-card';
                    playerCard.setAttribute('data-player', index + 1);
                    playerCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Player ${index + 1}</h6>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removePlayer(${index + 1})" ${index === 0 ? 'style="display: none;"' : ''}>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="players[${index}][full_name]" value="${player.full_name || ''}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Jersey Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="players[${index}][jersey_name]" value="${player.jersey_name || ''}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Jersey Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="players[${index}][jersey_number]" value="${player.jersey_number || ''}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Jersey Size <span class="text-danger">*</span></label>
                                <select class="form-select" name="players[${index}][jersey_size]" required>
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
                    `;
                    container.appendChild(playerCard);
                });
                
                playerCount = draftPlayers.length;
            }
        });
    </script>
</body>
</html>
