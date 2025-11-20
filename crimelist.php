<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reports</title>
</head>
<body>

<h2>List of Crime Reports</h2>

<div id="crime-list">
    <!-- Crime reports will be displayed here -->
</div>

<script>
// Function to fetch crime data from crimelist.php
fetch('crimelist.php')
    .then(response => response.json())
    .then(data => {
        console.log(data);  // Display the fetched data

        // Loop through the data and display the crime reports on the page
        const crimeListDiv = document.getElementById('crime-list');
        data.forEach(crime => {
            const crimeElement = document.createElement('div');
            crimeElement.innerHTML = `
                <h3>${crime.label}</h3>
                <p>Location: Latitude - ${crime.lat}, Longitude - ${crime.long}</p>
            `;
            crimeListDiv.appendChild(crimeElement);  // Append each crime report
        });
    })
    .catch(error => console.error('Error fetching crime data:', error));
</script>

</body>
</html>
