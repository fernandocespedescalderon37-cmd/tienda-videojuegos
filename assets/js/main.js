// Buscador dinámico
document.addEventListener('DOMContentLoaded', function() {
  const buscador = document.getElementById('buscador');
  if (buscador) {
    buscador.addEventListener('keyup', function() {
      const texto = this.value.toLowerCase();
      const productos = document.querySelectorAll('.producto-item');
      productos.forEach(function(prod) {
        const nombre = prod.querySelector('.card-title').textContent.toLowerCase();
        prod.style.display = nombre.includes(texto) ? 'block' : 'none';
      });
    });
  }

  // Navbar cambia color al hacer scroll
  window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar-main');
    if (navbar) {
      if (window.scrollY > 50) {
        navbar.style.background = 'rgba(10,10,20,0.99)';
      } else {
        navbar.style.background = 'rgba(10,10,20,0.95)';
      }
    }
  });
});