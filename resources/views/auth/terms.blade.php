<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Terms and Conditions</h4>
                    </div>
                    <div class="card-body">
                        <div class="terms-content" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px;">
                            <h5>ERP System Terms and Conditions</h5>
                            <p>By accessing and using this ERP system, you agree to the following terms:</p>
                            
                            <h6>1. Data Security</h6>
                            <p>You are responsible for maintaining the confidentiality of your login credentials and all activities under your account.</p>
                            
                            <h6>2. System Usage</h6>
                            <p>This system is for authorized personnel only. Unauthorized access is prohibited.</p>
                            
                            <h6>3. Data Privacy</h6>
                            <p>All data entered into the system must comply with applicable privacy laws and company policies.</p>
                            
                            <h6>4. System Availability</h6>
                            <p>While we strive for 100% uptime, the system may be unavailable during maintenance periods.</p>
                            
                            <h6>5. User Responsibilities</h6>
                            <p>Users must ensure data accuracy and report any security incidents immediately.</p>
                        </div>
                        
                        <form method="POST" action="{{ route('terms.accept') }}">
                            @csrf
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="acceptTerms" required>
                                <label class="form-check-label" for="acceptTerms">
                                    I have read and agree to the Terms and Conditions
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Accept and Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>