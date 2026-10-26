let numarProiecte = 1;
const butonAdauga = document.getElementById('butonadd');
const container = document.getElementById('containerproj');

butonAdauga.addEventListener('click', function() {
    numarProiecte++;

    // Creăm un div nou pentru a ține label-ul și input-ul
    const randNou = document.createElement('div');
    randNou.style.marginTop = '10px'; // puțin spațiu

    // Adăugăm conținutul HTML în noul div
    randNou.innerHTML = `
        <label>Proiect ${numarProiecte}:</label>
        <input type="text" name="projects[]" required>
    `;

    // Inserăm rândul nou în container
    container.appendChild(randNou);
});
// $projects = array_map('trim', explode(',', $_POST['projects_string']));