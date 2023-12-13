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




const dragging = (e) => {
    if(!isDragging) return;
    tabsBox.classList.add("dragging");
    tabsBox.scrollLeft -= e.movementX;
    handleIcons();
}

const dragStop = () => {
    isDragging  = false;
    tabsBox.classList.remove("dragging");
}

tabsBox.addEventListener("mousedown", () => isDragging  = true);
tabsBox.addEventListener("mousemove", dragging);
document.addEventListener("mouseup", dragStop);









const carousel = document.querySelector(".item-carousel"); 
const arrowBtns = document.querySelectorAll(".item-categ-carousel i"); 
const firstCardWidth = carousel.querySelector(".card").offsetWidth;

let isDraggingCar = false, startX, startScrollLeft;


arrowBtns.forEach(btn =>{
    btn.addEventListener("click", ()  => {
        carousel.scrollLeft += btn.id === "left" ? -firstCardWidth : firstCardWidth;
    });
});

const draggingCar = (e) => {
    if(!isDraggingCar) return
    carousel.scrollLeft = startScrollLeft - (e.pageX - startX);
}

const draggingStart = (e) => {
    isDraggingCar = true;
    carousel.classList.add("dragging");
    startX = e.pageX;
    startScrollLeft = carousel.scrollLeft;
}

const draggingStop = () => {
    isDraggingCar = false;
    carousel.classList.remove("dragging");
}

carousel.addEventListener("mousedown", draggingStart);
carousel.addEventListener("mousemove", draggingCar);
document.addEventListener("mouseup", draggingStop);



const accordionContent = document.querySelectorAll(".accordion-content");
accordionContent.forEach((item, index) => {
    let header = item.querySelector("header");
    header.addEventListener("click", () =>{
        item.classList.toggle("open");
        let description = item.querySelector(".description");
        if(item.classList.contains("open")){
            description.style.height = `${description.scrollHeight}px`;
            item.querySelector("i").classList.replace("fa-plus", "fa-minus");
        }else{
            description.style.height = "0px";
            item.querySelector("i").classList.replace("fa-minus", "fa-plus");
        }
        removeOpen(index);
    })
})
function removeOpen(index1){
    accordionContent.forEach((item2, index2) => {
        if(index1 != index2){
            item2.classList.remove("open");
            let des = item2.querySelector(".description");
            des.style.height = "0px";
            item2.querySelector("i").classList.replace("fa-minus", "fa-plus");
        }
    })
}