<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Payment Cancelled</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-warning mb-3">Payment Was Cancelled</h4>
                        <p class="text-muted mb-4">Your payment was cancelled or failed. No charges were made to your account.</p>
                        
                        <div class="alert alert-warning">
                            <strong>Note:</strong> If you believe this is an error, please contact our support team.
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-home"></i> Go to Dashboard
                            </a>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Try Again
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 