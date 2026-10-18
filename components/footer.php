<!-- SCRIPT JAVASCRIPT UNTUK KLIK MENU DROPDOWN -->
<script>
    function toggleMenu() {
        document.getElementById("dropdownProfil").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.closest('.profile-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>