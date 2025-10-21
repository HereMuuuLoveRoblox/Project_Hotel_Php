document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const offsetTop = target.offsetTop - 120; // ลดค่าตามความสูงของ Navbar
            window.scrollTo({
                top: offsetTop,
                behavior: "smooth"
            });
        }
    });
});

window.addEventListener('scroll', function () {
    const fixedNav = document.getElementById('fixedNav');
    if (window.scrollY > 200) { // ถ้าเลื่อนลงเกิน 200px
        fixedNav.classList.add('show');
    } else {
        fixedNav.classList.remove('show');
    }
});