<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .form-label {
            font-weight: 600;
        }

        #result {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <div class="card p-4 bg-white">
                <h3 class="mb-4 text-center text-primary">Secure Payment</h3>

                <form id="payment-form">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select a method</option>
                            <option value="stripe">Stripe</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01"
                               placeholder="e.g. 100.00" required>
                    </div>

                    <div class="mb-3">
                        <label for="currency" class="form-label">Currency</label>
                        <input type="text" class="form-control" id="currency" name="currency" placeholder="USD"
                               maxlength="3" value="USD">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Pay Now</button>
                    </div>
                </form>

                <div id="result" class="mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AJAX Logic -->
    <script>
        document.getElementById('payment-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '';

            const formData = {
                payment_method: document.getElementById('payment_method').value,
                amount: document.getElementById('amount').value,
                currency: document.getElementById('currency').value
            };

            try {
                const response = await fetch('{{ route("payments.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    resultDiv.innerHTML = `<div class="alert alert-success">✅ ${data.message}</div>`;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">❌ ${data.message || 'Payment failed.'}</div>`;
                }

            } catch (error) {
                resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ Error processing payment.</div>`;
                console.error(error);
            }
        });
    </script>
</body>
</html>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            axios.post('/payments', {
                    payment_method: document.getElementById('payment_method').value,
                    amount: document.getElementById('amount').value,
                    currency: document.getElementById('currency').value,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    document.getElementById('result').innerHTML = `
                <div class="alert alert-success">${response.data.message}</div>
            `;
                }).catch(error => {
                    if (error.response && error.response.data.errors) {
                        const errors = Object.values(error.response.data.errors).flat().join('<br>');
                        document.getElementById('result').innerHTML = `
                    <div class="alert alert-danger">${errors}</div>
                `;
                    } else {
                        document.getElementById('result').innerHTML = `
                    <div class="alert alert-danger">Something went wrong.</div>
                `;
                    }
                });
        });
    </script>
</body>

</html>
