const item = document.getElementById('dragul');
const containers = document.querySelectorAll('.container');

item.addEventListener('dragstart', () => {
    item.classList.add('dragging');
    item.style.color = "#12a339";
});

item.addEventListener('dragend', () => {
    item.classList.remove('dragging');
    item.style.color = "#0C447C";
});

containers.forEach(container => {
    container.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    

    container.addEventListener('drop', () => {
        const draggingElement = document.querySelector('.dragging');
        container.appendChild(draggingElement);
    });
});