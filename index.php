<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class='container'>
        <h1 class='h1-txt'>Time Calculator</h1>

        <!-- Form container to toggle visibility -->
        <div id="form-container">
            <form id="upload-form" enctype="multipart/form-data" class="upload-form">
                <label for="file-upload" class="custom-file-upload">Choose File</label>
                <input type="file" name="datafile" id="file-upload" accept=".csv" class="file-input">
                <span id="file-name">No file chosen</span>
                <button type="button" class="submit-button" onclick="uploadFile()">Upload</button>
            </form>
        </div>

        <!-- Output container to display results -->
        <div id="output-container" style="display: none;">
            <div id="output-text"></div>
            <button onclick="resetForm()">Upload Another File</button>
        </div>
    </div> 

    <script>
        document.getElementById("file-upload").addEventListener("change", function () {
            const fileName = this.files[0] ? this.files[0].name : "No file chosen";
            document.getElementById("file-name").textContent = fileName;
        });

        function uploadFile() {
            const formData = new FormData(document.getElementById("upload-form"));

            // Use Fetch API to send the file asynchronously to process.php
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("output-text").innerHTML = `<p>Error: ${data.error}</p>`;
                } else {
                    document.getElementById("output-text").innerHTML = `<p>${data.output}</p>`;
                    document.getElementById("output-text").innerHTML += `<a href="${data.downloadLink}" download>Download output.csv</a>`;
                }
                // Hide the form and show the output container
                document.getElementById("form-container").style.display = "none";
                document.getElementById("output-container").style.display = "block";
            })
            .catch(error => {
                document.getElementById("output-text").innerHTML = `<p>Error: ${error.message}</p>`;
            });
        }

        function resetForm() {
            document.getElementById("upload-form").reset();
            document.getElementById("file-name").textContent = "No file chosen";
            document.getElementById("form-container").style.display = "block";
            document.getElementById("output-container").style.display = "none";
        }
    </script>
</body>
</html>