<?php

namespace App\Services;

class ExportService
{
    public static function exportToExcel($data, $headers, $filename = 'export.csv')
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, $headers, ';');
        
        foreach ($data as $row) {
            $rowData = [];
            foreach ($headers as $header) {
                $key = str_replace([' ', '(', ')'], '_', strtolower($header));
                $rowData[] = $row[$key] ?? '';
            }
            fputcsv($output, $rowData, ';');
        }
        
        fclose($output);
        exit;
    }

    public static function exportToPDF($html, $filename = 'export.pdf')
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo $html;
        exit;
    }

    public static function generatePDFContent($title, $data, $columns)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { text-align: center; color: #333; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background-color: #f0f0f0; font-weight: bold; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                .footer { margin-top: 30px; text-align: right; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <h1>' . $title . '</h1>
            <p>Généré le ' . date('d/m/Y H:i:s') . '</p>
            <table>
                <thead>
                    <tr>';
        
        foreach ($columns as $column) {
            $html .= '<th>' . $column . '</th>';
        }
        
        $html .= '
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach (array_keys($columns) as $key) {
                $html .= '<td>' . ($row[$key] ?? '') . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            <div class="footer">
                <p>Document officiel - Gestion du Magasin Public</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
