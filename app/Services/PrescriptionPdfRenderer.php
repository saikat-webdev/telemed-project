<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Prescription;

class PrescriptionPdfRenderer
{
    public function render(Prescription $prescription, Appointment $appointment): string
    {
        $lines = [
            'HealthHub Prescription',
            '',
            'Appointment #'.$appointment->id,
            'Doctor: Dr. '.($appointment->doctor->name ?? 'Doctor'),
            'Patient: '.$prescription->patient_name,
            'Age/Gender: '.($prescription->age_gender ?: 'Not specified'),
            'Weight: '.($prescription->weight ?: 'Not specified'),
            'Height: '.($prescription->height ?: 'Not specified'),
            'Issued At: '.optional($prescription->issued_at)->format('d M Y h:i A'),
            '',
            'Chief Complaints:',
            ...$this->wrap($prescription->chief_complaints ?: 'No chief complaints recorded.'),
            '',
            'Diagnosis & Notes:',
            ...$this->wrap($prescription->diagnosis_notes ?: 'No diagnosis notes recorded.'),
            '',
            'Medications:',
        ];

        foreach ($prescription->medicines ?? [] as $medicine) {
            $lines[] = '- '.trim(($medicine['name'] ?? 'Medicine').' | '.($medicine['dosage'] ?? 'As directed').' | '.($medicine['duration'] ?? 'Until follow-up'));
        }

        $lines[] = '';
        $lines[] = 'Additional Notes:';
        $lines = [...$lines, ...$this->wrap($prescription->additional_notes ?: 'No additional notes recorded.')];

        $contentStream = $this->buildContentStream($lines);
        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>';
        $objects[] = '<< /Length '.strlen($contentStream)." >>\nstream\n".$contentStream."\nendstream";
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        return $this->buildPdf($objects);
    }

    protected function wrap(string $text, int $lineLength = 85): array
    {
        return preg_split('/\r\n|\r|\n/', wordwrap($text, $lineLength)) ?: [];
    }

    protected function buildContentStream(array $lines): string
    {
        $output = ['BT', '/F1 11 Tf', '50 740 Td', '14 TL'];

        foreach ($lines as $index => $line) {
            $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $line);
            $output[] = ($index === 0 ? '' : 'T* ').'('.$escaped.') Tj';
        }

        $output[] = 'ET';

        return implode("\n", array_filter($output, fn ($line) => $line !== ''));
    }

    protected function buildPdf(array $objects): string
    {
        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i < count($offsets); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }
}
