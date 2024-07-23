<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin1.css">
    <link rel="stylesheet" href="icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
   <div class="container">
    <!-- Sidebar -->
    <aside>
        <div class="logo">
            <img src="img/logo.png" alt="ODMS Logo">
            <h2>OD<span class="success">MS</span></h2>
        </div>
        <div class="sidebar">
            <a href="statistics.php?page=dashboard" class="sidebar-link" data-page="dashboard">
                <span class="material-icons-sharp"></span>
                <h3>Dashboard</h3>
            </a>
            <a href="display_users.php" class="sidebar-link" data-page="members">
                <span class="material-icons-sharp"></span>
                <h3>Members</h3>
            </a>
            <a href="lastseen.php" class="sidebar-link" data-page="reporting">
                <span class="material-icons-sharp"></span>
                <h3>Active users</h3>
            </a>
            <a href="content.php" class="sidebar-link" data-page="content_moderation">
                <span class="material-icons-sharp"></span>
                <h3>Content Moderation</h3>
            </a>
            <a href="subscribers.php" class="sidebar-link" data-page="subscriptions">
                <span class="material-icons-sharp"></span>
                <h3>Subscriptions</h3>
            </a>
            <a href="systemlogs.php" class="sidebar-link" data-page="reporting">
                <span class="material-icons-sharp"></span>
                <h3>SysLogs</h3>
            </a>
            <a href="logout.php" data-page="logout">
                <span class="material-icons-sharp"></span>
                <h3>Logout</h3>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="main-content">
        <h1>► Dashboard</h1>
        <!-- Statistics section -->
        <div class="statistics-card">
            <h3>Statistics</h3>
            <section class="dashboard">
                <div class="dashboard-box">
                    <h3>Users</h3>
                    <p>Total Users: <span id="totalUsers">100</span></p>
                </div>

                <div class="dashboard-box">
                    <h3>Male</h3>
                    <p>Male Users: <span id="maleUsers">(Percentage: <span id="malePercentage"></span>%)</p>
                </div>

                <div class="dashboard-box">
                    <h3>Female</h3>
                    <p>Female Users: <span id="femaleUsers">(Percentage: <span id="femalePercentage"></span>%)</p>
                </div>

                <div class="dashboard-box">
                    <h3>Subscribed Users</h3>
                    <p>Total: 100</p>
                </div>
            </section>
        </div>

        <!-- Bar graph for user traffic -->
        <div id="userTrafficChart"></div>
    </main>

    <!-- Right Sidebar -->
    <div class="right">
        <div class="profile">
            <div class="info">
            
            </div>
            <div class="profile-photo">
            </div>
        </div>
    </div>
   </div>

   <!-- Footer -->
   <div class="footer">
        <div class="row">
            <div class="copyright">
                © nimo 2023 All rights reserved.
            </div>
        </div>
   </div>

   <!-- Script to load statistics and user traffic graph -->
   <script src="admin.js"></script>
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const mainContent = document.getElementById('main-content');

        sidebarLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('href'); // Get the href attribute of the clicked link
                loadPage(page);
            });
        });

        function loadPage(page) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', page, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    mainContent.innerHTML = xhr.responseText;
                    // After loading the main content, load statistics data and render charts if it's the dashboard page
                    if (page === 'statistics.php?page=dashboard') {
                        loadStatistics();
                        loadUserTrafficData();
                    }
                }
            };
            xhr.send();
        }

        function loadStatistics() {
            // Load statistics
            fetch('statistics.php?page=dashboard')
                .then(response => response.json())
                .then(data => {
                    // Update DOM with statistics
                    document.getElementById('totalUsers').textContent = data.totalUsers;
                    document.getElementById('maleUsers').textContent = data.maleUsers;
                    document.getElementById('femaleUsers').textContent = data.femaleUsers;
                    document.getElementById('subscribedUsers').textContent = data.subscribedUsers;
                    document.getElementById('malePercentage').textContent = data.malePercentage;
                    document.getElementById('femalePercentage').textContent = data.femalePercentage;

                    // Load Google Charts library
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawPieChart);

                    function drawPieChart() {
                        var chartData = google.visualization.arrayToDataTable(data.genderData);

                        var options = {
                            title: 'Gender Distribution',
                            pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('genderChart'));
                        chart.draw(chartData, options);
                    }
                })
                .catch(error => console.error('Error fetching statistics:', error));
        }

        function loadUserTrafficData() {
            // Dummy data for user traffic during the day
            const userTrafficData = [
                ['Time', 'Users'],
                ['Morning', 150],
                ['Afternoon', 80],
                ['Evening', 300],
            ];

            // Load Google Charts library
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawBarChart);

            function drawBarChart() {
                var data = google.visualization.arrayToDataTable(userTrafficData);

                var options = {
                    title: 'User Traffic During the Day',
                    legend: { position: 'none' },
                    bars: 'vertical',
                    vAxis: {format: 'decimal'},
                    bar: {groupWidth: "65%"},
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('userTrafficChart'));
                chart.draw(data, options);
            }
        }

        // Load statistics and user traffic initially when the page loads
        loadStatistics();
        loadUserTrafficData();
    });
</script>
</body>
</html>
