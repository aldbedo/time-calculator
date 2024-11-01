<?php
    // Check if the file parameter is set
    if (isset($_GET['file'])) {
        $file = basename($_GET['file']); // Get the file name from the qeury parameter
        $filePath = 'tmp/output/' . $file; // Path to the output file

        // Debugging output
        echo "Requested file: " . htmlspecialchars($file) . "<br>";
        echo "Full path: " . htmlspecialchars($filePath) . "<br>";
        
        // Check if the file exists
        if (file_exists($filePath)) {
            // Set headers to initiate a download
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Read the file and send it to the output buffer
            readfile($filePath);
            exit; // Exit the script after sending the file
        } else {
            echo "Error: File does not exist";
        }
    } else {
        echo "Error: No file specified.";
    }
?>
