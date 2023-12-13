document.addEventListener("DOMContentLoaded", function () {
    const sidebarLinks = document.querySelectorAll(".sidebar-link");

    sidebarLinks.forEach((link) => {
        link.addEventListener("click", function (event) {
            
            if (!this.classList.contains("active")) {
                // Remove 'active' class from all sidebar links
                sidebarLinks.forEach((el) => el.classList.remove("active"));
                // Add 'active' class to the clicked link
                this.classList.add("active");
            } else {
                // Remove 'active' class when clicked again
                this.classList.remove("active");
            }
        });
    });
});

$('.open-btn').on('click', function(){
    $('.left-container').addClass('active');
});

$('.close-btn').on('click', function(){
    $('.left-container').removeClass('active');
});
