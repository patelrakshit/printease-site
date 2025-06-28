fetch('assets/products.json')
     .then(res => res.json())
     .then(products => {
          const grid = document.getElementById('product-grid');
          products.forEach(product => {
               const card = `
                    <div class="bg-white p-4 rounded shadow hover:shadow-lg">
                         <img src="${product.image}" alt="${product.title}" class="rounded mb-3 w-full h-40 object-cover" />
                         <h3 class="text-xl font-semibold mb-1">${product.title}</h3>
                         <p class="text-sm mb-2">${product.description}</p>
                         <p class="font-bold text-blue-600 mb-3">${product.price}</p>
                         <a href="upload.html" class="bg-blue-500 text-white px-4 py-2 rounded text-sm">Order Now</a>
                    </div>
               `;
               grid.innerHTML += card;
          });
     })
     .catch(err => console.error('Failed to load products:', err));
