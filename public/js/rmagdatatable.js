function dataTable(id, nomObjet) {
    $(id).DataTable({
        responsive: true,
        "aaSorting": [[0, "asc"]],
        oLanguage: {
            sLengthMenu: "Afficher: _MENU_ " + nomObjet + "s par page ",
            sSearch: "Rechercher : ",
            sZeroRecords: "Aucune valeur trouvee",
            sInfo: "Afficher page _PAGE_ sur _PAGES_",
            sInfoFiltered: "(Filtres sur _MAX_ )",
            sInfoEmpty: "Aucun " + nomObjet + " trouve",
            oPaginate: {
                sFirst: "<<",
                sPrevious: "<",
                sNext: ">",
                sLast: ">>"
            }
        },
        select: {
            style: 'multi'
        },
        "pagingType": "full_numbers",
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}

$(document).ready(function() {
    dataTable("#table_utilisateur", "Utilisateur");
    dataTable("#table_typeCE", "Type");
    dataTable("#table_zoneImpCE", "Zone");
    dataTable("#table_indicateurBase", "Indicateur");
    dataTable("#table_indicateur", "Indicateur");
    dataTable("#table_region", "Region");
    dataTable("#table_departement", "Departement");
    dataTable("#table_commune", "Commune");
    dataTable("#table_centre", "Centre");
    dataTable("#table_faitec", "Donnée");
    dataTable("#tableau_bord_region", "Donnée");
    dataTable("#tableau_bord_departement", "Donnée");
    dataTable("#tableau_bord_commune", "Donnée");
    dataTable("#tableau_bord_centre", "Donnée");
});