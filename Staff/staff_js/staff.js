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


   const tabsBox = document.querySelector(".tabs-box"),
allTabs = document.querySelectorAll(".tab"),
arrowIcons = document.querySelectorAll(".icon i");

let isDragging  = false;


const handleIcons = () => {
    let scrollVal = Math.round(tabsBox.scrollLeft);
    let maxScrollableWidth = tabsBox.scrollWidth - tabsBox.clientWidth;
    arrowIcons[0].parentElement.style.display = scrollVal > 0 ? "flex" : "none";
    arrowIcons[1].parentElement.style.display = maxScrollableWidth > scrollVal ? "flex" : "none";
}

arrowIcons.forEach(icon => {
    icon.addEventListener("click", () => {
        tabsBox.scrollLeft += icon.id === "left" ? -350 : 350;
        setTimeout(() => handleIcons(), 50);
    });
});




allTabs.forEach(tab => {
    tab.addEventListener("click", () => {
        tabsBox.querySelector(".active").classList.remove("active");
        tab.classList.add("active")
    });
});




// const dragging = (e) => {
//     if(!isDragging) return;
//     tabsBox.classList.add("dragging");
//     tabsBox.scrollLeft -= e.movementX;
//     handleIcons();
// }

// const dragStop = () => {
//     isDragging  = false;
//     tabsBox.classList.remove("dragging");
// }

// tabsBox.addEventListener("mousedown", () => isDragging  = true);
// tabsBox.addEventListener("mousemove", dragging);
// document.addEventListener("mouseup", dragStop);



