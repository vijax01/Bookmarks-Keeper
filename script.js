

document.getElementById("new-card-btn").addEventListener('click', function () {
  const newCardBtn = document.getElementById("new-card-btn");
  const inputBox = document.querySelector(".new-link-category");
  const saveBtn = document.querySelector(".save-new-category");
  newCardBtn.classList.add('d-none');
  saveBtn.classList.remove('d-none');
  inputBox.classList.remove('d-none');

});


document.addEventListener("DOMContentLoaded", () => {
  // Handle Edit Button Click
  document.querySelectorAll(".edit-btn").forEach((button) => {
    button.addEventListener("click", (e) => {
      const linkId = button.getAttribute("data-link-id");
      const linkName = button.getAttribute("data-link-name");
      const linkUrl = button.getAttribute("data-link-url");
      const linkCatId = button.getAttribute("data-link-cat-id");

      // Find the corresponding edit container
      const editContainer = document.getElementById(`edit-container-${linkCatId}`);
      if (editContainer) {
        // Populate input fields with existing data
        editContainer.querySelector(".link-edit-name").value = linkName;
        editContainer.querySelector(".link-edit-url").value = linkUrl;
        editContainer.querySelector(".link-edit-id").value = linkId;
        editContainer.style.visibility = "visible";
        editContainer.style.opacity = 1;
      }
    });
  });

  // Optionally handle hiding the edit container after saving (optional)
  document.querySelectorAll(".edit-form").forEach((form) => {
    form.addEventListener("submit", () => {
      form.closest(".edit-container").classList.add("d-none");
    });
  });
});


function confirmDelete() {
  return confirm('Are you sure you want to delete this?');
}


$(document).ready(function () {
  $('#search-box').on('input', function () {
    const query = $(this).val();
    if (query.length > 0) {
      $.ajax({
        url: 'search.php',
        method: 'GET',
        data: { q: query },
        success: function (data) {
          $('#search-results').html(data).show();
        },
      });
    } else {
      $('#search-results').hide();
    }
  });

  // Hide search results when clicking outside
  $(document).on('click', function (event) {
    if (!$(event.target).closest('#search-box, #search-results').length) {
      $('#search-box').val('');
      $('#search-results').hide();
    }
  });

  $(document).on('click', '.dropdown-item', function () {
    $('#search-box').val($(this).text());
    $('#search-results').hide();
  });
});
