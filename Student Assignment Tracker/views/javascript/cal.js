const months = [
  "Јануари", "Февруари", "Март", "Април", "Мај", "Јуни", 
  "Јули", "Август", "Септември", "Октомври", "Ноември", "Декември"
];

const currentDate = document.querySelector('.current-date');
const prevButton = document.querySelector('.icons span:first-child');
const nextButton = document.querySelector('.icons span:last-child');
let date = new Date();

// To store selected dates in localStorage
let selectedDates = [];

async function fetchAndProcessDates() {
  try {
      const response = await fetch('../public/assignments.php');
      if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
      }
      const data = await response.json();
      selectedDates = data.map(item => item.due_date); // Map dates
  } catch (err) {
      console.error('Error fetching data:', err);
  }
}

async function renderCalendar() {

  await fetchAndProcessDates();
  const daysContainer = document.querySelector('.days');
  daysContainer.innerHTML = ''; // Clear previous days
  const currentMonth = date.getMonth();
  const currentYear = date.getFullYear();

  // Set current date label using the months array
  currentDate.textContent = `${months[currentMonth]} ${currentYear}`;

  // Get first and last day of the month
  const firstDay = new Date(currentYear, currentMonth, 1).getDay();
  const lastDate = new Date(currentYear, currentMonth + 1, 0).getDate();

  // Add padding for the first week
  for (let i = 0; i < firstDay; i++) {
    const emptyCell = document.createElement('li');
    emptyCell.classList.add('inactive');
    daysContainer.appendChild(emptyCell);
  }

  // Add the days of the month
  for (let i = 1; i <= lastDate; i++) {
    const dayCell = document.createElement('li');
    dayCell.textContent = i;

    // Highlight current day
    if (
      i === new Date().getDate() &&
      currentMonth === new Date().getMonth() &&
      currentYear === new Date().getFullYear()
    ) {
      dayCell.classList.add('active');
    }
    // Check if this day is in selectedDates and apply "selected" class
    const dateKey = `${currentYear}-${currentMonth}-${i}`;

    selectedDates.forEach(date => {
      const day = new Date(date).getDate(); // Extract the day of the month from the date
      const month = new Date(date).getMonth();
      const year = new Date(date).getFullYear();
      if (i === day && month===currentMonth && year===currentYear) {
          dayCell.classList.add('selected');
      }
  });
    // Add click event for toggling selection
    dayCell.addEventListener('click', () => {
      if (dayCell.classList.contains('selected')) {
        dayCell.classList.remove('selected');
        delete selectedDates[dateKey]; // Remove from selected dates
      } else {
        dayCell.classList.add('selected');
        selectedDates[dateKey] = true; // Add to selected dates
      }
      // Save updated selection to localStorage
      localStorage.setItem("selectedDates", JSON.stringify(selectedDates));
    });

    daysContainer.appendChild(dayCell);
  }
}

// Navigate to the previous month
prevButton.addEventListener('click', () => {
  date.setMonth(date.getMonth() - 1);
  renderCalendar();
});

// Navigate to the next month
nextButton.addEventListener('click', () => {
  date.setMonth(date.getMonth() + 1);
  renderCalendar();
});

// Initial render
renderCalendar();