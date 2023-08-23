
const loader = document.getElementById('loader-overlay');
const loaderBtn = document.querySelectorAll('.btn-loader');

loaderBtn.forEach(b =>{
    b.addEventListener('click' , ()=>{
        loader.classList.add('d-flex');
    })
})