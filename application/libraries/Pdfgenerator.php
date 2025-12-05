<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdfgenerator {
    public function generate($html, $filename = 'invoice', $paper = 'A4', $orientation = 'portrait', $stream = TRUE)
    {
        require_once APPPATH.'../vendor/autoload.php';
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
