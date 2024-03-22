

/**
 * Ajoute un écouteur d'événement pour la touche clavier "keyup" sur l'entrée de filtre.
 * Appelle la fonction filterTable() en réponse à l'événement.
 */
var filter_input = document.getElementById('filterInput');
filter_input.addEventListener("keyup", function () {
    filterTable();
});

/**
 * Met à jour la valeur sélectionnée dans le formulaire.
 * 
 * @param {HTMLElement} input - L'élément d'entrée à partir duquel la valeur est extraite.
 */
function selectUpdate(input) {
    // Get the form_depart select element
    var select = document.getElementById('form_depart');
    
    // Set the value of the form_depart select element to the input value
    select.setAttribute('value', input.value);
}

/**
 * Filtrer le tableau en fonction de la valeur de l'entrée de filtre.
 */
function filterTable() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;

    // Get the filter input element
    input = filter_input;

    // Convert the input value to uppercase for case-insensitive filtering
    filter = input.value.toUpperCase();

    // Get the table and its rows
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those that don't match the search query
    for (i = 0; i < tr.length; i++) {
        // Get the first table cell (td) in each row
        td = tr[i].getElementsByTagName("td")[0];

        if (td) {
            // Get the text content of the table cell
            txtValue = td.textContent || td.innerText;

            // Check if the text content contains the filter string
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                // Display the row if it matches the filter
                tr[i].style.display = "";
            } else {
                // Hide the row if it doesn't match the filter
                tr[i].style.display = "none";
            }
        }
    }
}
