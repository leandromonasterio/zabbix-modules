<#
.SYNOPSIS
    Valida y empaqueta un módulo de Zabbix en un archivo .zip listo para su instalación.

.DESCRIPTION
    Verifica que el archivo manifest.json cumpla con las especificaciones de Zabbix 7.0/7.4
    y genera un paquete comprimido en el directorio de salida.

.PARAMETER ModuleId
    Nombre de la carpeta del módulo dentro de modules/ (ej: 'lm_quick_links').

.PARAMETER OutputDir
    Directorio donde se depositará el archivo .zip. Por defecto 'dist/'.

.EXAMPLE
    .\tools\Package-Module.ps1 -ModuleId lm_quick_links
#>

[CmdletBinding()]
param(
    [Parameter(Mandatory = $true)]
    [string]$ModuleId,

    [Parameter(Mandatory = $false)]
    [string]$OutputDir = "dist"
)

$ErrorActionPreference = "Stop"

$WorkspaceRoot = (Get-Item $PSScriptRoot).Parent.FullName
$ModulePath = Join-Path $WorkspaceRoot "modules\$ModuleId"
$ManifestPath = Join-Path $ModulePath "manifest.json"

if (-not (Test-Path $ModulePath)) {
    Write-Error "No se encontró el módulo '$ModuleId' en: $ModulePath"
    return
}

if (-not (Test-Path $ManifestPath)) {
    Write-Error "El módulo '$ModuleId' no contiene el archivo obligatorio 'manifest.json'."
    return
}

Write-Host "`n🔍 Validando manifiesto de '$ModuleId'..." -ForegroundColor Cyan

# Validar JSON
try {
    $manifestContent = Get-Content -Path $ManifestPath -Raw -Encoding UTF8
    $manifest = $manifestContent | ConvertFrom-Json
} catch {
    Write-Error "Error de sintaxis en manifest.json: $_"
    return
}

# Comprobaciones de campos obligatorios
$requiredFields = @('manifest_version', 'id', 'name', 'namespace', 'version')
foreach ($field in $requiredFields) {
    if (-not $manifest.PSobject.Properties[$field] -or [string]::IsNullOrWhiteSpace($manifest.$field)) {
        Write-Error "El campo '$field' es obligatorio en manifest.json y no está definido."
        return
    }
}

if ($manifest.manifest_version -ne 2.0) {
    Write-Warning "Atención: manifest_version es '$($manifest.manifest_version)'. Zabbix 7.0/7.4 requiere '2.0'."
}

Write-Host "✅ Manifiesto válido (v$($manifest.version) - ID: $($manifest.id))" -ForegroundColor Green

# Preparar directorio de destino
$DistPath = if ([System.IO.Path]::IsPathRooted($OutputDir)) {
    $OutputDir
} else {
    Join-Path $WorkspaceRoot $OutputDir
}

if (-not (Test-Path $DistPath)) {
    New-Item -Path $DistPath -ItemType Directory -Force | Out-Null
}

$ZipFileName = "$($manifest.id)-v$($manifest.version).zip"
$ZipFilePath = Join-Path $DistPath $ZipFileName

if (Test-Path $ZipFilePath) {
    Remove-Item -Path $ZipFilePath -Force
}

Write-Host "📦 Empaquetando módulo en: $ZipFilePath..." -ForegroundColor Cyan

# Carpeta temporal para empaquetar con el nombre de carpeta adecuado
$TempDir = Join-Path ([System.IO.Path]::GetTempPath()) ("zbx_pkg_" + [System.Guid]::NewGuid().ToString('N'))
$TempModuleDir = Join-Path $TempDir $manifest.id

try {
    New-Item -Path $TempModuleDir -ItemType Directory -Force | Out-Null
    Copy-Item -Path "$ModulePath\*" -Destination $TempModuleDir -Recurse -Exclude "Thumbs.db", ".DS_Store", "*.log", "*.bak"

    Compress-Archive -Path $TempModuleDir -DestinationPath $ZipFilePath -CompressionLevel Optimal

    $fileInfo = Get-Item $ZipFilePath
    $sizeKb = [Math]::Round($fileInfo.Length / 1KB, 2)
    $hash = (Get-FileHash -Path $ZipFilePath -Algorithm SHA256).Hash

    Write-Host "`n🎉 ¡Paquete generado con éxito!" -ForegroundColor Green
    Write-Host "   - Archivo:  $ZipFileName ($sizeKb KB)"
    Write-Host "   - Ruta:     $ZipFilePath"
    Write-Host "   - SHA-256:  $hash" -ForegroundColor DarkGray
    Write-Host "`n💡 Para instalarlo en tu servidor Zabbix:" -ForegroundColor Yellow
    Write-Host "   unzip $ZipFileName -d /usr/share/zabbix/modules/"
    Write-Host "   (luego ve a Administración -> Módulos -> Escanear directorio)`n"
}
finally {
    if (Test-Path $TempDir) {
        Remove-Item -Path $TempDir -Recurse -Force -ErrorAction SilentlyContinue
    }
}
