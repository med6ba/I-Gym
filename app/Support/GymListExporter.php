<?php

namespace App\Support;

use App\Models\Gym;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class GymListExporter
{
    /**
     * @param  Collection<int, Gym>  $gyms
     */
    public function toExcel(Collection $gyms): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('The Zip PHP extension is required to export Excel files.');
        }

        $path = tempnam(sys_get_temp_dir(), 'igym-gyms-');

        if ($path === false) {
            throw new RuntimeException('Unable to create a temporary export file.');
        }

        $zip = new ZipArchive;

        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            @unlink($path);

            throw new RuntimeException('Unable to create the Excel export.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->packageRelationshipsXml());
        $zip->addFromString('docProps/app.xml', $this->appPropertiesXml());
        $zip->addFromString('docProps/core.xml', $this->corePropertiesXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationshipsXml());
        $zip->addFromString('xl/styles.xml', $this->stylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheetXml($gyms));
        $zip->close();

        $contents = file_get_contents($path);
        @unlink($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read the Excel export.');
        }

        return $contents;
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     */
    public function toPdf(Collection $gyms): string
    {
        return $this->buildPdf($this->pdfPages($gyms));
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     * @return Collection<int, array<int, string|int>>
     */
    private function rows(Collection $gyms): Collection
    {
        return $gyms->map(fn (Gym $gym): array => [
            $gym->name,
            $gym->primaryAdmin?->name ?? '-',
            $gym->primaryAdmin?->email ?? '-',
            $this->headline($gym->subscription_plan),
            $this->headline($gym->status),
            $gym->users_count,
            $gym->members_count,
            $gym->coaches_count,
            $gym->city ?: '-',
            $gym->phone ?: '-',
            $this->date($gym->subscription_started_at),
            $this->date($gym->subscription_ends_at),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function headings(): array
    {
        return [
            'Gym Name',
            'Admin Name',
            'Admin Email',
            'Plan',
            'Status',
            'Users',
            'Members',
            'Coaches',
            'City',
            'Phone',
            'Started At',
            'Ends At',
        ];
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     * @return array<string, int>
     */
    private function summary(Collection $gyms): array
    {
        return [
            'total' => $gyms->count(),
            'active' => $gyms->where('status', 'active')->count(),
            'trial' => $gyms->where('status', 'trial')->count(),
            'expired' => $gyms->where('status', 'expired')->count(),
            'suspended' => $gyms->where('status', 'suspended')->count(),
        ];
    }

    private function date(mixed $value): string
    {
        if (! $value) {
            return '-';
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return (string) $value;
    }

    private function headline(?string $value): string
    {
        return Str::headline($value ?: '-');
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     */
    private function worksheetXml(Collection $gyms): string
    {
        $dataRows = $this->rows($gyms)->values();
        $summary = $this->summary($gyms);
        $lastRow = $dataRows->isEmpty() ? 7 : 6 + $dataRows->count();
        $mergeRefs = ['A1:L1', 'A2:L2'];

        $sheetRows = [
            $this->excelRow(1, [['I-Gym Gyms Report', 1]], 30),
            $this->excelRow(2, [['Generated '.now()->format('Y-m-d H:i').' for the super admin workspace', 2]], 22),
            $this->excelRow(4, [
                ['Total Gyms', 3],
                [$summary['total'], 4],
                ['Active', 3],
                [$summary['active'], 4],
                ['Trial', 3],
                [$summary['trial'], 4],
                ['Expired', 3],
                [$summary['expired'], 4],
                ['Suspended', 3],
                [$summary['suspended'], 4],
                ['Generated', 3],
                [now()->format('Y-m-d'), 4],
            ], 24),
            $this->excelRow(6, collect($this->headings())->map(fn (string $heading): array => [$heading, 5])->all(), 24),
        ];

        if ($dataRows->isEmpty()) {
            $sheetRows[] = $this->excelRow(7, [['No gyms found', 6]], 28);
            $mergeRefs[] = 'A7:L7';
        } else {
            foreach ($dataRows as $index => $row) {
                $sheetRows[] = $this->excelRow(
                    $index + 7,
                    collect($row)->map(fn (string|int $value): array => [$value, 6])->all(),
                    22
                );
            }
        }

        $mergeCells = collect($mergeRefs)
            ->map(fn (string $ref): string => '<mergeCell ref="'.$ref.'"/>')
            ->implode('');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<dimension ref="A1:L'.$lastRow.'"/>'
            .'<sheetViews><sheetView showGridLines="0" workbookViewId="0"><pane ySplit="6" topLeftCell="A7" activePane="bottomLeft" state="frozen"/><selection pane="bottomLeft" activeCell="A7" sqref="A7"/></sheetView></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="18"/>'
            .'<cols>'
            .'<col min="1" max="1" width="28" customWidth="1"/>'
            .'<col min="2" max="3" width="26" customWidth="1"/>'
            .'<col min="4" max="8" width="13" customWidth="1"/>'
            .'<col min="9" max="10" width="20" customWidth="1"/>'
            .'<col min="11" max="12" width="14" customWidth="1"/>'
            .'</cols>'
            .'<sheetData>'.implode('', $sheetRows).'</sheetData>'
            .'<mergeCells count="'.count($mergeRefs).'">'.$mergeCells.'</mergeCells>'
            .'<autoFilter ref="A6:L'.$lastRow.'"/>'
            .'<pageMargins left="0.4" right="0.4" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>'
            .'<pageSetup orientation="landscape"/>'
            .'</worksheet>';
    }

    /**
     * @param  array<int, array{0: string|int, 1?: int}>  $cells
     */
    private function excelRow(int $rowNumber, array $cells, int $height): string
    {
        $cellXml = collect($cells)->map(function (array $cell, int $column) use ($rowNumber): string {
            $style = $cell[1] ?? 0;
            $styleAttribute = $style > 0 ? ' s="'.$style.'"' : '';

            return '<c r="'.$this->columnName($column + 1).$rowNumber.'" t="inlineStr"'.$styleAttribute.'><is><t>'.$this->xml($cell[0]).'</t></is></c>';
        })->implode('');

        return '<row r="'.$rowNumber.'" ht="'.$height.'" customHeight="1">'.$cellXml.'</row>';
    }

    private function columnName(int $number): string
    {
        $name = '';

        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)).$name;
            $number = intdiv($number, 26);
        }

        return $name;
    }

    private function xml(string|int $value): string
    {
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', (string) $value) ?? '';

        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            .'<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'</Types>';
    }

    private function packageRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            .'<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            .'</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Gyms Report" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private function workbookRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="5">'
            .'<font><sz val="11"/><color theme="1"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="20"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'<font><sz val="11"/><color rgb="FFE2E8F0"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="10"/><color rgb="FF0F172A"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'</fonts>'
            .'<fills count="6">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF0F172A"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFFFF7ED"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF59E0B"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF8FAFC"/><bgColor indexed="64"/></patternFill></fill>'
            .'</fills>'
            .'<borders count="2">'
            .'<border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border><left style="thin"><color rgb="FFE2E8F0"/></left><right style="thin"><color rgb="FFE2E8F0"/></right><top style="thin"><color rgb="FFE2E8F0"/></top><bottom style="thin"><color rgb="FFE2E8F0"/></bottom><diagonal/></border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="7">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="2" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="4" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="center" wrapText="1"/></xf>'
            .'</cellXfs>'
            .'</styleSheet>';
    }

    private function appPropertiesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            .'<Application>I-Gym</Application>'
            .'</Properties>';
    }

    private function corePropertiesXml(): string
    {
        $timestamp = now()->toIso8601String();

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            .'<dc:title>Gyms Export</dc:title>'
            .'<dc:creator>I-Gym</dc:creator>'
            .'<cp:lastModifiedBy>I-Gym</cp:lastModifiedBy>'
            .'<dcterms:created xsi:type="dcterms:W3CDTF">'.$timestamp.'</dcterms:created>'
            .'<dcterms:modified xsi:type="dcterms:W3CDTF">'.$timestamp.'</dcterms:modified>'
            .'</cp:coreProperties>';
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     * @return array<int, string>
     */
    private function pdfPages(Collection $gyms): array
    {
        $chunks = $gyms->values()->isEmpty()
            ? collect([collect()])
            : $gyms->values()->chunk(9)->values();

        return $chunks->map(function (Collection $chunk, int $index) use ($chunks, $gyms): string {
            $page = $index + 1;
            $pages = $chunks->count();
            $tableTop = $page === 1 ? 612 : 670;

            return $this->pdfHeader($page, $pages)
                .($page === 1 ? $this->pdfSummary($gyms) : '')
                .$this->pdfTable($chunk, $tableTop)
                .$this->pdfFooter($page, $pages);
        })->all();
    }

    private function pdfHeader(int $page, int $pages): string
    {
        return $this->pdfRect(0, 720, 612, 72, '0.059 0.090 0.165')
            .$this->pdfRect(36, 746, 12, 12, '0.961 0.620 0.043')
            .$this->pdfTextAt('I-Gym Gyms Report', 54, 754, 20, 'F2', '1 1 1')
            .$this->pdfTextAt('Generated '.now()->format('Y-m-d H:i').' | Page '.$page.' of '.$pages, 54, 737, 9, 'F1', '0.890 0.937 1')
            .$this->pdfTextAt('Super admin export', 456, 754, 10, 'F2', '0.961 0.620 0.043');
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     */
    private function pdfSummary(Collection $gyms): string
    {
        $summary = $this->summary($gyms);
        $cards = [
            ['Total gyms', $summary['total']],
            ['Active', $summary['active']],
            ['Trial', $summary['trial']],
            ['Expired', $summary['expired']],
            ['Suspended', $summary['suspended']],
        ];
        $content = '';
        $x = 36;

        foreach ($cards as [$label, $value]) {
            $content .= $this->pdfRect($x, 642, 96, 48, '0.973 0.980 0.988', '0.878 0.910 0.945')
                .$this->pdfRect($x, 686, 96, 4, '0.961 0.620 0.043')
                .$this->pdfTextAt((string) $value, $x + 10, 668, 17, 'F2', '0.059 0.090 0.165')
                .$this->pdfTextAt($label, $x + 10, 653, 8, 'F1', '0.392 0.455 0.545');
            $x += 108;
        }

        return $content;
    }

    /**
     * @param  Collection<int, Gym>  $gyms
     */
    private function pdfTable(Collection $gyms, int $top): string
    {
        $columns = [
            ['Gym', 36, 128],
            ['Admin', 164, 126],
            ['Plan', 290, 58],
            ['Status', 348, 74],
            ['Users', 422, 50],
            ['Ends', 472, 104],
        ];

        $content = $this->pdfRect(36, $top - 26, 540, 26, '0.100 0.116 0.168');

        foreach ($columns as [$label, $x]) {
            $content .= $this->pdfTextAt($label, $x + 8, $top - 17, 8.5, 'F2', '1 1 1');
        }

        if ($gyms->isEmpty()) {
            return $content
                .$this->pdfRect(36, $top - 76, 540, 50, '0.973 0.980 0.988', '0.878 0.910 0.945')
                .$this->pdfTextAt('No gyms found', 52, $top - 56, 10, 'F1', '0.392 0.455 0.545');
        }

        $rowTop = $top - 26;

        foreach ($gyms->values() as $index => $gym) {
            $rowTop -= 46;
            $fill = $index % 2 === 0 ? '1 1 1' : '0.973 0.980 0.988';
            [$badgeFill, $badgeStroke, $badgeText] = $this->statusColors($gym->status);

            $content .= $this->pdfRect(36, $rowTop, 540, 46, $fill, '0.878 0.910 0.945')
                .$this->pdfTextAt($this->fit($gym->name, 26), 44, $rowTop + 28, 8.5, 'F2', '0.059 0.090 0.165')
                .$this->pdfTextAt($this->fit($gym->city ?: '-', 28), 44, $rowTop + 15, 7.5, 'F1', '0.392 0.455 0.545')
                .$this->pdfTextAt($this->fit($gym->primaryAdmin?->name ?? '-', 25), 172, $rowTop + 28, 8.5, 'F2', '0.059 0.090 0.165')
                .$this->pdfTextAt($this->fit($gym->primaryAdmin?->email ?? '-', 28), 172, $rowTop + 15, 7.5, 'F1', '0.392 0.455 0.545')
                .$this->pdfTextAt($this->fit($this->headline($gym->subscription_plan), 10), 298, $rowTop + 22, 8.5, 'F1', '0.059 0.090 0.165')
                .$this->pdfRect(356, $rowTop + 15, 54, 17, $badgeFill, $badgeStroke)
                .$this->pdfTextAt($this->fit($this->headline($gym->status), 9), 362, $rowTop + 20, 7.5, 'F2', $badgeText)
                .$this->pdfTextAt((string) $gym->users_count, 430, $rowTop + 27, 9, 'F2', '0.059 0.090 0.165')
                .$this->pdfTextAt($gym->members_count.'M '.$gym->coaches_count.'C', 430, $rowTop + 15, 7.5, 'F1', '0.392 0.455 0.545')
                .$this->pdfTextAt($this->date($gym->subscription_ends_at), 480, $rowTop + 22, 8.5, 'F1', '0.059 0.090 0.165');
        }

        return $content;
    }

    private function pdfFooter(int $page, int $pages): string
    {
        return $this->pdfLine(36, 44, 576, 44, '0.878 0.910 0.945')
            .$this->pdfTextAt('I-Gym platform export', 36, 28, 8, 'F1', '0.392 0.455 0.545')
            .$this->pdfTextAt('Page '.$page.' / '.$pages, 526, 28, 8, 'F1', '0.392 0.455 0.545');
    }

    /**
     * @param  array<int, string>  $pageContents
     */
    private function buildPdf(array $pageContents): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
        ];
        $pageObjects = [];

        foreach ($pageContents as $content) {
            $contentObject = count($objects) + 1;
            $pageObject = $contentObject + 1;

            $objects[$contentObject] = '<< /Length '.strlen($content)." >>\nstream\n".$content."\nendstream";
            $objects[$pageObject] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents '.$contentObject.' 0 R >>';
            $pageObjects[] = $pageObject.' 0 R';
        }

        $objects[2] = '<< /Type /Pages /Kids ['.implode(' ', $pageObjects).'] /Count '.count($pageObjects).' >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        for ($number = 1; $number <= count($objects); $number++) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number." 0 obj\n".$objects[$number]."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($number = 1; $number <= count($objects); $number++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$number]);
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n".$xrefOffset."\n%%EOF";

        return $pdf;
    }

    private function pdfRect(float|int $x, float|int $y, float|int $width, float|int $height, string $fill, ?string $stroke = null): string
    {
        $draw = $stroke ? 'B' : 'f';
        $strokeCommand = $stroke ? $stroke." RG\n0.6 w\n" : '';

        return "q\n".$fill." rg\n".$strokeCommand.$this->n($x).' '.$this->n($y).' '.$this->n($width).' '.$this->n($height).' re '.$draw."\nQ\n";
    }

    private function pdfLine(float|int $x1, float|int $y1, float|int $x2, float|int $y2, string $stroke): string
    {
        return "q\n".$stroke." RG\n0.6 w\n".$this->n($x1).' '.$this->n($y1).' m '.$this->n($x2).' '.$this->n($y2)." l S\nQ\n";
    }

    private function pdfTextAt(string $text, float|int $x, float|int $y, float|int $size, string $font, string $color): string
    {
        return "BT\n/".$font.' '.$this->n($size)." Tf\n".$color." rg\n".$this->n($x).' '.$this->n($y)." Td\n(".$this->pdf($this->pdfText($text)).") Tj\nET\n";
    }

    /**
     * @return array<int, string>
     */
    private function statusColors(?string $status): array
    {
        return match ($status) {
            'active' => ['0.863 0.965 0.906', '0.518 0.824 0.631', '0.086 0.396 0.204'],
            'trial' => ['1 0.969 0.878', '0.961 0.620 0.043', '0.573 0.318 0.008'],
            'expired' => ['1 0.894 0.894', '0.988 0.443 0.443', '0.608 0.106 0.106'],
            'suspended' => ['0.914 0.929 0.949', '0.580 0.639 0.722', '0.200 0.255 0.333'],
            default => ['0.973 0.980 0.988', '0.878 0.910 0.945', '0.392 0.455 0.545'],
        };
    }

    private function fit(string $value, int $limit): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $this->pdfText($value)) ?? '');

        if ($value === '') {
            return '-';
        }

        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return mb_substr($value, 0, max(1, $limit - 3)).'...';
    }

    private function n(float|int $value): string
    {
        return rtrim(rtrim(number_format((float) $value, 3, '.', ''), '0'), '.');
    }

    private function pdfText(string $value): string
    {
        return Str::ascii($value);
    }

    private function pdf(string $value): string
    {
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value) ?? '';

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $value);
    }
}
