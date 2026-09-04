<#
.SYNOPSIS
    Genera un nuevo módulo o widget de Zabbix a partir de las plantillas oficiales.

.DESCRIPTION
    Este script automatiza la creación de la estructura de carpetas, manifiesto,
    clases PHP, vistas y assets para Zabbix 7.0 LTS / 7.4.

.PARAMETER Type
    Tipo de módulo a crear: 'module' (frontend) o 'widget' (dashboard).

.PARAMETER Id
    Identificador único del módulo (ej. 'lm_alerta_banner'). Debe usar minúsculas y guiones bajos.

.PARAMETER Name
    Nombre descriptivo visible en Zabbix (ej. 'Alerta Banner').

.PARAMETER Namespace
    Espacio de nombres PHP (formato PascalCase, ej. 'LmAlertaBanner'). Si se omite, se infiere del Id.

.PARAMETER Author
    Nombre del autor. Por defecto 'Leandro Monasterio'.

.PARAMETER Description
    Descripción de la función del módulo.

.EXAMPLE
    .\tools\New-ZabbixModule.ps1 -Type module -Id lm_notificaciones -Name "Notificaciones Internas"
    .\tools\New-ZabbixModule.ps1 -Type widget -Id lm_reloj_operativo -Name "Reloj Operativo"
#>

[CmdletBinding()]
param(
    [Parameter(Mandatory = $true)]
    [ValidateSet('module', 'widget')]
    [string]$Type,

    [Parameter(Mandatory = $true)]
    [string]$Id,

    [Parameter(Mandatory = $true)]
    [string]$Name,

    [Parameter(Mandatory = $false)]
    [string]$Namespace,

    [Parameter(Mandatory = $false)]
    [string]$Author = "Leandro Monasterio",

    [Parameter(Mandatory = $false)]
    [string]$Description = "Módulo personalizado para Zabbix Frontend."
)

$ErrorActionPreference = "Stop"

$WorkspaceRoot = (Get-Item $PSScriptRoot).Parent.FullName
$ModulesDir = Join-Path $WorkspaceRoot "modules"
$TemplatesDir = Join-Path $WorkspaceRoot "templates"

# Normalizar ID
$Id = $Id.ToLower().Trim()
if ($Id -notmatch '^[a-z0-9_]+$') {
    Write-Error "El ID solo puede contener letras minúsculas, números y guiones bajos (ej: lm_mi_modulo)."
    return
}

# Inferir Namespace si no se proporcionó
if ([string]::IsNullOrWhiteSpace($Namespace)) {
    $parts = $Id -split '_'
    $Namespace = ($parts | ForEach-Object { (Get-Culture).TextInfo.ToTitleCase($_) }) -join ''
}

# Definir variables derivadas
$ActionName = ($Id -replace '_', '.') + ".view"
$JsClass = "Widget" + $Namespace

# Determinar plantilla
$SourceTemplate = if ($Type -eq 'widget') {
    Join-Path $TemplatesDir "dashboard-widget"
} else {
    Join-Path $TemplatesDir "frontend-module"
}

$TargetModuleDir = Join-Path $ModulesDir $Id

if (Test-Path $TargetModuleDir) {
    Write-Error "El módulo ya existe en la ruta: $TargetModuleDir"
    return
}

Write-Host "`n🚀 Creando nuevo $Type para Zabbix: '$Name' (ID: $Id)..." -ForegroundColor Cyan

# Copiar plantilla
Copy-Item -Path $SourceTemplate -Destination $TargetModuleDir -Recurse

# Reemplazar marcadores en todos los archivos de texto
$FilesToProcess = Get-ChildItem -Path $TargetModuleDir -Recurse -File

foreach ($file in $FilesToProcess) {
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    
    $content = $content.Replace('{{MODULE_ID}}', $Id)
    $content = $content.Replace('{{MODULE_NAME}}', $Name)
    $content = $content.Replace('{{MODULE_NAMESPACE}}', $Namespace)
    $content = $content.Replace('{{MODULE_AUTHOR}}', $Author)
    $content = $content.Replace('{{MODULE_DESCRIPTION}}', $Description)
    $content = $content.Replace('{{ACTION_NAME}}', $ActionName)
    $content = $content.Replace('{{JS_CLASS}}', $JsClass)
    
    Set-Content -Path $file.FullName -Value $content -Encoding UTF8 -NoNewline
}

Write-Host "✅ Módulo creado con éxito en:" -ForegroundColor Green
Write-Host "   $TargetModuleDir`n" -ForegroundColor Yellow
Write-Host "📋 Datos del módulo:"
Write-Host "   - ID:        $Id"
Write-Host "   - Nombre:    $Name"
Write-Host "   - Namespace: Modules\$Namespace"
Write-Host "   - Tipo:      $Type"
Write-Host "   - Acción:    $ActionName`n"
Write-Host "💡 Siguientes pasos:" -ForegroundColor Cyan
Write-Host "   1. Edita el código dentro de: modules/$Id"
Write-Host "   2. Empaquétalo con: pwsh tools/Package-Module.ps1 -ModuleId $Id"
