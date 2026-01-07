<!DOCTYPE html>
<html>
<head>
    <title>Downloading Invoice...</title>
    <script>
        // Auto download the PDF
        window.onload = function() {
            // Get the dtl_kode from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const dtl_kode = urlParams.get('code');

            if (dtl_kode) {
                // Create a hidden iframe to trigger download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = '<?php echo base_url(); ?>Cetak/download/' + dtl_kode;
                document.body.appendChild(iframe);

                // Show message and close after download starts
                setTimeout(function() {
                    document.getElementById('message').innerHTML = 'Download started. You can close this window.';
                }, 2000);
            } else {
                document.getElementById('message').innerHTML = 'Invalid download link.';
            }
        };
    </script>
</head>
<body>
    <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
        <h2>Downloading Your Invoice</h2>
        <p id="message">Please wait while we prepare your download...</p>
        <div style="margin: 20px 0;">
            <div style="width: 200px; height: 20px; background: #f0f0f0; border-radius: 10px; margin: 0 auto; overflow: hidden;">
                <div style="width: 100%; height: 100%; background: #4CAF50; animation: progress 2s ease-in-out;"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</body>
</html>