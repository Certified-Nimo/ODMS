const sideMenu = document.querySelector("aside");
const menuBtn = document.querySelector("#menu-btn");
const closeBtn = document.querySelector("#close-btn");


menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
})

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
})


//logout session

document.getElementById("logoutLink").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent the default behavior of the link (e.g., navigating to "logout.php")

    // Redirect to the logout script (logout.php)
    window.location.href = "logout.php";
});

