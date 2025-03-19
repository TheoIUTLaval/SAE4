function AfficheRange2(newVal) {
    var monCurseurKm = document.getElementById("monCurseurKm");
    if (newVal >= 100) {
        monCurseurKm.innerHTML = "Rayon de " + newVal + "+ ";
    } else {
        monCurseurKm.innerHTML = "Rayon de " + newVal + " ";
    }
}
