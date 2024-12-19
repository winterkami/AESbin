document.addEventListener("DOMContentLoaded", function () {
    const loginButton = document.getElementById("login-button");
    const logoutButton = document.getElementById("logout-button");

    // Initialize login status
    let isLoggedIn = true; // By default, assume the user is logged in

    // Function to check the user's login status
    function checkLoginStatus() {
        fetch("check_session.php", { cache: "no-store" })
            .then((response) => response.json())
            .then((data) => {
                isLoggedIn = data.loggedIn; // Update login status based on backend response
                if (isLoggedIn) {
                    // If the user is logged in
                    loginButton.textContent = "Logged";
                    loginButton.style.pointerEvents = "none"; // Disable click
                    loginButton.style.color = "gray"; // Change color to indicate inactive state
                } else {
                    // If the user is not logged in
                    loginButton.textContent = "Login";
                    loginButton.style.pointerEvents = "auto"; // Enable click
                    loginButton.style.color = ""; // Restore default color
                }
            })
            .catch((error) => console.error("Error checking login status:", error));
    }

    // Logout functionality
    if (logoutButton) {
        logoutButton.addEventListener("click", function (e) {
            e.preventDefault(); // Prevent the default behavior of the button
            if (!isLoggedIn) {
                // If the user is already logged out
                alert("You have already logged out.");
                return;
            }
            if (confirm("Are you sure you want to log out?")) {
                fetch("logout.php", { cache: "no-store" })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.loggedOut) {
                            // If logout is successful
                            alert("You have been logged out.");
                            isLoggedIn = false; // Update the status to logged out
                            loginButton.textContent = "Login"; // Reset the login button
                            loginButton.style.pointerEvents = "auto"; // Enable click
                            loginButton.style.color = ""; // Restore default color
                        } else {
                            // If logout fails
                            alert("Logout failed. Please try again.");
                        }
                    })
                    .catch((error) => console.error("Error during logout:", error));
            }
        });
    }

    // Check login status when the page is loaded
    checkLoginStatus();
});
