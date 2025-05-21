function base64Encode(value) {

    const bytes = new TextEncoder().encode(value);
    const base64 = btoa(String.fromCharCode(...bytes));

    return base64;

}

function generateMyUrl(data = null, redirect = true, type = "my_web", extras = null) {

    if(type === "my_web") {

        let section = extras?.section ?? "home";

        let url = `${window.location.protocol}//${window.location.hostname}/${data?.slug}/${section}`;

        if(["tracking_attendances"].includes(extras?.section)) {

            url = `${url}?branch=${base64Encode(extras?.branch?.id)}`;

        }

        if(redirect) {

            window.open(url, "_self");

        }else {

            return url;

        }

    }else if(type === "my_dashboard") {

        let url = `${window.location.protocol}//${window.location.hostname}?company=${base64Encode(data?.id)}`;

        if(redirect) {

            window.open(url, "_self");

        }else {

            return url;

        }

    }

}
