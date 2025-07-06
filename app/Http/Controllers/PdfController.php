<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    /**
     * Generate and stream PDF of project share page
     */
    public function downloadProjectPdf(Project $project)
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(404);
        }

        // Ensure the project is shared
        if (!$project->share) {
            abort(400, 'Project must be shared to generate PDF');
        }

        try {
            // Generate PDF from dedicated template
            $html = view('projects.pdf', compact('project'))->render();
            
            $pdf = Browsershot::html($html)
                ->setNodeBinary(env('NODE_BINARY_PATH', 'C:\Users\Joy\.config\herd\bin\nvm\v23.11.0\node.exe'))
                ->setNpmBinary(env('NPM_BINARY_PATH', 'C:\Users\Joy\.config\herd\bin\nvm\v23.11.0\npm.cmd'))
                ->setChromePath(env('CHROME_PATH', 'C:\Users\Joy\.cache\puppeteer\chrome\win64-138.0.7204.92\chrome-win64\chrome.exe'))
                ->format('A4')
                ->margins(20, 20, 20, 20)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->delay(1000)
                ->windowSize(1240, 1754)
                ->displayHeaderFooter()
                ->showBrowserHeaderAndFooter()
                ->headerHtml('')
                ->hideHeader()
                ->footerHtml('
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        .footer-content { 
                            width: 100%;
                            font-family: "Times New Roman", serif;
                            font-size: 10pt;
                            padding: 10px 20px 0;
                            border-top: 1px solid #ccc;
                            text-align: center;
                            color: #666;
                        }
                    </style>
                    <div class="footer-content">
                        ' . htmlspecialchars($project->user->handle ?: $project->user->username) . ' | ' . htmlspecialchars($project->name) . ' | ' . now()->format('F j, Y') . '
                    </div>
                ')
                ->deviceScaleFactor(1)
                ->landscape(false)
                ->pdf();

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $project->name . '-buildbook.pdf"');

        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'PDF generation failed'], 500);
        }
    }
} 