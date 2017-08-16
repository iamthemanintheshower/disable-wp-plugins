$( document ).ready(function() {
    $( ".fa-thumbs-down" ).on( "click", function() {
        console.log( $( this ).data('id_folder') );
        location.href = '?id_folder=' + $( this ).data('id_folder');
    });
});