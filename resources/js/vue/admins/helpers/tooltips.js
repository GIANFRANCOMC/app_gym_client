function initTooltips(tiempo = 0){

    if(tiempo > 0){

        setTimeout(() => {

            $('[data-bs-toggle="tooltip"]').tooltip();

        }, tiempo);

    }else{

        $('[data-bs-toggle="tooltip"]').tooltip();

    }

}

function hideTooltips(tiempo = 0){

    if(tiempo > 0){

        setTimeout(() => {

            $(".tooltip").tooltip("hide");

        }, tiempo);

    }else{

        $(".tooltip").tooltip("hide");

    }

}

export { initTooltips, hideTooltips };
