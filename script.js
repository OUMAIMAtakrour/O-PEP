const toggleButton = document.getElementsByClassName('btn')[0]
const cart = document.getElementsByClassName('cartbar')[0]
toggleButton.addEventListner('click',()=>{
    cart.toggle('active')
})