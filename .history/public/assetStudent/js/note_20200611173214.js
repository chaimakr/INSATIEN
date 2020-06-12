document.getElementById("addNoteButton").onclick = function () {
    location.href = "{{ path('addNote') }}";
};