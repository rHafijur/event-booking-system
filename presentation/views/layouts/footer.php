        <div id="toast-container"></div>
        <script>
            function localTimeToUTC(localDatetime) {
                let localDate = new Date(localDatetime);
                return localDate.toISOString();
            }
            
            function convertUTCToLocalInput(date) {
                const day = String(date.getDate()).padStart(2, "0");
                const month = String(date.getMonth()+1).padStart(2, "0");
                const year = date.getFullYear();
                
                const hours = String(date.getHours()).padStart(2, "0");
                const minutes = String(date.getMinutes()).padStart(2, "0");
                const seconds = String(date.getSeconds()).padStart(2, "0");

                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }
            
            function convertUTCToLocal(date) {
                const day = String(date.getDate()).padStart(2, "0");
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const month = monthNames[date.getMonth()];
                const year = date.getFullYear();
                
                const hours = String(date.getHours()).padStart(2, "0");
                const minutes = String(date.getMinutes()).padStart(2, "0");
                const seconds = String(date.getSeconds()).padStart(2, "0");

                return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
            }

            document.addEventListener("DOMContentLoaded", function() {
                const elements = document.querySelectorAll(".localtime");
                elements.forEach(element => {
                    const utcDatetime = element.textContent.trim(); 
                    const localDatetime = convertUTCToLocal(new Date(utcDatetime)); 
                    element.textContent = localDatetime;
                });
                const inputElements = document.querySelectorAll(".localtime-input");
                inputElements.forEach(element => {
                    const utcDatetime = element.getAttribute('data-datetime'); 
                    
                    const localDatetime = convertUTCToLocalInput(new Date(utcDatetime)); 
                    console.log(localDatetime);
                    element.value = localDatetime;
                });
            });
            
            function showToast(message, type) {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 show`;
                toast.setAttribute('role', 'alert');
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;
                toastContainer.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>