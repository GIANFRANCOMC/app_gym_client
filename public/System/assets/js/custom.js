function generateMyUrl(data = null) {

    return window.open(`${window.location.protocol}//${window.location.hostname}/${data?.slug}/home`, "_blank");

}
