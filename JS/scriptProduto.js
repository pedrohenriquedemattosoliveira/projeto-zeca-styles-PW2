        // Dados dos produtos
        const products = [
            {
                id: 1,
                name: "Pomada Modeladora",
                description: "Pomada para modelagem de cabelo com fixação forte",
                price: 29.90,
                category: "cabelo",
                image: "pomada.jpg"
            },
            {
                id: 2,
                name: "Óleo para Barba",
                description: "Óleo hidratante para barba com aroma amadeirado",
                price: 34.90,
                category: "barba",
                image: "oleo.jpg"
            },
            {
                id: 3,
                name: "Kit Barba Completo",
                description: "Kit com pente, óleo e balm para barba",
                price: 89.90,
                category: "kit",
                image: "kit.jpg"
            },
            {
                id: 4,
                name: "Shampoo para Barba",
                description: "Shampoo especial para limpeza da barba",
                price: 27.90,
                category: "barba",
                image: "shampoo.jpg"
            }
        ];

        let cartItems = [];
        let currentFilter = 'todos';

        // Função para renderizar produtos
        function renderProducts() {
            const productsGrid = document.querySelector('.products-grid');
            productsGrid.innerHTML = '';

            products.forEach(product => {
                if (currentFilter === 'todos' || product.category === currentFilter) {
                    const productCard = `
                        <div class="product-card">
                            <div class="product-image">
                                <img src="/api/placeholder/250/200" alt="${product.name}">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">${product.name}</h3>
                                <p class="product-description">${product.description}</p>
                                <div class="product-price">R$ ${product.price.toFixed(2)}</div>
                                <button class="add-to-cart" onclick="addToCart(${product.id})">
                                    Adicionar ao Carrinho
                                </button>
                            </div>
                        </div>
                    `;
                    productsGrid.innerHTML += productCard;
                }
            });
        }

        // Função para filtrar produtos
        function filterProducts(category) {
            currentFilter = category;
            renderProducts();
        }

        // Funções do carrinho
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (product) {
                cartItems.push(product);
                updateCartCount();
                showNotification(`${product.name} adicionado ao carrinho!`);
            }
        }

        function updateCartCount() {
            document.querySelector('.cart-count').textContent = cartItems.length;
        }

        function toggleCart() {
            const modal = document.getElementById('cart-modal');
            const currentDisplay = modal.style.display;
            modal.style.display = currentDisplay === 'block' ? 'none' : 'block';

            if (modal.style.display === 'block') {
                updateCartModal();
            }
        }

        function updateCartModal() {
            const cartItemsDiv = document.getElementById('cart-items');
            const cartTotalDiv = document.getElementById('cart-total');
            
            cartItemsDiv.innerHTML = '';
            let total = 0;

            cartItems.forEach(item => {
                cartItemsDiv.innerHTML += `
                    <div style="margin: 10px 0; padding: 10px; border-bottom: 1px solid #ddd;">
                        <span>${item.name}</span>
                        <span style="float: right">R$ ${item.price.toFixed(2)}</span>
                    </div>
                `;
                total += item.price;
            });

            cartTotalDiv.innerHTML = `
                <div style="margin-top: 20px; font-weight: bold;">
                    Total: R$ ${total.toFixed(2)}
                </div>
            `;
        }

        function showNotification(message) {
            alert(message); // Você pode implementar uma notificação mais elegante aqui
        }

        // Inicializar a página
        renderProducts();
