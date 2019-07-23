function filterUsers() {
    // Declare variables
    var input, filter, table, tr, td, i, select;
    nameInput = document.getElementById("nameInput");
    filterName = nameInput.value.toUpperCase();
    roleInput = document.getElementById("roleInput");
    filterRole = roleInput.value.toUpperCase();
    dateInput = document.getElementById("dateInput");
    filterDate = dateInput.value;
    table = document.getElementById("filterTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        tdName = tr[i].getElementsByTagName("td")[0];
        tdRole = tr[i].getElementsByTagName("td")[2];
        tdDate = tr[i].getElementsByTagName("td")[1];
        if (tdName || tdRole || tdDate) {
            if (tdName.innerHTML.toUpperCase().indexOf(filterName) > -1 && 
                    tdRole.innerHTML.toUpperCase().indexOf(filterRole) > -1 && 
                    tdDate.innerHTML.indexOf(filterDate) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }

}