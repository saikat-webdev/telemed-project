document.addEventListener('DOMContentLoaded', function() {
    const clockElement = document.getElementById('liveClock');

    if (clockElement) {
        function updateClock() {
            const now = new Date();
            
            // Format options for a very professional look
            const options = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            };
            
            let timeString = now.toLocaleTimeString('en-US', options);
            
            // Force uppercase for AM/PM for a cleaner look
            clockElement.textContent = timeString.toUpperCase();
        }

        setInterval(updateClock, 1000);
        updateClock(); 
    }
});