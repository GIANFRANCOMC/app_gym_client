function verTooltips(tiempo = 0){

    if(tiempo > 0){

        setTimeout(() => {

            $('[data-toggle="tooltip"]').tooltip();

        }, tiempo);

    }else{

        $('[data-toggle="tooltip"]').tooltip();

    }

}

function ocultarTooltips(tiempo = 0){

    if(tiempo > 0){

        setTimeout(() => {

            $(".tooltip").tooltip("hide");

        }, tiempo);

    }else{

        $(".tooltip").tooltip("hide");

    }

}

export { verTooltips, ocultarTooltips };
