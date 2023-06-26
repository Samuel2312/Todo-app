let newTask = [];
// get a reference to the "Add Task" button and the input fields
const addTaskBtn = document.querySelector('#quick-entry button[type="submit"]');
const titleInput = document.querySelector('#quick-title');
const priorityInput = document.querySelector('#quick-priority');
const dueDateInput = document.querySelector('#quick-due-date');

// add event listener for "Add Task" button
addTaskBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // create a new task object with the input values
  const newTask = {
    title: titleInput.value,
    priority: priorityInput.value,
    dueDate: dueDateInput.value,
    status: 'not-started',
    notes: ''
  };
  // add the new task to the task list
  addTaskToList(newTask);
  // clear the input fields
  titleInput.value = '';
  priorityInput.value = 'low';
  dueDateInput.value = '';
});

// function to add a new task to the task list
function addTaskToList(task) {
  // get a reference to the task list table and its tbody
  const taskListTable = document.querySelector('#task-list table');
  const taskListTbody = taskListTable.tBodies[0];
  // create a new row for the task
  const newRow = taskListTbody.insertRow();
  // insert the task data into the row cells
  newRow.insertCell().textContent = task.title;
  newRow.insertCell().textContent = '';
  newRow.insertCell().textContent = task.priority;
  newRow.insertCell().textContent = task.dueDate;
  newRow.insertCell().textContent = task.status;
}
// get a reference to the table and its rows
const table = document.querySelector('table');
const rows = table.tBodies[0].rows;

// add draggable attribute to each row
for (let i = 0; i < rows.length; i++) {
  rows[i].setAttribute('draggable', true);
}

// add event listeners for drag and drop
table.addEventListener('dragstart', (event) => {
  // set data attribute to the index of the row being dragged
  event.dataTransfer.setData('text/plain', event.target.parentNode.rowIndex);
});

table.addEventListener('dragover', (event) => {
  event.preventDefault();
  // set class on row being dragged over to indicate it can be dropped on
  event.target.parentNode.classList.add('drag-over');
});

table.addEventListener('drop', (event) => {
  event.preventDefault();
  // get index of row being dropped on
  const dropIndex = event.target.parentNode.rowIndex;
  // get index of row being dragged
  const dragIndex = event.dataTransfer.getData('text/plain');
  // swap positions of the two rows in the table
  const parent = rows[dropIndex].parentNode;
  const dropped = rows[dropIndex];
  const dragged = rows[dragIndex];
  parent.insertBefore(dragged, dropped.nextSibling);
  // remove class from row being dragged over
  event.target.parentNode.classList.remove('drag-over');
});
// get a reference to the "Save" button and the priority dropdown menu
const saveBtn = document.querySelector('#task-detail button[type="submit"]');
const priorityDropdown = document.querySelector('#priority');

// add event listener for "Save" button
saveBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // get the selected priority level
  const priority = priorityDropdown.value;
  // update the priority level of the task
  updateTaskPriority(priority);
});

// function to update the priority level of the task
function updateTaskPriority(priority) {
  // get a reference to the task list table and its tbody
  const taskListTable = document.querySelector('#task-list table');
  const taskListTbody = taskListTable.tBodies[0];
  // get a reference to the selected task row
  const selectedRow = taskListTbody.querySelector('.selected');
  // update the priority level in the task data object
  selectedRow.task.priority = priority;
  // update the priority level in the task list table
  selectedRow.cells[2].textContent = priority;
}
// get a reference to the task list table and its tbody
const taskListTable = document.querySelector('#task-list table');
const taskListTbody = taskListTable.tBodies[0];

// add event listener for "Status" column
taskListTbody.addEventListener('click', (event) => {
  // check if the clicked cell is in the "Status" column
  if (event.target.cellIndex === 4) {
    // get a reference to the clicked row
    const clickedRow = event.target.parentNode;
    // update the status of the task
    updateTaskStatus(clickedRow);
    // strike through or delete the task row as needed
    if (clickedRow.task.status === 'completed') {
      clickedRow.classList.add('completed');
      setTimeout(() => {
        taskListTbody.removeChild(clickedRow);
      }, 1000);
    }
  }
});

// function to update the status of the task
function updateTaskStatus(row) {
  // get the current status of the task
  const currentStatus = row.task.status;
  // update the status of the task
  if (currentStatus === 'not-started') {
    row.task.status = 'in-progress';
  } else if (currentStatus === 'in-progress') {
    row.task.status = 'completed';
  } else {
    row.task.status = 'not-started';
  }
  // update the status in the task list table
  row.cells[4].textContent = row.task.status;
}
// get a reference to the "Save" button and the due date input field

// add event listener for "Save" button
saveBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // get the due date of the task
  const dueDate = new Date(dueDateInput.value);
  // create a new task object with the input values
  const newTask = {
    title: titleInput.value,
    priority: priorityInput.value,
    dueDate: dueDate,
    status: 'not-started',
    notes: ''
  };
  // add the new task to the appropriate section of the task list
  if (isToday(dueDate)) {
    addTaskToToday(newTask);
  } else {
    addTaskToCalendar(newTask);
  }
  // clear the input fields
  titleInput.value = '';
  priorityInput.value = 'low';
  dueDateInput.value = '';
});

// function to check if a date is today
function isToday(date) {
  const today = new Date();
  return date.getFullYear() === today.getFullYear() &&
         date.getMonth() === today.getMonth() &&
         date.getDate() === today.getDate();
}

// function to add a new task to the "Today" section of the task list
function addTaskToToday(task) {
  // get a reference to the "Today" section of the task list
  const todaySection = document.querySelector('#today');
  // create a new task row
  const newRow = createTaskRow(task);
  // add the new task row to the "Today" section
  todaySection.querySelector('tbody').appendChild(newRow);
}


// get a reference to the "Save" button and the status dropdown menu

const statusDropdown = document.querySelector('#status');

// add event listener for "Save" button
saveBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // get the selected status
  const status = statusDropdown.value;
  // update the status of the task
  updateTaskStatus(status);
});

// function to update the status of the task
function updateTaskStatus(status) {
  // get a reference to the task list table and its tbody
  const taskListTable = document.querySelector('#task-list table');
  const taskListTbody = taskListTable.tBodies[0];
  // get a reference to the selected task row
  const selectedRow = taskListTbody.querySelector('.selected');
  // update the status in the task data object
  selectedRow.task.status = status;
  // update the status in the task list table
  selectedRow.cells[4].textContent = status;
}


// get a reference to the "Add Task" button in the "Quick Entry" section
const quickEntryBtn = document.querySelector('#quick-entry button[type="submit"]');

// add event listener for "Add Task" button in "Quick Entry" section
quickEntryBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // create a new task object with the input values
  const title = document.querySelector('#quick-title').value;
  const priority = document.querySelector('#quick-priority').value;
  const dueDate = document.querySelector('#quick-due-date').value;
  const status = 'not-started';
  const notes = '';
  const newTask = { title, priority, dueDate, status, notes };
  // add the new task to the task list
  addTaskToList(newTask);
  // clear the input fields
  document.querySelector('#quick-title').value = '';
  document.querySelector('#quick-priority').value = 'low';
  document.querySelector('#quick-due-date').value = '';
});

// add event listeners to the task rows in the task list table
const taskRows = document.querySelectorAll('#task-list tbody tr');
taskRows.forEach((row) => {
  // add event listener for click on task row
  row.addEventListener('click', () => {
    // remove "selected" class from all rows
    taskRows.forEach((r) => r.classList.remove('selected'));
    // add "selected" class to clicked row
    row.classList.add('selected');
    // display task details in "Task Detail" section
    displayTaskDetails(row.task);
  });
});

// function to display task details in "Task Detail" section
function displayTaskDetails(task) {
  // get a reference to the "Task Detail" section
  const taskDetailSection = document.querySelector('#task-detail');
  // display the task details in the input fields
  taskDetailSection.querySelector('#title').value = task.title;
  taskDetailSection.querySelector('#priority').value = task.priority;
  taskDetailSection.querySelector('#due-date').value = task.dueDate;
  taskDetailSection.querySelector('#status').value = task.status;
  taskDetailSection.querySelector('#notes').value = task.notes;
}
// get a reference to the "Save" button in the "Task Detail" section


// add event listener for "Save" button in "Task Detail" section
saveBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // get a reference to the selected task row
  const selectedRow = document.querySelector('#task-list tbody tr.selected');
  // update the task data object with the new details
  selectedRow.task.title = document.querySelector('#title').value;
  selectedRow.task.priority = document.querySelector('#priority').value;
  selectedRow.task.dueDate = document.querySelector('#due-date').value;
  selectedRow.task.status = document.querySelector('#status').value;
  selectedRow.task.notes = document.querySelector('#notes').value;
  // update the task list table with the new details
  selectedRow.cells[0].textContent = selectedRow.task.title;
  selectedRow.cells[1].textContent = selectedRow.task.priority;
  selectedRow.cells[2].textContent = selectedRow.task.dueDate;
  selectedRow.cells[3].textContent = selectedRow.task.status;
});
// get a reference to the "Delete" button in the "Task Detail" section
const deleteBtn = document.querySelector('#task-detail button[type="button"]');

// add event listener for "Delete" button in "Task Detail" section
deleteBtn.addEventListener('click', (event) => {
  event.preventDefault();
  // get a reference to the selected task row
  const selectedRow = document.querySelector('#task-list tbody tr.selected');
  // remove the selected task row from the task list table
  selectedRow.parentNode.removeChild(selectedRow);
});



//To add tasks to FullCalendar when clicking on the "save" button
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth'
  });

  calendar.render();

  // Add a new task when the form is submitted
  document.getElementById('new-task-form').addEventListener('submit', function(event) {
    event.preventDefault();

    var title = document.getElementById('new-task-title').value;
    var dueDate = document.getElementById('new-task-due-date').value;

    // Add the task to the calendar
    calendar.addEvent({
      title: title,
      start: dueDate
    });

    // Reset the form
    document.getElementById('new-task-form').reset();
  });
});
