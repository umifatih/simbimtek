<?php

namespace App\Support;

use PhpOffice\PhpWord\IOFactory;

class DocxPreviewer
{
    /**
     * Load file .docx yang sudah diisi (hasil TemplateProcessor->saveAs())
     * lalu convert jadi HTML asli pakai writer bawaan PHPWord.
     * Style-nya di-scope ke .docx-preview biar tidak bentrok
     * dengan CSS Tailwind punya aplikasi.
     */
    public static function toHtml(string $docxPath): string
    {
        $phpWord = IOFactory::load($docxPath);
        $writer = IOFactory::createWriter($phpWord, 'HTML');

        ob_start();
        $writer->save('php://output');
        $full = ob_get_clean();

        $style = '';
        if (preg_match('/<style[^>]*>(.*?)<\/style>/is', $full, $m)) {
            $style = $m[1];
        }

        $body = $full;
        if (preg_match('/<body[^>]*>(.*)<\/body>/is', $full, $m)) {
            $body = $m[1];
        }

        $style = self::scopeCss($style, '.docx-preview');
        $style .= self::fixUkuranGambar();

        return '<style>' . $style . '</style><div class="docx-preview">' . $body . '</div>';
    }

    /**
     * PHPWord tidak selalu melestarikan width/height yang diset lewat
     * TemplateProcessor->setImageValue() (mis. logo 60x60, QR 200x200)
     * begitu file di-load ulang untuk di-convert ke HTML — gambar bisa
     * muncul di resolusi aslinya yang jauh lebih besar. Paksa batasi
     * dengan !important supaya tetap wajar di preview.
     */
    private static function fixUkuranGambar(): string
    {
        return '.docx-preview img { max-width: 140px !important; max-height: 140px !important; width: auto !important; height: auto !important; }';
    }

    /**
     * Tambahkan prefix ".docx-preview" ke tiap selector CSS supaya
     * style generik dari PHPWord (mis. "body", ".Normal", "table")
     * tidak bocor mengubah tampilan di luar area preview.
     */
    private static function scopeCss(string $css, string $scope): string
    {
        return preg_replace_callback('/(^|\})\s*([^{]+)\{/m', function ($m) use ($scope) {
            $selectors = array_map(function ($s) use ($scope) {
                $s = trim($s);
                if ($s === '' || str_starts_with($s, '@')) {
                    return $s;
                }
                return $scope . ' ' . $s;
            }, explode(',', $m[2]));

            return $m[1] . ' ' . implode(', ', $selectors) . ' {';
        }, $css);
    }
}