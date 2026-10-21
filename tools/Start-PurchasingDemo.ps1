param([int]$Port = 8092)

$ErrorActionPreference = 'Stop'
$taskRoot = Split-Path -Parent $PSScriptRoot
$credentialsPath = Join-Path $taskRoot '.agent/private/c1/credentials.json'
$phpPath = 'C:/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe'
if (-not (Test-Path -LiteralPath $credentialsPath) -or -not (Test-Path -LiteralPath $phpPath)) { throw 'Thiếu cấu hình test C1 private hoặc PHP Laragon.' }
if ($Port -lt 1024 -or $Port -gt 65535) { throw 'Cổng demo phải từ 1024 đến 65535.' }
if (@(Get-NetTCPConnection -State Listen -LocalPort $Port -ErrorAction SilentlyContinue).Count) { throw 'Cổng đã được sử dụng; không khởi động server thứ hai.' }
$c = Get-Content -LiteralPath $credentialsPath -Raw | ConvertFrom-Json
if ($c.database -ne 'mnm_inventory_test_20261021' -or $c.runtime_user -ne 'mnm_c1_test_runtime') { throw 'Cấu hình không thuộc database test cô lập.' }
foreach ($directory in @('sessions', 'cache', 'views')) { New-Item -ItemType Directory -Path (Join-Path $taskRoot "storage/mnm/c1-test/$directory") -Force | Out-Null }
$environment = @{
    APP_ENV='inventory-testing'; APP_DEBUG='false'; MNM_INVENTORY_TEST='isolated-c1';
    DB_CONNECTION='mysql'; DB_HOST='127.0.0.1'; DB_PORT='3306'; DB_DATABASE=$c.database;
    DB_USERNAME=$c.runtime_user; DB_PASSWORD=$c.runtime_password
}
$saved = @{}
try {
    foreach ($key in $environment.Keys) { $saved[$key] = [Environment]::GetEnvironmentVariable($key, 'Process'); [Environment]::SetEnvironmentVariable($key, $environment[$key], 'Process') }
    $publicPath = Join-Path $taskRoot 'public'
    $routerPath = '../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php'
    Write-Output "Demo test: http://127.0.0.1:$Port/login — Ctrl+C để dừng; không migration/seed."
    Push-Location -LiteralPath $publicPath
    try { & $phpPath '-S' "127.0.0.1:$Port" $routerPath } finally { Pop-Location }
} finally {
    foreach ($key in $saved.Keys) { [Environment]::SetEnvironmentVariable($key, $saved[$key], 'Process') }
}
