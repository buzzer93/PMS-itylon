/**
 * Fonction pour générer le menu de navigation complet.
 *
 * @param {string} linkClass Classe CSS supplémentaire pour les liens.
 * @returns {string} Le code HTML du menu de navigation.
 */
function nav_menu(linkClass = '') {
    // Concaténer les éléments de menu avec leurs liens respectifs
    return `<ul class='nav-list'>
            ${nav_item('/index.php', 'Planning', linkClass)}
            ${nav_item('/reservation.php', 'Réservations', linkClass)}
            ${nav_item('/gestion.php', 'Gestion', linkClass)}
            ${nav_item('/compta.php', 'Comptabilité', linkClass)}
            ${nav_item('/logout.php', 'Déconnexion', linkClass)}
            </ul>`;
}
/**
 * Fonction pour générer un élément de menu de navigation.
 *
 * @param {string} lien      Lien vers la page.
 * @param {string} titre     Texte du lien.
 * @param {string} linkClass Classe CSS supplémentaire pour le lien.
 * @returns {string} Le code HTML de l'élément de menu.
 */
function nav_item(lien, titre, linkClass = '') {
    var classe = 'nav-link';

    // Ajouter la classe 'active' si le lien correspond à la page actuelle
    if (window.location.pathname === lien) {
        classe += ' active';
    }
    // Générer et retourner le code HTML de l'élément de menu
    return `<li class="nav-item">
                <a class="nav-link ${linkClass} ${classe}" href="${lien}">${titre}</a>
            </li>`;
}
document.getElementById('menuContainer').innerHTML = nav_menu();
