<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "waterdc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the suite data with pass and fail counts
$sql = "
  SELECT 
     s.suite_id,
        s.project_id,
        s.name AS suite_name,
         
       
    num_of_pass_test_case AS pass,
     num_of_fail_test_case AS fail
FROM 
    testsuites s
LEFT JOIN 
    testcases t ON s.suite_id = t.suite_id
GROUP BY 
    s.suite_id, s.project_id, s.name

";

$result = $conn->query($sql);






// SQL query to fetch data


function shortenUrl($url, $maxLength = 16) {
    $parsedUrl = parse_url($url);
    $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
    $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
    $shortenedUrl = $host;

    if (!empty($path)) {
        $shortenedPath = strlen($path) > 1 ? substr($path, 0, $maxLength - strlen($host)) . '...' : '';
        $shortenedUrl .= $shortenedPath;
    }

    return $shortenedUrl;
}


$testSql = "
    SELECT urls.url_1 AS production_link, 
           urls.url_2 AS uat_link, 
           testcases.status, 
           testcases.time_taken, 
           testcases.error_message
    FROM urls
    JOIN testcases ON urls.case_id = testcases.case_id
";

$testResult = $conn->query($testSql);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome CDN -->

    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional styles for the screenshots content */



        .screenshots-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 columns */
            gap: 20px;
            align-items: center;
        }

        .screenshot-card {
            background: #fff;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .screenshot-card img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .screenshot-title {
            background-color: blue;
            margin: 10px 0 5px;
            font-size: 1.2em;
            font-weight: bold;
        }





    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <img src="/DC/img/vTech lcompany_logo white 1 1.png" alt="Logo"> <!-- Replace with your logo -->
        </div>
        <div class="links">
            <a href="#" id="dashboardLink" class="link active"><i class="fas fa-home"></i> Dashboard</a>
            <!-- Changed to home icon -->
            <a href="#" id="suitesLink" class="link"><i class="fa fa-briefcase"></i> Suites</a>
            <a href="#" id="testMarticsLink" class="link"><i class="fas fa-list"></i> Test Martics</a>
            <!-- <a href="#" id="ProjectLink" class="link"><i class="fas fa-list"></i> Project</a> -->
            <a href="#" id="screenshotsLink" class="link"><i class="fas fa-camera"></i> Screenshot</a>
            <!-- Changed to camera icon -->
            <!-- Add more links with icons as needed -->
        </div>
    </div>

    <div class="content">
        <div class="topbar">
            <span class="menu-toggle" onclick="toggleMenu()">&#9776;</span>
            <div class="logo">
                <img src="/DC/img/vTech lcompany_logo white 1 1.png" alt="Logo"> <!-- Replace with your logo -->
            </div>
        </div>






        <!-- Main content goes here -->
        <div id="dashboardContent" class="das-container">
           
            <div class="card">
                <!-- Automation Report code -->
                <div class="report-header">
                    <h2>Automation Report</h2>
                    <p id="date_take">0</p> <!-- Dynamic Date -->
                    <div class="test-cases-container">
                        <h3>TEST CASES</h3>
                        <h4 class="test-cases-number" id="totalCount">0</h4>
                    </div>
                    <div class="circle-container">
                        <canvas id="progressCircle" width="160" height="160"></canvas>
                    </div>
                    <div class="results-container">
                        <div class="result passed">
                            <p>Passed <span id="passedCount">0</span></p>
                        </div>
                        <div class="result failed">
                            <p>Failed <span id="failedCount">0</span></p>
                        </div>
                    </div>
                    <div class="time-taken">
                        <i class="fas fa-clock"></i>
                        <span id="timeTaken">Time taken 00:00 Hrs</span>
                    </div>
                </div>
            </div>

            <!-- Trend Card code -->
            <div class="trend-card">
                <div class="trend-header">
                    <h2>Trends</h2>
                    <button onclick="downloadExcel()">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
                <div class="card-content">
                    <canvas id="myChart" width="700" ></canvas>
                </div>
            </div>

            <!-- Test Suites code -->
            <div class="combined-card">
                <h2>Test Suites:
                    <?php
                    // Database connection settings
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "waterdc";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Query to count total number of suite_id
                    $sql = "SELECT COUNT(suite_id) AS total_suites FROM testsuites";
                    $resultc = $conn->query($sql);

                    // Fetch the result
                    $row = $resultc->fetch_assoc();
                    $total_suites = $row['total_suites'];

                    // Output the result
                    echo $total_suites;

                    // Close connection
                    
                    ?>
                </h2>
                <div class="combined-card-content">
                    <div class="chart-container">
                        <canvas id="testChart"></canvas>
                    </div>

                    <div class="legend-container"></div>
                </div>
            </div>
        </div>

        <!-- page 2 -->


        <!-- Placeholder for Test Martics content -->


        <div id="testMarticsContent" style="display: none;">
            <div class="suites_background">
                <div class="show-search">
                    <div class="left-section">
                        <label for="entries">Show</label>
                        <select id="entries" onchange="changeEntries()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                        <label for="entries">entries</label>
                    </div>
                    <div class="center-section">
                        <img src="/DC/img/logo-dark.png" alt="Logo" class="center-logo"> <!-- Center logo -->
                    </div>
                    <div class="right-section">
                        <p>Search: </p> <input type="text" id="search" placeholder="" oninput="searchTable()">
                    </div>
                </div>

                <div class="table_class">
                    <table class="table row-border tablecard" id="suitesTable">
                        <thead>
                            <tr class="table-header">
                                <th>Production Link</th>
                                <th>UAT Link</th>
                                <th> Status</th>
                                <th>Time(s)</th>
                                <th>Error Message</th>
                        </thead>
                        <tbody>
                            <!-- Example rows, replace with dynamic content as needed -->
                            <!-- Add rows here -->
                            <?php if ($testResult->num_rows > 0): ?>
    <?php while ($row = $testResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars(shortenUrl($row['production_link'])); ?></td>
            <td><?php echo htmlspecialchars(shortenUrl($row['uat_link'])); ?></td>
            <td><?php echo $row['status'] == 0 ? 'Passed' : 'Failed'; ?></td>
            <td><?php echo htmlspecialchars($row['time_taken']); ?></td>
            <td><?php echo htmlspecialchars($row['error_message']); ?></td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="5">No records found</td>
    </tr>
<?php endif; ?>
                        </tbody>
                    </table>

                </div>

                <div class="cs">
                    <div class="pagination-left" id="paginationInfo">
                        Showing 1 to 10 of 13 entries
                    </div>
                    <div class="pagination-right" id="paginationControls">
                        <a href="#" onclick="changePage(-1)">Previous</a>
                        <a href="#" onclick="goToPage(1)" class="active">1</a>
                        <a href="#" onclick="goToPage(2)">2</a>
                        <a href="#" onclick="goToPage(3)">3</a>
                        <a href="#" onclick="goToPage(4)">4</a>
                        <a href="#" onclick="changePage(1)">Next</a>
                    </div>
                </div>
            </div>


















        </div>


            <!-- Placeholder for Suites content -->
            <div id="suitesContent" style="display: none;">
                <div class="suites_background">
                    <div class="show-search">
                        <div class="left-section">
                            <label for="entries">Show</label>
                            <select id="entries" onchange="changeEntries()">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                            <label for="entries">entries</label>
                        </div>
                        <div class="center-section">
                            <img src="/DC/img/logo-dark.png" alt="Logo" class="center-logo"> <!-- Center logo -->
                        </div>
                        <div class="right-section">
                            <p>Search: </p> <input type="text" id="search" placeholder="" oninput="searchTable()">
                        </div>
                    </div>

                    <div class="table_class">
                        <table class="table row-border tablecard" id="suitesTable">
                            <thead>
                                <tr class="table-header">
                                    <th><a href="#" onclick="sortTable('suite_name')">Suites</a></th>
                                    <th><a href="#" onclick="sortTable('pass')">Pass</a></th>
                                    <th><a href="#" onclick="sortTable('fail')">Fail</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row["suite_name"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["pass"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["fail"]) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No data available</td></tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                        <?php $conn->close(); ?>
                    </div>

                    <div class="cs">
                        <div class="pagination-left" id="paginationInfo">
                            Showing 1 to 10 of 13 entries
                        </div>
                        <div class="pagination-right" id="paginationControls">
                            <a href="#" onclick="changePage(-1)">Previous</a>
                            <a href="#" onclick="goToPage(1)" class="active">1</a>
                            <a href="#" onclick="goToPage(2)">2</a>
                            <a href="#" onclick="goToPage(3)">3</a>
                            <a href="#" onclick="goToPage(4)">4</a>
                            <a href="#" onclick="changePage(1)">Next</a>
                        </div>
                    </div>
                </div>
            </div>





    </div>

    <!-- Placeholder for Screenshots content -->

    <div id="screenshotsContent" style="display: none;">




    </div>


    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="script.js"></script>
    <script>










        function downloadExcel() {
            fetch('fetch_trend.php')
                .then(response => response.json())
                .then(data => {
                    if (data && Array.isArray(data) && data.length > 0) {
                        const worksheet = XLSX.utils.json_to_sheet(data);
                        const workbook = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(workbook, worksheet, "Trends");
                        XLSX.writeFile(workbook, "trend_data.xlsx");
                    } else {
                        console.error('No data available to download');
                    }
                })
                .catch(error => console.error('Error fetching trend data:', error));
        }










        document.addEventListener("DOMContentLoaded", function () {
            // Get the current page's URL
            const currentPage = window.location.pathname;

            // Get the links
            const dashboardLink = document.getElementById('dashboardLink');
            const suitesLink = document.getElementById('suitesLink');
            const testMarticsLink = document.getElementById('testMarticsLink');
            const screenshotsLink = document.getElementById('screenshotsLink');
            // const ProjectLink = document.getElementById('ProjectLink');


            // Add click event listeners to the links
            dashboardLink.addEventListener('click', function (event) {
                event.preventDefault();
                showContent('dashboard');
                setActiveLink(dashboardLink);
            });

            suitesLink.addEventListener('click', function (event) {
                event.preventDefault();
                showContent('suites');
                setActiveLink(suitesLink);
            });

            testMarticsLink.addEventListener('click', function (event) {
                event.preventDefault();
                showContent('testMartics');
                setActiveLink(testMarticsLink);
            });
            ;

            screenshotsLink.addEventListener('click', function (event) {
                event.preventDefault();
                showContent('screenshots');
                setActiveLink(screenshotsLink);
            });

            // Function to show the appropriate content and hide others
            function showContent(contentType) {
                dashboardContent.style.display = 'none';
                testMarticsContent.style.display = 'none';
                suitesContent.style.display = 'none';

                screenshotsContent.style.display = 'none';

                if (contentType === 'dashboard') {
                    dashboardContent.style.display = 'block';
                } else if (contentType === 'testMartics') {
                    testMarticsContent.style.display = 'block';
                } else if (contentType === 'suites') {
                    suitesContent.style.display = 'block';
                } else if (contentType === 'screenshots') {
                    screenshotsContent.style.display = 'block';
                    // } else if (contentType === 'Project') {
                    //     ProjectContent.style.display = 'block';
                }
            }

            // Function to set the active link
            function setActiveLink(activeLink) {
                // Remove active class from all links
                dashboardLink.classList.remove('active');
                suitesLink.classList.remove('active');
                testMarticsLink.classList.remove('active');
                screenshotsLink.classList.remove('active');

                // Add active class to the clicked link
                activeLink.classList.add('active');
            }
        });





        document.addEventListener("DOMContentLoaded", function () {
        // Function to draw progress circle
        function drawProgressCircle(passedPercentage, failedPercentage) {
            const canvas = document.getElementById('progressCircle');
            const ctx = canvas.getContext('2d');
            const radius = canvas.width / 2;
            const lineWidth = 15; // Border width for the passed portion
            const failedLineWidth = 10; // Thinner border width for the failed portion

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Draw background circle
            ctx.strokeStyle = '#f3f3f3';
            ctx.lineWidth = lineWidth;
            ctx.beginPath();
            ctx.arc(radius, radius, radius - lineWidth / 2, 0, Math.PI * 2);
            ctx.stroke();

            // Draw progress arc (passed portion) with thicker border
            ctx.strokeStyle = '#00B69B'; // Green for pass
            ctx.lineWidth = lineWidth;
            ctx.beginPath();
            const startAngle = -Math.PI / 2; // Start angle (top of the circle)
            const endAngle = startAngle + (Math.PI * 2 * (passedPercentage / 100));
            ctx.arc(radius, radius, radius - lineWidth / 2, startAngle, endAngle);
            ctx.stroke();

            // Draw remaining arc (failed portion) with thinner border
            ctx.strokeStyle = '#FF6262'; // Red for fail
            ctx.lineWidth = failedLineWidth; // Thinner border for the failed portion
            ctx.beginPath();
            const failStartAngle = endAngle;
            const failEndAngle = failStartAngle + (Math.PI * 2 * (failedPercentage / 100));
            ctx.arc(radius, radius, radius - failedLineWidth / 2, failStartAngle, failEndAngle);
            ctx.stroke();

            // Draw white circle for text background
            ctx.fillStyle = '#fff';
            ctx.beginPath();
            ctx.arc(radius, radius, radius - Math.max(lineWidth, failedLineWidth) / 2 - 5, 0, Math.PI * 2); // Slightly smaller radius
            ctx.fill();

            // Draw percentage text
            ctx.fillStyle = '#00B69B'; // Green for text
            ctx.font = 'bold 24px Arial'; // Bold text
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(`${Math.round(passedPercentage)}%`, radius, radius);

            // Draw "Test Cases Passed" text
            ctx.fillStyle = '#979797'; // Grey text for label
            ctx.font = '14px Arial'; // Regular text
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('Test Cases Passed', radius, radius + 15); // Positioned below the percentage text
        }

        // Fetch data from PHP script
        fetch('fetch_test_case.php')
            .then(response => response.json())
            .then(data => {
                const total = data.total;
                const passed = data.passed;
                const failed = data.failed;
                const totalTimeTaken = data.total_time_taken;
                const dateTake = new Date(data.Date_take); // Convert to Date object

                // Format the date to "Dec 2nd 2024"
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                const formattedDate = dateTake.toLocaleDateString('en-US', options);

                // Add ordinal suffix to the day
                const day = dateTake.getDate();
                const suffix = (day % 10 === 1 && day !== 11) ? 'st' :
                    (day % 10 === 2 && day !== 12) ? 'nd' :
                        (day % 10 === 3 && day !== 13) ? 'rd' : 'th';
                const finalDate = formattedDate.replace(/\d+/, day + suffix);

                // Update the HTML with the fetched data
                document.getElementById('totalCount').textContent = total;
                document.getElementById('passedCount').textContent = passed;
                document.getElementById('failedCount').textContent = failed;
                document.getElementById('timeTaken').textContent = `Time taken ${totalTimeTaken}`;
                document.getElementById('date_take').textContent = finalDate; // Update formatted date

                drawProgressCircle(data.passedPercentage, data.failedPercentage);
            })
            .catch(error => console.error('Error fetching data:', error));





            // Fetch trend data from PHP script
            fetch('fetch_trend.php')
                .then(response => response.json())
                .then(data => {
                    const dates = data.map(item => item.date);
                    const passedData = data.map(item => item.passed);
                    const failedData = data.map(item => item.failed);

                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: dates,
                            datasets: [
                                {
                                    label: 'Failed Data',
                                    data: failedData,
                                    borderColor: '#ff6262',
                                    tension: 0.4,
                                    pointRadius: 6,
                                    pointBackgroundColor: '#ff6262'
                                },
                                {
                                    label: 'Passed Data',
                                    data: passedData,
                                    borderColor: '#00B69B',
                                    tension: 0.4,
                                    pointRadius: 6,
                                    pointBackgroundColor: '#00B69B'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        autoSkip: true,
                                        maxTicksLimit: 5
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        min: 0,
                                        max: 100,
                                        stepSize: 25
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching trend data:', error));
        });









        document.addEventListener("DOMContentLoaded", function () {
            // Fetch data from the PHP script
            fetch('fetch_data_test.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.test_name);
                    const passData = data.map(item => item.pass_count);
                    const failData = data.map(item => item.fail_count);

                    // Calculate the maximum values for pass and fail
                    const maxPass = Math.max(...passData, 0); // Ensure at least 0
                    const maxFail = Math.max(...failData, 0); // Ensure at least 0
                    const maxValue = Math.max(maxPass, maxFail);

                    // Set maximum value for y-axis to maxValue + 500
                    const yAxisMax = maxValue + 500;

                    const ctx = document.getElementById('testChart').getContext('2d');
                    const chartData = {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Pass',
                                data: passData,
                                backgroundColor: '#00B69B', // Green for passed cases
                                barThickness: 15,
                                borderWidth: 1,
                                borderRadius: {
                                    topLeft: 7.5,
                                    topRight: 7.5,
                                    bottomLeft: 7.5,
                                    bottomRight: 7.5
                                },
                                barPercentage: 0.2,
                                categoryPercentage: 0.8
                            },
                            {
                                label: 'Fail',
                                data: failData,
                                backgroundColor: '#FF6262', // Red for failed cases
                                barThickness: 15,
                                borderWidth: 1,
                                borderRadius: {
                                    topLeft: 7.5,
                                    topRight: 7.5,
                                    bottomLeft: 7.5,
                                    bottomRight: 7.5
                                },
                                barPercentage: 0.2,
                                categoryPercentage: 0.8
                            }
                        ]
                    };

                    const config = {
                        type: 'bar',
                        data: chartData,
                        options: {
                            layout: {
                                padding: {
                                    bottom: 10
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,

                                    font: {
                                        size: 16
                                    },
                                    padding: {
                                        bottom: 10
                                    },
                                    position: 'left'
                                },
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    stacked: false,
                                    title: {
                                        display: true,
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                    },
                                    beginAtZero: true,
                                    max: yAxisMax, // Set maximum value dynamically
                                    ticks: {
                                        stepSize: 100,
                                        padding: 0
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    };

                    const testChart = new Chart(ctx, config);

                    // Create the custom legend
                    const legendContainer = document.querySelector('.legend-container');
                    const legendItems = [
                        { color: '#00B69B', label: 'Passed' },
                        { color: '#FF6262', label: 'Failed' }
                    ];

                    legendItems.forEach(item => {
                        const legendItem = document.createElement('div');
                        legendItem.classList.add('legend-item');

                        const colorCircle = document.createElement('div');
                        colorCircle.classList.add('legend-color');
                        colorCircle.style.backgroundColor = item.color;

                        const label = document.createElement('span');
                        label.textContent = item.label;

                        legendItem.appendChild(colorCircle);
                        legendItem.appendChild(label);
                        legendContainer.appendChild(legendItem);
                    });

                    // Display testCount in the HTML
                    document.getElementById('suitesCount').textContent = data.reduce((total, item) => total + parseInt(item.testCount, 10), 0);
                })
                .catch(error => console.error('Error fetching data:', error));
        });


    </script>
</body>

</html>