


// const toggleButton = document.getElementsByClassName('btn')[0];
// const cart = document.getElementsByClassName('cartbar')[0];

// toggleButton.addEventListener('click', () => {
//     cart.classList.toggle('active');
// });
// document.addEventListener('DOMContentLoaded', function () {
//     const toggleButton = document.querySelector('.btn');
//     const cart = document.querySelector('.cartbar');

//     toggleButton.addEventListener('click', function () {
//         cart.classList.toggle('active');
//     });
// });


   
    document.addEventListener("DOMContentLoaded", function() {
        var toggleCartBtn = document.getElementById("toggleCartBtn");
        var cartbar = document.querySelector(".cartbar");
    
        toggleCartBtn.addEventListener("click", function() {
            cartbar.classList.toggle("show-cart");
        });
   
    });


    

