(function () {
  // jeśli gtag nie istnieje, to nie wysyłamy nic (unikasz errorów)
  if (typeof window.gtag !== 'function') return;

  let lastAddToCartContext = null;

  function hasPromo(rootEl) {
    if (!rootEl) return false;
    // Twoje klasy z HTML: .price-sale i .discount-amount
    return !!rootEl.querySelector('.discount-amount, .discount-percentage, .price-sale');
  }

  function text(rootEl, selector) {
    const el = rootEl ? rootEl.querySelector(selector) : null;
    return el ? el.textContent.trim() : '';
  }

  function parsePrice(plText) {
    if (!plText) return null;
    const cleaned = plText
      .replace(/\u00A0/g, ' ')
      .replace(/[^\d,.-]/g, '')
      .replace(',', '.')
      .trim();
    const v = Number(cleaned);
    return Number.isFinite(v) ? v : null;
  }

  function detectProductRoot(btn) {
    return (
      btn.closest('.product-miniature') ||
      btn.closest('.product-container') ||
      btn.closest('.product-page') ||
      document
    );
  }

  function detectProductId(btn) {
    return btn.dataset.idProduct || btn.getAttribute('data-id-product') || null;
  }

  function detectName(root) {
    return text(root, '.product-title, .h3.product-title, h1') || null;
  }

  function detectPrice(root) {
    const sale = parsePrice(text(root, '.price-sale'));
    if (sale != null) return sale;
    const regular = parsePrice(text(root, '.price'));
    return regular;
  }

  function detectDiscountValue(root) {
    const d = parsePrice(text(root, '.discount-amount'));
    return d != null ? Math.abs(d) : null;
  }

  document.addEventListener(
    'click',
    function (e) {
      const btn = e.target.closest(
        '[data-button-action="add-to-cart"], button.ajax_add_to_cart_button, .add-to-cart button'
      );
      if (!btn) return;

      const root = detectProductRoot(btn);

      lastAddToCartContext = {
        ts: Date.now(),
        productId: detectProductId(btn),
        name: detectName(root),
        price: detectPrice(root),
        discountValue: detectDiscountValue(root),
        isPromo: hasPromo(root),
      };
    },
    true
  );

  function firePromoAddToCart(ctx, quantity) {
    if (!ctx || !ctx.isPromo) return;

    window.gtag('event', 'add_to_cart_promo', {
      currency: 'PLN',
      value: ctx.price != null ? ctx.price : undefined,
      items: [
        {
          item_id: ctx.productId || undefined,
          item_name: ctx.name || undefined,
          price: ctx.price != null ? ctx.price : undefined,
          quantity: quantity || 1,
          discount_value: ctx.discountValue != null ? ctx.discountValue : undefined,
        },
      ],
    });
  }

  // 2) Odpal event dopiero po potwierdzeniu aktualizacji koszyka
  if (window.prestashop && typeof window.prestashop.on === 'function') {
    window.prestashop.on('updatedCart', function (event) {
      if (!lastAddToCartContext) return;

      // żeby nie odpalało “po czasie” przy innych update’ach koszyka
      if (Date.now() - lastAddToCartContext.ts > 8000) {
        lastAddToCartContext = null;
        return;
      }

      const qty =
        (event && event.resp && event.resp.quantity) ||
        (event && event.quantity) ||
        1;

      firePromoAddToCart(lastAddToCartContext, qty);
      lastAddToCartContext = null;
    });
  }
})();
