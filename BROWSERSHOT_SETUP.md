# Browsershot Setup for BuildBook

This guide explains how to set up Browsershot for PDF generation in the BuildBook project.

## Prerequisites

- Laravel project with Livewire
- Herd for local development
- Node.js and npm (provided by Herd)
- Chromium browser

## Installation Steps

### 1. Install Required Packages

```bash
# Install Browsershot via Composer
composer require spatie/browsershot

# Install Puppeteer via npm
npm install --save puppeteer
```

### 2. Find Your Herd Paths

First, locate your Herd installation paths:

**Windows (PowerShell):**
```powershell
# Find Node.js path
Get-ChildItem "C:\Users\$env:USERNAME\.config\herd\bin\nvm" -Recurse -Name "node.exe" | Select-Object -First 1

# Find npm path  
Get-ChildItem "C:\Users\$env:USERNAME\.config\herd\bin\nvm" -Recurse -Name "npm.cmd" | Select-Object -First 1

# Find Chrome path (after Puppeteer installs it)
Get-ChildItem "C:\Users\$env:USERNAME\.cache\puppeteer" -Recurse -Name "chrome.exe" | Select-Object -First 1
```

**macOS/Linux:**
```bash
# Find Node.js path
find ~/Library/Application\ Support/Herd/config/nvm -name "node" -type f

# Find npm path
find ~/Library/Application\ Support/Herd/config/nvm -name "npm" -type f

# Find Chrome path
find ~/.cache/puppeteer -name "chrome" -type f
```

### 3. Configure Environment Variables

Add these variables to your `.env` file:

```env
# Browsershot Configuration
NODE_BINARY_PATH="C:\Users\YOUR_USERNAME\.config\herd\bin\nvm\v23.11.0\node.exe"
NPM_BINARY_PATH="C:\Users\YOUR_USERNAME\.config\herd\bin\nvm\v23.11.0\npm.cmd"
CHROME_PATH="C:\Users\YOUR_USERNAME\.cache\puppeteer\chrome\win64-138.0.7204.92\chrome-win64\chrome.exe"
```

**For macOS/Linux:**
```env
NODE_BINARY_PATH="/Users/YOUR_USERNAME/Library/Application Support/Herd/config/nvm/v18.18.2/bin/node"
NPM_BINARY_PATH="/Users/YOUR_USERNAME/Library/Application Support/Herd/config/nvm/v18.18.2/bin/npm"
CHROME_PATH="/Users/YOUR_USERNAME/.cache/puppeteer/chrome/mac-138.0.7204.92/Chromium.app/Contents/MacOS/Chromium"
```

### 4. Verify Installation

Test the PDF generation by visiting a project page and clicking the "Download PDF" button.

## Troubleshooting

### Common Issues

1. **Path Not Found Errors**
   - Verify paths exist and are correct
   - Use forward slashes in Windows paths
   - Check file permissions

2. **Chrome/Chromium Issues**
   - Ensure Puppeteer has downloaded Chrome
   - Run `npm install puppeteer` to trigger download
   - Check Chrome path in `.cache/puppeteer`

3. **Node.js/npm Issues**
   - Verify Herd is using the correct Node.js version
   - Check symlinks if using them
   - Ensure paths don't contain spaces

### Debugging

Add debugging to the PdfController:

```php
$pdf = Browsershot::html($html)
    ->setNodeBinary(env('NODE_BINARY_PATH'))
    ->setNpmBinary(env('NPM_BINARY_PATH'))
    ->setChromePath(env('CHROME_PATH'))
    ->setDebugging() // Add this line for debugging
    ->format('A4')
    // ... rest of configuration
```

### Error Handling

The PdfController includes comprehensive error handling:

```php
try {
    // PDF generation code
} catch (\Exception $e) {
    Log::error('PDF generation failed: ' . $e->getMessage());
    return response()->json(['error' => 'PDF generation failed'], 500);
}
```

## Production Deployment

For production, ensure:

1. **Environment Variables**: Set all required paths in production `.env`
2. **Chrome Installation**: Install Chromium on the server
3. **Permissions**: Ensure proper file permissions
4. **Memory**: Allocate sufficient memory for PDF generation

### Production Environment Variables

```env
NODE_BINARY_PATH="/usr/bin/node"
NPM_BINARY_PATH="/usr/bin/npm"
CHROME_PATH="/usr/bin/chromium-browser"
```

## Best Practices

1. **Path Validation**: Always validate paths exist before using them
2. **Error Logging**: Log all PDF generation errors
3. **Timeout Handling**: Set appropriate timeouts for large PDFs
4. **Memory Management**: Monitor memory usage during PDF generation
5. **Caching**: Consider caching generated PDFs for performance

## Testing

Run the PDF generation test:

```bash
php artisan test --filter="PdfGenerationTest"
```

This will verify that PDF generation works correctly with your configuration. 