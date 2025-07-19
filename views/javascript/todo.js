
document.addEventListener('DOMContentLoaded', () => {
    loadTasks();
});

// vcituvanje na zadaci
function loadTasks() {
    fetch('../public/tasks.php')
        .then(response => response.json())
        .then(data => {
            const taskList = document.getElementById('task-list');
            taskList.innerHTML = '';

            data.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.classList.add('task-item');
                taskElement.innerHTML = `
                    <h2>${task.task_title}</h2>
                    <p>${task.description}</p>
                    <button class="button" onclick="editTask(${task.task_id})">Уреди</button>
                    <button class="button" onclick="deleteTask(${task.task_id})">Избриши</button>
                `;
                taskList.appendChild(taskElement);
            });
        })
        .catch(err => console.error(err));
}

// dodavanje nova zadaca
document.getElementById('task-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const title = document.getElementById('task-title').value;
    const description = document.getElementById('task-desc').value;

    fetch('../public/tasks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ title, description })
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire('Додаввањето е успешно', 'Задачата е додадена!', 'success');
        loadTasks();
    })
    .catch(err => console.error(err));
});

// ureduvanje na zadaca
function editTask(taskId) {
    const title = prompt('Уреди го насловот на задачата:');
    const description = prompt('Уреди го описот на задачата:');

    if (title && description) {
        fetch('../public/tasks.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ task_id: taskId, title, description })
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire('Уредувањето е успешно', 'Задачата е ажурирана!', 'success');
            loadTasks();
        })
        .catch(err => console.error(err));
    }
}

// brisenje na zadaca
function deleteTask(taskId) {
    fetch('../public/tasks.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ task_id: taskId })
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire('Бришењето е успешно', 'Задачата е избришана!', 'success');
        loadTasks();
    })
    .catch(err => console.error(err));
}
