document.addEventListener("DOMContentLoaded", function() {
    const alertElements = document.querySelectorAll(".alert");
    if (alertElements.length > 0) {
        alertElements.forEach(function(alert) {
            setTimeout(function() {
                alert.style.display = "none";
            }, 3000);
        });
    }
});

