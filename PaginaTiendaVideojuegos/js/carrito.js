// carrito.js - maneja el carrito de compras con localStorage

let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

function agregarAlCarrito(nombre, precio) {
  carrito.push({ nombre, precio });
  guardarCarrito();
  alert(`"${nombre}" fue agregado al carrito.`);
}

function guardarCarrito() {
  localStorage.setItem("carrito", JSON.stringify(carrito));
  actualizarCarrito();
}

function actualizarCarrito() {
  const lista = document.getElementById("carrito-lista");
  const total = document.getElementById("carrito-total");
  if (!lista || !total) return;

  lista.innerHTML = "";
  let suma = 0;

  carrito.forEach((item, index) => {
    const li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between align-items-center";
    li.innerHTML = `
      ${item.nombre}
      <span>
        $${item.precio.toFixed(2)}
        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="quitarDelCarrito(${index})">x</button>
      </span>`;
    lista.appendChild(li);
    suma += item.precio;
  });

  total.textContent = suma.toFixed(2);
}

function quitarDelCarrito(index) {
  carrito.splice(index, 1);
  guardarCarrito();
}

function vaciarCarrito() {
  carrito = [];
  guardarCarrito();
}

function toggleCarrito() {
  const contenedor = document.getElementById("carrito-container");
  if (!contenedor) return;
  contenedor.style.display = (contenedor.style.display === "none" || contenedor.style.display === "") ? "block" : "none";
  actualizarCarrito();
}

// Verifica sesión en el servidor antes de ir a pago
function finalizarCompra() {
  if (carrito.length === 0) {
    alert("Tu carrito está vacío. Agrega productos antes de finalizar la compra.");
    return;
  }

  fetch("../verificar_sesion.php")
    .then(r => r.json())
    .then(data => {
      if (data.sesion) {
        window.location.href = "../pago.php";
      } else {
        if (confirm("Necesitas iniciar sesión para comprar. ¿Ir al login ahora?")) {
          window.location.href = "../inicio_sesion.php";
        }
      }
    })
    .catch(() => {
      // Si el fetch falla (ej. páginas en raíz), intentar directo
      window.location.href = "../pago.php";
    });
}

actualizarCarrito();
