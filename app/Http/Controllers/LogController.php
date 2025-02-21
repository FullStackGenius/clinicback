<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class LogController extends Controller
{
    public function readLog(Request $request)
    {
        // Get the latest log file
        if(isset($request->file) && !empty($request->file)){

            if($request->file == 'current'){
                $currentDate = Carbon::now()->format('Y-m-d');
                $file = 'logs/laravel-'.$currentDate.'.log';
                $logFile = storage_path($file);
            }else{
                $file = 'logs/laravel-'.$request->file.'.log';
                $logFile = storage_path($file);
            }
           

        }else{
            $logFile = storage_path('logs/laravel.log');
        }
        

        // Check if the file exists
        if (!File::exists($logFile)) {
            return view('logs', ['logs' => 'Log file not found']);
        }

        // Read the log file into an array of lines
        $logLines = file($logFile, FILE_IGNORE_NEW_LINES);

        // Reverse the array to show the newest logs first
        $logLines = array_reverse($logLines);

        // Convert the array to a single string with line breaks
        $formattedLogs = implode("<br>", array_map('e', $logLines));

        // Return the view with the logs
        return view('logs', ['logs' => $formattedLogs]);
    }

    public function showApiRoutes()
    {
        // Get all routes
        $routes = Route::getRoutes();

        // Filter API routes
        $apiRoutes = [];
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'api') === 0) {
                $apiRoutes[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName() ?? 'N/A',
                    'action' => $route->getActionName(),
                ];
            }
        }

        // Return the view with API routes
        return view('apilist.api-list', compact('apiRoutes'));
    }
}
