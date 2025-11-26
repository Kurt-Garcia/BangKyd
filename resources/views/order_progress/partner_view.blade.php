<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Progress - BangKyd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: url('{{ asset('img/BG.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            padding: 2rem 0;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }
        .progress-container {
            position: relative;
            z-index: 1;
        }
        .progress-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .stage-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .stage-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .stage-card.active {
            border: 3px solid #fa709a;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ebf2 100%);
        }
        .stage-card.completed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 3px solid #28a745;
        }
        .stage-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1rem;
            transition: all 0.3s ease;
        }
        .stage-card.active .stage-icon {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            animation: pulse 2s infinite;
        }
        .stage-card.completed .stage-icon {
            background: #28a745;
            color: white;
        }
        .stage-card.pending .stage-icon {
            background: #e9ecef;
            color: #6c757d;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .progress-bar-custom {
            height: 30px;
            border-radius: 15px;
            background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .order-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .quantity-input {
            font-size: 1.5rem;
            text-align: center;
            border: 3px solid #fa709a;
            border-radius: 15px;
            padding: 1rem;
        }
        .stage-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .notes-textarea {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Order Header -->
        <div class="order-header">
            <div class="text-center mb-3">
                <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo" style="height: 60px;">
            </div>
            <h2 class="text-center mb-4">Order Production Progress</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <div class="text-muted small">Order Number</div>
                    <div class="fw-bold fs-5">{{ $progress->order->order_number }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="text-muted small">Customer</div>
                    <div class="fw-bold fs-5">{{ $progress->order->accountReceivable->submission->salesOrder->so_name }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="text-muted small">Total Quantity</div>
                    <div class="fw-bold fs-5">{{ $progress->total_quantity }} jerseys</div>
                </div>
            </div>
            
            <!-- Overall Progress Bar -->
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Overall Progress</span>
                    <span class="fw-bold">{{ $progress->getProgressPercentage() }}%</span>
                </div>
                <div class="progress" style="height: 30px; border-radius: 15px;">
                    <div class="progress-bar progress-bar-custom" role="progressbar" 
                         style="width: {{ $progress->getProgressPercentage() }}%;" 
                         aria-valuenow="{{ $progress->getProgressPercentage() }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Stage 1: Print & Press -->
        <div class="stage-card {{ $progress->current_stage === 'print_press' ? 'active' : ($progress->print_press_completed_at ? 'completed' : 'pending') }}">
            <div class="stage-icon">
                @if($progress->print_press_completed_at)
                    <i class="bi bi-check-circle-fill"></i>
                @else
                    <i class="bi bi-printer-fill"></i>
                @endif
            </div>
            <h3 class="stage-title text-center">
                <i class="bi bi-printer"></i> Stage 1: Print & Press
                @if($progress->print_press_completed_at)
                    <span class="badge bg-success">Completed</span>
                @elseif($progress->current_stage === 'print_press')
                    <span class="badge bg-primary">In Progress</span>
                @else
                    <span class="badge bg-secondary">Pending</span>
                @endif
            </h3>
            
            @if($progress->current_stage === 'print_press')
            <form action="{{ route('progress.update', $progress->unique_link) }}" method="POST">
                @csrf
                <input type="hidden" name="stage" value="print_press">
                
                <div class="mb-3">
                    <p class="text-center lead">
                        <strong>{{ $progress->total_quantity }} jerseys</strong> to be printed and pressed
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Notes (Optional)</label>
                    <textarea name="notes" class="form-control notes-textarea" rows="3" 
                              placeholder="Add any notes or remarks...">{{ $progress->notes }}</textarea>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-gradient btn-lg">
                        <i class="bi bi-check-circle"></i> Mark Print & Press as Done
                    </button>
                </div>
            </form>
            @else
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle-fill"></i> Print & Press completed on {{ $progress->print_press_completed_at->format('M d, Y h:i A') }}
                <div class="mt-2 fw-bold">{{ $progress->total_quantity }} jerseys completed</div>
            </div>
            @endif
        </div>

        <!-- Stage 2: Tailoring -->
        <div class="stage-card {{ $progress->current_stage === 'tailoring' ? 'active' : ($progress->tailoring_completed_at ? 'completed' : 'pending') }}">
            <div class="stage-icon">
                @if($progress->tailoring_completed_at)
                    <i class="bi bi-check-circle-fill"></i>
                @else
                    <i class="bi bi-scissors"></i>
                @endif
            </div>
            <h3 class="stage-title text-center">
                <i class="bi bi-scissors"></i> Stage 2: Tailoring
                @if($progress->tailoring_completed_at)
                    <span class="badge bg-success">Completed</span>
                @elseif($progress->current_stage === 'tailoring')
                    <span class="badge bg-primary">In Progress</span>
                @else
                    <span class="badge bg-secondary">Pending</span>
                @endif
            </h3>
            
            @if($progress->current_stage === 'tailoring')
            <form action="{{ route('progress.update', $progress->unique_link) }}" method="POST">
                @csrf
                <input type="hidden" name="stage" value="tailoring">
                
                <div class="mb-3">
                    <p class="text-center lead">
                        <strong>{{ $progress->total_quantity }} jerseys</strong> to be tailored
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Notes (Optional)</label>
                    <textarea name="notes" class="form-control notes-textarea" rows="3" 
                              placeholder="Add any notes or remarks...">{{ $progress->notes }}</textarea>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-gradient btn-lg">
                        <i class="bi bi-check-circle"></i> Mark Tailoring as Done
                    </button>
                </div>
            </form>
            @elseif($progress->tailoring_completed_at)
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle-fill"></i> Tailoring completed on {{ $progress->tailoring_completed_at->format('M d, Y h:i A') }}
                <div class="mt-2 fw-bold">{{ $progress->total_quantity }} jerseys completed</div>
            </div>
            @else
            <div class="alert alert-secondary text-center">
                <i class="bi bi-hourglass-split"></i> Waiting for print & press to complete
            </div>
            @endif
        </div>

        @if($progress->current_stage === 'completed')
        <div class="stage-card completed">
            <div class="stage-icon">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <h2 class="text-center mb-3">
                <i class="bi bi-check-circle-fill text-success"></i> All Stages Completed!
            </h2>
            <p class="text-center lead">The order is now ready for delivery.</p>
            <div class="alert alert-info text-center mt-3">
                <i class="bi bi-info-circle-fill"></i> Customer must pay the remaining balance before delivery.
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
