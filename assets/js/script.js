document.addEventListener('DOMContentLoaded', function () {
    const produtos = [
      {
        nome: "Lingerie Sensual Vermelha",
        preco: "R$ 89,90",
        imagem: "https://via.placeholder.com/300x200/FFB6C1/000000?text=Lingerie"
      },
      {
        nome: "Perfume Seduction 50ml",
        preco: "R$ 119,90",
        imagem: "https://via.placeholder.com/300x200/DDA0DD/000000?text=Perfume"
      },
      {
        nome: "Fantasia Policial Sexy",
        preco: "R$ 139,90",
        imagem: "https://via.placeholder.com/300x200/FF69B4/000000?text=Fantasia"
      }
    ];
  
    const container = document.getElementById('produtos-destaque');
  
    produtos.forEach(produto => {
      const col = document.createElement('div');
      col.className = 'col-md-4 mb-4';
      col.innerHTML = `
        <div class="card h-100">
          <img src="${produto.imagem}" class="card-img-top" alt="${produto.nome}">
          <div class="card-body">
            <h5 class="card-title">${produto.nome}</h5>
            <p class="card-text text-danger fw-bold">${produto.preco}</p>
            <a href="produto.html" class="btn btn-outline-danger w-100">Ver Detalhes</a>
          </div>
        </div>
      `;
      container.appendChild(col);
    });
  });
  