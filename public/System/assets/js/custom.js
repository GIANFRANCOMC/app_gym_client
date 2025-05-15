function generateMyUrl(data = null, redirect = true, type = "my_web") {

    if(type === "my_web") {

        const url = `${window.location.protocol}//${window.location.hostname}/${data?.slug}/home`;

        if(redirect) {

            window.open(url, "_self");

        }else {

            return url;

        }

    }else if(type === "my_dashboard") {

        const bytes = new TextEncoder().encode(data?.id);
        const base64 = btoa(String.fromCharCode(...bytes));

        const url = `${window.location.protocol}//${window.location.hostname}?company=${base64}`;

        if(redirect) {

            window.open(url, "_self");

        }else {

            return url;

        }

    }

}
