$(document).ready(function(){

    //Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #
    $('a.rouleau[href^=#]').click(function() {
        var popID = $(this).attr('rel'); // On recupere l'id du pop-up appelé

        //Faire apparaitre la pop-up avec un effet fade in
        $('#' + popID).fadeIn();

        //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
        $('#fade').css({'filter' : 'alpha(opacity=50)'}).fadeIn();

        return false;
    });

    //Fermeture de la pop-up et du fond
    $('input.close, #fade').live('click', function() { //Au clic sur le bouton ou sur le calque...
        $('#fade , .pop-up-block').fadeOut();
        $('input').parent().find('label').fadeIn('fast');
        // return false;
    });

});