document.getElementById('resetFilters').addEventListener('click', function () {
    document.querySelectorAll('.search-filters input, .search-filters select').forEach(el => el.value = '');
    document.querySelector('input[name="q"]').value = '';
});