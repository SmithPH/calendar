const calendarElement = document.getElementById("calendar");
const monthYearElement = document.getElementById("monthYear");
const modalElement = document.getElementById("eventModal");

let currentDate = new Date();

function renderCalendar(date = new Date()) {
  calendarElement.innerHTML = "";

  const year = date.getFullYear();
  const month = date.getMonth();
  const today = new Date();

  const totalDays = new Date(year, month + 1, 0).getDate();
  const firstDayOfMonth = new Date(year, month, 1).getDay();

  // Display month and year
  monthYearElement.textContent = date.toLocaleDateString("en-US", {
    month: "long",
    year: "numeric",
  });

  const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  weekDays.forEach((day) => {
    const dayElement = document.createElement("div");
    dayElement.className = "day-name";
    dayElement.textContent = day;
    calendarElement.appendChild(dayElement);
  });

  for (let i = 0; i < firstDayOfMonth; i++) {
    calendarElement.appendChild(document.createElement("div"));
  }

  // Loop through days
  for (let day = 1; day <= totalDays; day++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
      day
    ).padStart(2, "0")}`;

    const cell = document.createElement("div");
    cell.className = "day";

    if (
      day === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear()
    ) {
      cell.classList.add("today");
    }

    const dateElement = document.createElement("div");
    dateElement.className = "date-number";
    dateElement.textContent = day;
    cell.appendChild(dateElement);

    const eventsToday = events.filter((e) => e.date === dateStr);
    const eventBox = document.createElement("div");
    // eventBox.className = "event";

    // Render events
    eventsToday.forEach((event) => {
      const ev = document.createElement("div");
      ev.className = "event";

      const titleElement = document.createElement("div");
      titleElement.className = "title";
      titleElement.textContent = event.title;

      const descriptionElement = document.createElement("div");
      descriptionElement.className = "description";
      descriptionElement.textContent = event.description;

      const timeElement = document.createElement("div");
      timeElement.className = "time";
      timeElement.textContent = `${event.start_time} - ${event.end_time}`;

      ev.appendChild(titleElement);
      ev.appendChild(descriptionElement);
      ev.appendChild(timeElement);
      eventBox.appendChild(ev);
    });

    // Overlay buttons
    const overlay = document.createElement("div");
    overlay.className = "day-overlay";

    // Add btn
    const addBtn = document.createElement("div");
    addBtn.className = "overlay-add-btn";
    addBtn.textContent = "Add";
    addBtn.onclick = (e) => {
      e.stopPropagation();
      openModalForAdd(dateStr);
    };
    overlay.appendChild(addBtn);

    // Edit btn
    if (eventsToday.length > 0) {
      const editBtn = document.createElement("button");
      editBtn.className = "overlay-edit-btn";
      editBtn.textContent = "Edit";
      editBtn.onclick = (e) => {
        e.stopPropagation();
        openModalForEdit(eventsToday);
      };
      overlay.appendChild(editBtn);
    }

    cell.appendChild(overlay);
    cell.appendChild(eventBox);
    calendarElement.appendChild(cell);
  }
}

//  MODALS
// Add event Modal
function openModalForAdd(dateStr) {
  document.getElementById("formAction").value = "add";
  document.getElementById("eventId").value = "";
  document.getElementById("deleteEventId").value = "";
  document.getElementById("title").value = "";
  document.getElementById("description").value = "";
  document.getElementById("startDate").value = dateStr;
  document.getElementById("endDate").value = dateStr;
  document.getElementById("startTime").value = "09:00";
  document.getElementById("endTime").value = "10:00";

  const selector = document.getElementById("eventSelector");
  const wrapper = document.getElementById("eventSelectorWrapper");

  if (selector && wrapper) {
    selector.innerHTML = "";
    wrapper.style.display = "none";
  }

  modalElement.style.display = "flex";
}

// Edit event Modal
function openModalForEdit(eventsOnDate) {
  document.getElementById("formAction").value = "edit";
  modalElement.style.display = "flex";

  const selector = document.getElementById("eventSelector");
  const wrapper = document.getElementById("eventSelectorWrapper");
  selector.innerHTML = "<option disabled selected>Select event...</option>";

  eventsOnDate.forEach((e) => {
    const option = document.createElement("option");
    option.value = JSON.stringify(e);
    option.textContent = `${e.title} (${e.start_time} to ${e.end_time})`;
    selector.appendChild(option);
  });

  if (eventsOnDate.length > 1) {
    wrapper.style.display = "block";
  } else {
    wrapper.style.display = "none";
  }

  handleEventSelection(JSON.stringify(eventsOnDate[0]));
}

// Populate a form from selected event
function handleEventSelection(eventJSON) {
  const event = JSON.parse(eventJSON);

  document.getElementById("eventId").value = event.id;
  document.getElementById("deleteEventId").value = event.id;

  const [title, description] = event.title.split(" - ").map((e) => e.trim());
  document.getElementById("title").value = title || "";
  document.getElementById("description").value = description || "";
  document.getElementById("startDate").value = event.date || "";
  document.getElementById("endDate").value = event.date || "";
  document.getElementById("startTime").value = event.start_time || "";
  document.getElementById("endTime").value = event.end_time || "";
}

// close Modal
function closeModal() {
  modalElement.style.display = "none";
}

// Month navigation
function changeMonth(offset) {
  currentDate.setMonth(currentDate.getMonth() + offset);
  renderCalendar(currentDate);
}

// Live digital clock
function updateClock() {
  const now = new Date();
  const clock = document.getElementById("clock");
  clock.textContent = [
    now.getHours().toString().padStart(2, "0"),
    now.getMinutes().toString().padStart(2, "0"),
    now.getSeconds().toString().padStart(2, "0"),
  ].join(":");
}

// Initialisation
renderCalendar(currentDate);
updateClock();
setInterval(updateClock, 1000);
