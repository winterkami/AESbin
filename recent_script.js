// Enlai Li 261068637
document.addEventListener("DOMContentLoaded", () => {
  // get all elements from document
  const tableBody = document.querySelector("tbody");
  const prevPage = document.getElementById("prevPage");
  const nextPage = document.getElementById("nextPage");
  const pageNumber = document.getElementById("pageNumber");
  const sortButtons = document.querySelectorAll(".paste-button");
  const searchInput = document.querySelector(".paste-search-bar input");
  const searchButton = document.querySelector(".paste-search-bar button");

  // global vars
  let current_page = 1;
  let current_sort = "recent";
  let current_search = "";
  let content_length = 40;

  // get entries using recent.php
  const fetchPastes = (sort, page, search = "") => {
    fetch(
      `recent.php?sort=${sort}&page=${page}&search=${encodeURIComponent(
        search
      )}`
    )
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        updateTable(data.entries);
        updatePageControl(data.has_next_page);
      });
  };
  // helper function to truncate text if it's too long
  const truncateContent = (content) => {
    if (content.length > content_length) {
      return content.slice(0, content_length);
    }
    return content;
  };
  const updateTable = (entries) => {
    // clear rows
    tableBody.innerHTML = "";
    // fill table
    entries.forEach((entry) => {
      let row = document.createElement("tr");
      row.innerHTML = `
        <!-- date -->
        <td>${entry[0]}</td> 
        <!-- clickable id -->
        <td><a href="pastes/${entry[1]}" class="paste-id-link">${
        entry[1]
      }</a></td>
        <!-- content preview -->
        <td>${truncateContent(entry[2])}</td> 
        <!-- visits -->
        <td>${entry[3]}</td> 
        `;
      tableBody.appendChild(row);
    });
  };
  const updatePageControl = (has_next_page) => {
    // previous page button
    if (current_page <= 1) {
      prevPage.disabled = true;
    } else {
      prevPage.disabled = false;
    }
    // next page button
    if (has_next_page) {
      nextPage.disabled = false;
    } else {
      nextPage.disabled = true;
    }
    // page number
    pageNumber.textContent = `Page ${current_page}`;
  };

  // event listeners for page buttons
  prevPage.addEventListener("click", () => {
    current_page--;
    fetchPastes(current_sort, current_page, current_search);
  });
  nextPage.addEventListener("click", () => {
    current_page++;
    fetchPastes(current_sort, current_page, current_search);
  });

  // event listeners for sorting buttons
  sortButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      // get the clicked button
      let new_sort = event.target.textContent.toLowerCase();
      // debug
      console.log(current_sort, new_sort);
      // update sort
      if (current_sort !== new_sort) {
        current_sort = new_sort;
        // reset page because new sort will have different order
        current_page = 1;
        // disable the other buttons and enable the current one
        sortButtons.forEach((b) => {
          b.classList.remove("active");
        });
        event.target.classList.add("active");
        fetchPastes(current_sort, current_page, current_search);
      }
    });
  });

  // event listeners for search
  searchButton.addEventListener("click", () => {
    // clean the search input
    current_search = searchInput.value.trim();
    // debug
    console.log(current_search);
    fetchPastes(current_sort, current_page, current_search);
  });
  // initial load of the page
  fetchPastes(current_sort, current_page, current_search);
});
