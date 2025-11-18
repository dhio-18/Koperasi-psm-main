# PowerShell script untuk menjalankan Laravel schedule:run
# Gunakan ini di Windows Task Scheduler

# Set working directory
Set-Location "C:\Koperasi-psm-main"

# Run schedule
php artisan schedule:run

# Log output (optional)
# "Schedule run at $(Get-Date)" | Out-File -Append -FilePath "C:\Koperasi-psm-main\storage\logs\schedule.log"
