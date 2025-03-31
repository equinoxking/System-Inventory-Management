Set WshShell = CreateObject("WScript.Shell")
WshShell.Run """C:\xampp\php\php.exe"" ""C:\xampp\htdocs\Laravel\SMS\Inventory\artisan"" schedule:run", 0, True
