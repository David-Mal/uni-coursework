document.addEventListener("DOMContentLoaded", () => {
    // 1. Încărcăm fișierele deja salvate în sesiune (sau un obiect gol dacă e prima accesare)
    let sessionData = JSON.parse(sessionStorage.getItem("viewedFiles")) || {};

    // 2. Colectăm fișierele din pagina curentă și le adăugăm în obiect folosind ID-ul ca cheie unică
    const currentRows = document.querySelectorAll(".file-row");
    currentRows.forEach(row => {
        const id = row.getAttribute("data-id");
        const filename = row.getAttribute("data-filename");
        
        if (id && filename) {
            sessionData[id] = filename;
        }
    });

    // Salvăm obiectul actualizat înapoi în sessionStorage
    sessionStorage.setItem("viewedFiles", JSON.stringify(sessionData));

    // 3. Calculăm frecvența numelor de fișiere salvate
    let nameCounts = {};
    let maxCount = 0;
    let mostPopularFile = "Niciunul";

    Object.values(sessionData).forEach(filename => {
        nameCounts[filename] = (nameCounts[filename] || 0) + 1;

        if (nameCounts[filename] > maxCount) {
            maxCount = nameCounts[filename];
            mostPopularFile = filename;
        }
    });

    // 4. Afișăm rezultatul în elementul HTML dedicat
    const displayElement = document.getElementById("popular-file");
    if (displayElement) {
        displayElement.textContent = maxCount > 0 ? `${mostPopularFile} (văzut de ${maxCount} ori)` : "Niciunul";
    }
});