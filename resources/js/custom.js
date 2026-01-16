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





        //////Adding more medicine rows in prescription form//////
        const addBtn = document.getElementById('add-medicine-row');
        const tableBody = document.getElementById('medicine-list-body');

        if (addBtn && tableBody) {
            addBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="p-3"><input type="text" name="medicines[]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="Next medicine..."></td>
                    <td class="p-3"><input type="text" name="dosage[]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="Dosage"></td>
                    <td class="p-3"><input type="text" name="duration[]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="Duration"></td>
                    <td class="p-3 text-center">
                        <button type="button" class="text-red-400 hover:text-red-600 remove-row">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(newRow);

                // Add event listener to the new remove button
                newRow.querySelector('.remove-row').addEventListener('click', function() {
                    newRow.remove();
                });
            });
        }
    }
});