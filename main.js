const cards = document.getElementsByClassName("card");

for (let i = 0; i < cards.length; i++) {
    cards[i].addEventListener("mouseenter", (event) => {
        setTimeout(function () { cards[i].classList.remove("unflipped") }, 800);
        setTimeout(function () { cards[i].classList.add("flipped") }, 800);
        console.log("mouseenter")
    });

    cards[i].addEventListener("mouseleave", (event) => {
        cards[i].classList.remove("unflipped");
        cards[i].classList.add("flipped");
        console.log("mouseleave")
    });
};