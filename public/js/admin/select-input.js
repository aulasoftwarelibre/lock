document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll('.btn.copy');

    buttons.forEach(button => {
        button.addEventListener('click', async () => {
            const text = button.dataset.copy
            await navigator.clipboard.writeText(text)
        })
    })
});
