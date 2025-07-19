// gi zimame podatocite od backend-ot
fetch('../public/for_you.php')
.then(response => response.json())
.then(data => {
        console.log("Zdravo");
    const ctx = document.getElementById('averageGradeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Просечна Оценка'],
            datasets: [{
                label: 'Вашата просечна оценка',
                data: [data.average_grade],
                backgroundColor: '#4a6db4',
                borderColor: '#2c3e50',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true },
            },
            scales: {
                y: { beginAtZero: true },
            },
        }        
    });

    // prikazuvanje na prosecnata ocenka
    document.getElementById('average-grade').textContent = data.average_grade;

    const assignmentsList = document.getElementById('assignments-list');
    const today = new Date(); // Get the current date
    
    // Filter assignments to include only those with 'Completed' status
    const completedAssignments = data.assignments.filter(assignment => assignment.status_of_assignment === 'Completed');
    
    if (completedAssignments.length > 0) {
        completedAssignments.forEach(assignment => {
            const li = document.createElement('li');
    
            const dueDate = new Date(assignment.due_date);
            const progress = 100;  // All displayed assignments are completed, so progress is 100%
    
            li.innerHTML = `<strong>${assignment.assignment_title}</strong><br>
                            Рок: ${assignment.due_date}<br>
                            Статус: ${assignment.status_of_assignment}<br>
                            <div class="progress-bar">
                                <div class="progress" style="width: ${progress}%;"></div>
                            </div>`;
            assignmentsList.appendChild(li);
        });
    } else {
        assignmentsList.innerHTML = '<li>Нема постоечки задачи со статус "Completed".</li>';
    }
    // prikazuvanje na zadacite
    // const assignmentsList = document.getElementById('assignments-list');
    // const today = new Date();  // go zimame momentalniot datum
    
    // if(data.assignments.length > 0) {
    //     data.assignments.forEach(assignment => {
    //         const li = document.createElement('li');
            
    //         const dueDate = new Date(assignment.due_date);
    //         let progress = assignment.status_of_assignment === 'Completed' ? 100 :
    //                        (dueDate < today && assignment.status_of_assignment !== 'Completed') ? 0 : 50;  // 0 za zadaci so pominat rok, 50 za zadaci vo tek

    //         li.innerHTML = `<strong>${assignment.assignment_title}</strong><br>
    //                         Рок: ${assignment.due_date}<br>
    //                         Статус: ${assignment.status_of_assignment}<br>
    //                         <div class="progress-bar">
    //                             <div class="progress" style="width: ${progress}%;"></div>
    //                         </div>`;
    //         assignmentsList.appendChild(li);
    //     });
    // } else {
    //     assignmentsList.innerHTML = '<li>Нема постоечки задачи.</li>';
    // }

    // // prikazuvanje na zadacite od to-do listata
    // const tasksList = document.getElementById('tasks-list');

    // if(data.tasks.length > 0) {

    //     data.tasks.forEach(task => {

    //         const li = document.createElement('li');
    //         li.innerHTML = `<strong>${task.task_title}</strong><br>
    //                         Опис: ${task.description}`;
    //         tasksList.appendChild(li);

    //     });

    // } else {
    //     tasksList.innerHTML = '<li>Нема постоечки задачи во To-Do листата.</li>';
    // }

    // // prikazuvanje na izvestuvanja
    // const notificationsList = document.getElementById('notifications-list');

    // if(data.notifications.length > 0) {
    //     data.notifications.forEach(notification => {
    //         const li = document.createElement('li');
    //         const icon = notification.notification_type === 'task' ? '📝' : '🔔'; // primer za ikona za notifikacii
    //         li.innerHTML = `<strong>${icon} ${notification.notification_type}:</strong><br>
    //                         ${notification.message}<br>
    //                         Дата: ${notification.notification_date}`;
    //         notificationsList.appendChild(li);
    //     });
    // } else {
    //     notificationsList.innerHTML = '<li>Нема нови известувања.</li>';
    // }

})
.catch(error => console.error('Грешка при зимање на податоци', error));