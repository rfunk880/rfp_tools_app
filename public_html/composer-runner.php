<?php

// This script will attempt to run 'composer dump-autoload'

// Change to the project's root directory
// This path assumes the script is placed in the public_html folder.
chdir(__DIR__); 

echo "<pre>"; // Use <pre> for cleaner output

// Check if shell_exec is available
if (function_exists('shell_exec')) {
    echo "Attempting to run 'composer dump-autoload'...\n\n";
    
    // Execute the command
    $output = shell_exec('composer dump-autoload 2>&1');
    
    // Display the output
    if ($output !== null) {
        echo "Output:\n";
        echo htmlspecialchars($output);
    } else {
        echo "Command executed, but there was no output. This might be normal.";
    }
    
    echo "\n\nProcess complete. \n\nIMPORTANT: Please delete this file (composer-runner.php) from your server now.";

} else {
    echo "Error: The 'shell_exec' function is disabled on this server. Unable to run Composer command.";
}

echo "</pre>";

?>