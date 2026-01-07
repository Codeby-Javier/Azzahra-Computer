<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdfgenerator {
    public function generate($html, $filename = 'invoice', $paper = 'A4', $orientation = 'portrait', $stream = TRUE)
    {
        if (!file_exists(APPPATH.'../vendor/autoload.php')) {
            // Fallback: output HTML as text if Dompdf not installed
            header('Content-Type: text/html');
            echo $html;
            return;
        }

        require_once APPPATH.'../vendor/autoload.php';

        if (!class_exists('Dompdf\Dompdf')) {
            // Fallback: output HTML inline
            header('Content-Type: text/html');
            header('Content-Disposition: inline; filename="invoice.html"');
            echo $html;
            return;
        }

        $options = new Options();
        $options->set('isRemoteEnabled', TRUE); // agar bisa load gambar/logo
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();

        if ($stream) {
            $dompdf->stream($filename.".pdf", array("Attachment" => true));
        } else {
            // For preview, output to browser inline
            $dompdf->stream($filename.".pdf", array("Attachment" => false));
        }
    }
}
