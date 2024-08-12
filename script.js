
function toggleMenu() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('open');
}





document.addEventListener("DOMContentLoaded", function () {
    const currentPage = window.location.pathname;
    const dashboardLink = document.getElementById('dashboardLink');
    const suitesLink = document.getElementById('suitesLink');

    if (currentPage.includes('suites.html')) {
        dashboardLink.classList.remove('active');
        suitesLink.classList.add('active');
    } else if (currentPage === '/' || currentPage.includes('dashboard.html')) {
        suitesLink.classList.remove('active');
        dashboardLink.classList.add('active');
    }

    initPagination();
});

let currentPage = 1;
let rowsPerPage = 10;

function initPagination() {
    const table = document.getElementById("suitesTable");
    const totalRows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
    updatePaginationInfo(currentPage, rowsPerPage, totalRows);
    displayRows(currentPage, rowsPerPage, totalRows);
}

function changePage(direction) {
    const table = document.getElementById("suitesTable");
    const totalRows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    currentPage += direction;
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;
    updatePaginationInfo(currentPage, rowsPerPage, totalRows);
    displayRows(currentPage, rowsPerPage, totalRows);
}

function goToPage(pageNumber) {
    const table = document.getElementById("suitesTable");
    const totalRows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    currentPage = pageNumber;
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;
    updatePaginationInfo(currentPage, rowsPerPage, totalRows);
    displayRows(currentPage, rowsPerPage, totalRows);
}

function updatePaginationInfo(page, rowsPerPage, totalRows) {
    const startRow = (page - 1) * rowsPerPage + 1;
    const endRow = Math.min(page * rowsPerPage, totalRows);
    document.getElementById("paginationInfo").textContent = `Showing ${startRow} to ${endRow} of ${totalRows} entries`;
}

function displayRows(page, rowsPerPage, totalRows) {
    const table = document.getElementById("suitesTable");
    const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
    const start = (page - 1) * rowsPerPage;
    const end = page * rowsPerPage;

    for (let i = 0; i < totalRows; i++) {
        rows[i].style.display = i >= start && i < end ? '' : 'none';
    }
}

function changeEntries() {
    const select = document.getElementById("entries");
    const value = select.value;
    const table = document.getElementById("suitesTable");
    const totalRows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

    if (value === "all") {
        rowsPerPage = totalRows;
        currentPage = 1;
    } else {
        rowsPerPage = parseInt(value, 10);
        currentPage = 1;
    }

    updatePaginationInfo(currentPage, rowsPerPage, totalRows);
    displayRows(currentPage, rowsPerPage, totalRows);
}




function updatePaginationControls() {
const links = document.querySelectorAll('#paginationControls a');
links.forEach(link => {
link.classList.remove('active'); // Remove active class from all links
});
document.querySelector(`#paginationControls a:nth-child(${currentPage + 1})`).classList.add('active'); // Add active class to current page
}

function changePage(direction) {
const table = document.getElementById("suitesTable");
const totalRows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
const totalPages = Math.ceil(totalRows / rowsPerPage);
currentPage += direction;
if (currentPage < 1) currentPage = 1;
if (currentPage > totalPages) currentPage = totalPages;
updatePaginationInfo(currentPage, rowsPerPage, totalRows);
displayRows(currentPage, rowsPerPage, totalRows);
updatePaginationControls(); // Update active class
}

function goToPage(pageNumber) {
const table = document.getElementById("suitesTable");
const totalRows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
const totalPages = Math.ceil(totalRows / rowsPerPage);
currentPage = pageNumber;
if (currentPage < 1) currentPage = 1;
if (currentPage > totalPages) currentPage = totalPages;
updatePaginationInfo(currentPage, rowsPerPage, totalRows);
displayRows(currentPage, rowsPerPage, totalRows);
updatePaginationControls(); // Update active class
}



function searchTable() {
    const input = document.getElementById("search");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("suitesTable");
    const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
    const totalRows = rows.length;
    let visibleRows = 0;

    for (let i = 0; i < totalRows; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let rowVisible = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                rowVisible = true;
                break;
            }
        }

        rows[i].style.display = rowVisible ? '' : 'none';
        if (rowVisible) visibleRows++;
    }

    const totalPages = Math.ceil(visibleRows / rowsPerPage);
    if (currentPage > totalPages) currentPage = totalPages;
    updatePaginationInfo(currentPage, rowsPerPage, visibleRows);
    displayRows(currentPage, rowsPerPage, visibleRows);
}


