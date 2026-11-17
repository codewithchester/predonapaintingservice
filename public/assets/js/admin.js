(() => {
  'use strict';
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
      if (!window.confirm(form.dataset.confirm || 'Are you sure?')) event.preventDefault();
    });
  });
  document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', () => {
      const file = input.files?.[0];
      if (!file) return;
      const allowed = ['image/jpeg', 'image/png', 'image/webp'];
      if (!allowed.includes(file.type)) {
        window.alert('Please choose a JPG, PNG or WebP image.');
        input.value = '';
      } else if (file.size > 5 * 1024 * 1024) {
        window.alert('The image must be 5MB or smaller.');
        input.value = '';
      }
    });
  });
})();
