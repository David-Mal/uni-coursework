$(document).ready(function () {

    let draggedItem = null;
    let cnt = 0;

    function saveToStorage() {
        const tasks = [];
        $('#todo-list .todo-item').each(function () {
            tasks.push($(this).find('.todo-text').text());
        });
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    function updateCount() {
        const n = $('#todo-list .todo-item').length;
        if (n === 0) {
            $('#counter').text('No tasks');
        } else {
            $('#counter').text(n + (n === 1 ? ' task' : ' tasks'));
        }
    }

    function createTask(text) {
        cnt ++;
        const id = 't' + cnt;

        const li = $('<li>')
            .addClass('todo-item')
            .attr('id', id)
            .attr('draggable', 'true');

        const handle = $('<span>').addClass('drag-handle').html(`<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>`);
        const span = $('<span>').addClass('todo-text').text(text);
        const btn = $('<button>').addClass('del-btn').text('✕');

        btn.on('click', function () {
            $(this).closest('.todo-item').remove();
            updateCount();
            saveToStorage();
        });

        li.append(handle, span, btn);
        return li;
    }

    function addTask() {
        const val = $('#input').val().trim();
        if (!val) return;
        const task = createTask(val);
        $('#todo-list').append(task);
        bindDragEvents(task);
        $('#input').val('');
        updateCount();
        saveToStorage();
    }

    function bindDragEvents(item) {
        item.on('dragstart', function () {
            draggedItem = $(this);
        });

        item.on('dragend', function () {
            $(this).css('opacity', '1');
            $('#todo-list .todo-item').removeClass('drag-over');
            draggedItem = null;
            saveToStorage();
        });

        item.on('dragover', function (e) {
            e.preventDefault();
            $('#todo-list .todo-item').removeClass('drag-over');
            $(this).addClass('drag-over');
        });

        item.on('dragleave', function () {
            $(this).removeClass('drag-over');
        });

        item.on('drop', function (e) {
            e.preventDefault();
            if (draggedItem && draggedItem.attr('id') !== $(this).attr('id')) {
                const dragged = draggedItem.index();
                const target = $(this).index();
                if (dragged < target) {
                    $(this).after(draggedItem);
                } else {
                    $(this).before(draggedItem);
                }
            }
            $(this).removeClass('drag-over');
        });
    }

    function loadFromStorage() {
        const stored = localStorage.getItem('tasks');
        let tasks;

        if (!stored) {
            tasks = ['Buy groceries', 'Go to the gym', 'Review pull request'];
        } else {
            tasks = JSON.parse(stored);
        }
        tasks.forEach(function (text) {
            const task = createTask(text);
            $('#todo-list').append(task);
            bindDragEvents(task);
        });
        updateCount();
    }

    $('#add-btn').on('click', function () {
        addTask();
    });

    $('#input').on('keydown', function (e) {
        if (e.key === 'Enter') addTask();
    });

    loadFromStorage();
});