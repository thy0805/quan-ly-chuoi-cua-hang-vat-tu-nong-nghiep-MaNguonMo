param([switch]$ConcurrencyOnly)

$ErrorActionPreference = 'Stop'
$taskRoot = Split-Path -Parent $PSScriptRoot
$phpPath = 'C:/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe'
$credentialsPath = Join-Path $taskRoot '.agent/private/c1/credentials.json'
if (-not (Test-Path -LiteralPath $credentialsPath) -or -not (Test-Path -LiteralPath $phpPath)) { throw 'Thiếu cấu hình test C1 private hoặc PHP Laragon.' }
$c = Get-Content -LiteralPath $credentialsPath -Raw | ConvertFrom-Json
if ($c.database -ne 'mnm_inventory_test_20261021' -or $c.runtime_user -ne 'mnm_c1_test_runtime') { throw 'Chỉ được chạy trên database test C1.' }
foreach ($directory in @('sessions', 'cache', 'views')) { New-Item -ItemType Directory -Path (Join-Path $taskRoot "storage/mnm/c1-test/$directory") -Force | Out-Null }
$environment = @{
    APP_ENV='inventory-testing'; APP_DEBUG='false'; MNM_INVENTORY_TEST='isolated-c1';
    DB_CONNECTION='mysql'; DB_HOST='127.0.0.1'; DB_PORT='3306'; DB_DATABASE=$c.database;
    DB_USERNAME=$c.runtime_user; DB_PASSWORD=$c.runtime_password;
    MNM_DEMO_ADMIN_PASSWORD=$c.demo_admin_password; MNM_DEMO_USER_PASSWORD=$c.demo_user_password
}
$saved = @{}
Push-Location -LiteralPath $taskRoot
try {
    foreach ($key in $environment.Keys) { $saved[$key] = [Environment]::GetEnvironmentVariable($key, 'Process'); [Environment]::SetEnvironmentVariable($key, $environment[$key], 'Process') }
    $entry = if ($ConcurrencyOnly) { 'tests/Support/purchases_concurrency.php' } else { 'vendor/phpunit/phpunit/phpunit' }
    $output = & $phpPath $entry 2>&1 | Out-String
    $exitCode = $LASTEXITCODE
    foreach ($secret in @($c.runtime_password, $c.migration_password, $c.demo_admin_password, $c.demo_user_password)) { if ($secret) { $output = $output.Replace($secret, '[REDACTED]') } }
    Write-Output $output
    if ($exitCode -ne 0 -or ($ConcurrencyOnly -and $output -notmatch '"status"\s*:\s*"PASS"') -or (-not $ConcurrencyOnly -and $output -notmatch '"result"\s*:\s*"passed"')) { throw 'Kiểm thử chưa đạt; xem đầu ra đã lọc.' }
} finally {
    foreach ($key in $saved.Keys) { [Environment]::SetEnvironmentVariable($key, $saved[$key], 'Process') }
    Pop-Location
}
