
const loader = document.getElementById('loader-overlay');
const loaderBtn = document.querySelectorAll('.btn-loader');

loaderBtn.forEach(b =>{
    b.addEventListener('click' , ()=>{
        loader.classList.add('d-flex');
    })
})

const btnArrow = document.querySelector("#btnArrow");

if (btnArrow) {
    btnArrow.addEventListener("click", function() {
        document.querySelector("#arrow").classList.toggle('arrow')
    });
}