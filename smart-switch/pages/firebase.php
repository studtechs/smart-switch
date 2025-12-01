<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Load Viewer</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 1.5em;
            color: #34495e;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        thead th {
            background-color: #ecf0f1;
            font-weight: 600;
            color: #34495e;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            color: #fff;
            font-weight: 500;
            text-align: center;
            display: inline-block;
        }
        .status-on { background-color: #27ae60; }
        .status-off { background-color: #c0392b; }
        pre {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap; /* Ensures the text wraps */
            word-wrap: break-word;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Real-Time Load Status</h1>
        <div id="loads-container">
            <!-- Grouped device data will be populated by JavaScript -->
        </div>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-database-compat.js"></script>


    <script>
    // --- Firebase Configuration ---
    // IMPORTANT: Replace with your own Firebase project configuration
     // Your Firebase configuration
    // I have replaced your visible keys with placeholders for security.
    const firebaseConfig = {
      apiKey: "AIzaSyBQwgU8SOM9We4s7M4O2bfUYkjAt41iyis",
      authDomain: "smarthouse-34289.firebaseapp.com",
      databaseURL: "https://smarthouse-34289-default-rtdb.firebaseio.com",
      projectId: "smarthouse-34289",
      storageBucket: "smarthouse-34289.firebasestorage.app",
      messagingSenderId: "133121985695",
      appId: "1:133121985695:web:decf09640e936e07db26aa",
      measurementId: "G-EKVVWP9JG6"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const database = firebase.database();
    console.log("Firebase initialized.");
    </script>

    <script>
    async function fetchAndUpdateLoads() {
        const loadsContainer = document.getElementById('loads-container');
        try {
            // Add { cache: 'no-cache' } to prevent the browser from caching the response.
            // This ensures we always get the latest data from the server.
            const response = await fetch('get_loads_data.php', { cache: 'no-cache' });
            const result = await response.json();

            if (result.success) {
                // Clear existing rows
                // These objects will hold the data grouped by device_auth
                const firebaseData = {};
                const groupedLoads = {};

                // Populate with new data
                // Group loads by device_auth
                result.data.forEach(load => {
                    // Initialize group if it doesn't exist for both HTML and Firebase
                    if (!groupedLoads[load.device_auth]) {
                        groupedLoads[load.device_auth] = []; // For HTML rendering
                        firebaseData[load.device_auth] = {};
                    }
 
                    // Add to groups
                    groupedLoads[load.device_auth].push(load); // For HTML rendering
                    firebaseData[load.device_auth][load.loadname] = load.load_state; // For Firebase
                });
 
                // --- Render the Firebase data object as formatted JSON ---
                loadsContainer.innerHTML = `<pre><code>${JSON.stringify(firebaseData, null, 2)}</code></pre>`;

                // --- Send the grouped data to Firebase ---
                await setFirebaseData(firebaseData);

            } else {
                console.error('Failed to fetch data:', result.error);
                loadsContainer.innerHTML = '<p>Error loading data.</p>';
            }
        } catch (error) {
            console.error('An error occurred during fetch or update:', error);
            loadsContainer.innerHTML = '<p>Error connecting to the server.</p>';
        }
    }

    // Fetch data when the page loads, and then every 2 seconds
    document.addEventListener('DOMContentLoaded', fetchAndUpdateLoads);
    setInterval(fetchAndUpdateLoads, 10);

    /**
     * Writes the entire grouped data object to the Firebase Realtime Database.
     * This will overwrite any existing data at the 'loads/' path.
     * @param {object} data The data object to be sent to Firebase.
     */
    async function setFirebaseData(data) {
        try {
            // Set the data at the root 'loads' path in Firebase
            await database.ref('loads').set(data);
            console.log('Data successfully sent to Firebase:', data);
        } catch (error) {
            console.error('Firebase data could not be set.', error);
        }
    }
    </script>

</body>
</html>