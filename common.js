document.addEventListener("DOMContentLoaded", function () {
    const loginButton = document.getElementById("login-button");
    const logoutButton = document.getElementById("logout-button");
    const recentLink = document.getElementById("recent-link");

    let isLoggedIn = false;


    function checkLoginStatus() {
        return fetch("check_session.php", { cache: "no-store" })
            .then((response) => response.json())
            .then((data) => {
                isLoggedIn = data.loggedIn;
                return isLoggedIn;
            })
            .catch((error) => {
                console.error("Error checking login status:", error);
                return false;
            });
    }


    function initializeLoginButton() {
        checkLoginStatus().then((loggedIn) => {
            if (loggedIn) {
                loginButton.textContent = "Logged";
                loginButton.style.pointerEvents = "none";
            } else {
                loginButton.textContent = "Login";
                loginButton.style.pointerEvents = "auto";
            }
        });
    }


    if (logoutButton) {
        if (!logoutButton.hasAttribute("data-listener-added")) {
            logoutButton.addEventListener("click", function (e) {
                e.preventDefault();
                fetch("logout.php", { cache: "no-store" })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.loggedOut) {
                            alert("You have been logged out.");
                            window.location.reload();
                        }
                    })
                    .catch((error) => console.error("Error during logout:", error));
            });


            logoutButton.setAttribute("data-listener-added", "true");
        }
    }


    if (recentLink) {
        recentLink.addEventListener("click", async function (event) {
            const loggedIn = await checkLoginStatus();
            if (!loggedIn) {
                event.preventDefault();
                alert("You need to log in to access this page.");
                window.location.href = "./index.html";
            }
        });
    }


    initializeLoginButton();
});
