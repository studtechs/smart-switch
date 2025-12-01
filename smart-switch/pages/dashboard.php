<?php
$page_title = 'Dashboard'; // Set a specific title for this page
require_once 'header.php'; // Include the new header and navigation
include('../db/config.php');
?>

<style>
/* 🚀 Updated Styles to match the dark, glowing aesthetic */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    /* Updated: Dark space-like background */
    background: #0d122b; 
    min-height: 100vh;
    color: #f0f0f0; /* Light text color for dark background */
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px;
}

h1 {
    text-align: center;
    color: #4b88ff; /* Bright blue color */
    font-size: 1.8em;
    margin-bottom: 20px;
    /* Added: Subtle text shadow for a glow effect */
    text-shadow: 0 0 10px rgba(75, 136, 255, 0.5), 0 0 5px rgba(75, 136, 255, 0.3);
    padding: 10px;
}

/* Header and Logout Button */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px 0; /* Add some padding around the header */
}

.header h1 {
    margin: 0; /* Reset margin for h1 inside header */
}

.logout-button {
    /* Updated: Dark background, bright hover */
    background: #253360; /* Darker blue/purple */
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease, box-shadow 0.3s ease;
    /* Added: Subtle glow effect */
    box-shadow: 0 0 8px rgba(255, 69, 0, 0.4); 
}

.logout-button:hover {
    background: #ff4500; /* Orange-red on hover */
    box-shadow: 0 0 15px rgba(255, 69, 0, 0.8);
}

/* Loads Container */
.loads-container {
    display: grid;
    gap: 20px; /* Increased gap */
}

/* Device Group Styles */
.device-group {
    /* Updated: Dark card style with a blue glow/border */
    background: #1a2240; 
    border-radius: 16px; /* Slightly more rounded */
    box-shadow: 0 0 15px rgba(75, 136, 255, 0.3), 0 4px 15px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    border: 1px solid #4b88ff; /* Blue border for definition */
}

.device-header {
    /* Updated: Dark header with a blue accent */
    background: #232c4e;
    padding: 15px; /* Increased padding */
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.device-title {
    color: #fff;
    margin: 0;
    font-size: 1.1em;
    font-weight: 700;
}

.device-count {
    background: #4b88ff; /* Bright color accent */
    color: #0d122b;
    padding: 5px 12px;
    border-radius: 16px;
    font-size: 0.8em;
    font-weight: 700;
}

.device-loads {
    padding: 15px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px; /* Increased gap */
    background: #1a2240; /* Match device-group background */
}

.load-item {
    display: flex;
    justify-content: space-between;
    align-items: center; 
    /* Updated: Dark load item style */
    background: #253360; 
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #3c4c8d;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.load-item:hover {
    background: #3c4c8d; /* Lighter dark color on hover */
    border-color: #4b88ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(75, 136, 255, 0.4); /* Glow on hover */
}

.load-info {
    flex-grow: 1;
    min-width: 0;
}

.load-name {
    margin: 0 0 3px 0;
    font-size: 1em;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.load-details {
    margin: 0;
    font-size: 0.8em;
    color: #a0a0a0; /* Lighter grey for details */
}

.load-control {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.edit-load-button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #7d96ff; /* Default icon color */
    transition: color 0.2s ease;
    display: flex;
    align-items: center;
}

.edit-load-button:hover {
    color: #fff; /* White on hover */
    text-shadow: 0 0 5px #7d96ff;
}

.edit-load-button svg {
    width: 20px;
    height: 20px;
}


/* Switch Styles */
.switch-container {
    position: relative;
    display: inline-block;
    width: 48px; /* Slightly larger switch */
    height: 28px;
    cursor: pointer;
}

.switch-input {
    opacity: 0;
    width: 0;
    height: 0;
}

.switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #555; /* Darker off state */
    transition: 0.3s;
    border-radius: 28px;
    border: 1px solid #444;
}

.switch-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
}

.switch-input:checked + .switch-slider {
    /* Bright blue/purple gradient for ON state */
    background: linear-gradient(135deg, #4b88ff 0%, #764ba2 100%);
    border: 1px solid #4b88ff;
}

.switch-input:checked + .switch-slider:before {
    transform: translateX(20px);
}

/* No Loads */
.no-loads {
    text-align: center;
    padding: 40px 20px;
    background: #1a2240; /* Dark background */
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(75, 136, 255, 0.3), 0 4px 15px rgba(0, 0, 0, 0.3);
    border: 1px solid #4b88ff;
}

.no-loads h2 {
    color: #4b88ff;
    margin-bottom: 10px;
    font-size: 1.5em;
    text-shadow: 0 0 5px rgba(75, 136, 255, 0.5);
}

.no-loads p {
    color: #a0a0a0;
    font-size: 1em;
}

/* Responsive Design */
@media (max-width: 992px) {
    .device-loads {
        grid-template-columns: repeat(3, 1fr); /* 3 columns for medium screens */
    }
}

@media (max-width: 768px) {
    .device-loads {
        grid-template-columns: repeat(2, 1fr); /* 2 columns for tablets/small laptops */
    }
    
    h1 {
        font-size: 1.5em;
    }
}

@media (max-width: 480px) {
    .device-loads {
        grid-template-columns: 1fr; /* 1 column for mobile */
    }

    .container {
        padding: 10px;
    }

    h1 {
        font-size: 1.3em;
    }

    .load-item {
        padding: 15px;
    }
}

/* --- Notification System Update --- */

/* Notification colors and styles updated for the dark theme */
/* Note: The JS dynamic styles for positioning are kept, but the background gradients are defined here */
.notification-success {
    background: linear-gradient(135deg, #28a745, #1e7e34); /* Darker success green */
}

.notification-error {
    background: linear-gradient(135deg, #dc3545, #bd2130); /* Darker error red */
}

.notification-info {
    background: linear-gradient(135deg, #4b88ff, #3c6ed3); /* Match theme blue */
}

</style>
        <?php
        // Fetch all loads belonging to the logged-in user
        $userid = $_SESSION['userid'];
        $query = "SELECT l.loadid, l.loadname, l.old_name, l.device_auth, l.load_state, d.DeviceName 
                  FROM loads l
                  JOIN device d ON l.device_auth = d.device_auth
                  WHERE d.userid = ?
                  ORDER BY l.device_auth ASC, l.loadid ASC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Group loads by device_auth
            $groupedLoads = [];
            while ($row = $result->fetch_assoc()) {
                $deviceAuth = $row['device_auth'];
                if (!isset($groupedLoads[$deviceAuth])) {
                    $groupedLoads[$deviceAuth] = [];
                }
                $groupedLoads[$deviceAuth][] = $row;
            }
            
            // Display grouped loads
            echo "<div class='loads-container' id='loadsContainer'>";
            
            foreach ($groupedLoads as $deviceAuth => $loads) {
                // Use the actual device name from the first load in the group
                $deviceName = htmlspecialchars($loads[0]['DeviceName'] ?? $deviceAuth);
                $loadCount = count($loads);
                
                echo "<div class='device-group'>";
                echo "<div class='device-header'>";
                echo "<h2 class='device-title'>$deviceName</h2>";
                echo "<span class='device-count'>$loadCount " . ($loadCount == 1 ? 'Load' : 'Loads') . "</span>";
                echo "</div>";
                echo "<div class='device-loads'>";
                
                foreach ($loads as $row) {
                    $loadId = $row['loadid'];
                    $loadName = htmlspecialchars($row['loadname']);
                    $oldName = htmlspecialchars($row['old_name']); // Get the old_name
                    $loadState = $row['load_state'];
                    $isChecked = $loadState == 1;
                    $statusText = $loadState == 1 ? 'ON' : 'OFF';
                    $statusClass = $loadState == 1 ? 'status-on' : 'status-off';
                    
                    echo "<div class='load-item'>";
                    echo "<div class='load-info'>";
                    echo "<h3 class='load-name'>$oldName</h3>";
                    echo "</div>";
                    echo "<div class='load-control'>";
                    echo "<label class='switch-container'>";
                    echo "<input type='checkbox' class='switch-input' data-load-id='$loadId' data-load-name='$oldName' " . ($isChecked ? 'checked' : '') . ">";
                    echo "<span class='switch-slider'></span>";
                    echo "</label>";
                    // Add the new edit button linking to the edit page
                    echo "<a href='edit_load.php?id=$loadId' class='edit-load-button' title='Edit Load'><svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='3'></circle><path d='M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z'></path></svg></a>";
                    echo "</div>";
                    echo "</div>";
                }
                
                echo "</div>"; // Close device-loads
                echo "</div>"; // Close device-group
            }
            
            echo "</div>"; // Close loads-container
        } else {
            echo "<div class='no-loads'>";
            echo "<a href='add-device.php' style='text-decoration: none; color: inherit;'><h2>+ Add Device </h2><p></p></a>";
            echo "</div>";
        }
        
        $conn->close();
        ?>
    </div>
    
    <script>
    // Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications to prevent stacking
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;

    // Define SVG icons
    const icons = {
        success: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14 9 11"></polyline></svg>`,
        error: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`,
        info: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`
    };

    // Set inner HTML with icon and message
    notification.innerHTML = `
        <div class="notification-icon">${icons[type] || icons['info']}</div>
        <div class="notification-message">${message}</div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 18px;
        border-radius: 8px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.9em;
        z-index: 10000;
        animation: slideInRight 0.3s ease-out;
        max-width: 320px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 12px;
    `;

    // Set background color based on type
    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #28a745, #218838)';
    } else if (type === 'error') {
        notification.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #17a2b8, #138496)';
    }

    // Add to page
    document.body.appendChild(notification);

    // Style the icon and message elements
    notification.querySelector('.notification-icon').style.cssText = 'flex-shrink: 0; display: flex; align-items: center;';
    notification.querySelector('.notification-message').style.cssText = 'flex-grow: 1;';

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Use event delegation on the container
    function handleToggle(event) {
        const checkbox = event.target;
        // Ensure we only handle changes on our switch inputs
        if (!checkbox.classList.contains('switch-input')) {
            return;
        }

        const loadId = checkbox.dataset.loadId;
        const loadName = checkbox.dataset.loadName;
        const newState = checkbox.checked ? 1 : 0;

        // Disable the checkbox to prevent multiple clicks
        checkbox.disabled = true;
        showNotification(`Switching ${loadName}...`, 'info');

        // Prepare data for the POST request
        const formData = new URLSearchParams();
        formData.append('loadid', loadId);
        formData.append('state', newState);

        // Send the request to the secure API endpoint
        fetch('../api/update_switch.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // If the server returns an error (like 401, 403, 500), try to parse the JSON error message
                return response.json().then(err => { throw new Error(err.error || `HTTP error! Status: ${response.status}`); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Use 'success' for ON (green) and 'error' for OFF (red) to change modal color
                const notificationType = newState === 1 ? 'success' : 'error';
                showNotification(`${loadName} turned ${newState === 1 ? 'ON' : 'OFF'}`, notificationType);
            } else {
                throw new Error(data.error || 'Failed to toggle switch.');
            }
        })
        .catch(error => {
            console.error('Error toggling switch:', error);
            showNotification(`Error: ${error.message}`, 'error');
            // On failure, revert the checkbox to its previous state
            checkbox.checked = !checkbox.checked;
        })
        .finally(() => {
            // Re-enable the checkbox after the operation is complete
            checkbox.disabled = false;
        });
    }

    const loadsContainer = document.getElementById('loadsContainer');
    if (loadsContainer) {
        loadsContainer.addEventListener('change', handleToggle);
    }

    </script>

<iframe src="firebase.php" style="display:none;"></iframe>

<?php
require_once 'footer.php'; // Include the new footer
?>