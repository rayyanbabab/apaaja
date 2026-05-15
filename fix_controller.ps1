$filePath = 'app\Http\Controllers\ReportsController.php'
$lines = Get-Content $filePath -Encoding UTF8

$linesToDelete = @(413) + (415..543) + (648..773)

$result = New-Object System.Collections.Generic.List[string]
for ($i = 0; $i -lt $lines.Count; $i++) {
    $lineNum = $i + 1
    if ($lineNum -notin $linesToDelete) {
        $result.Add($lines[$i])
    }
}

$result | Set-Content $filePath -Encoding UTF8
Write-Host "Done. Lines remaining: $($result.Count)"
