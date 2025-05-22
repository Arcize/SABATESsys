<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfHelper
{
    public static function generatePdf($htmlContent, $filename = "documento.pdf", $download = true)
    {

        $options = new Options();
        $options->set("isHtml5ParserEnabled", true);
        $options->set("defaultFont", "Arial");

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper("A4", "portrait");
        $dompdf->render();

        if ($download) {
            $dompdf->stream($filename, ["Attachment" => true]);
        } else {
            return $dompdf->output();
        }
    }
}
