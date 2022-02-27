document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll('.btn.copy');

    buttons.forEach(button => {
        const text = button.dataset.copy
        button.addEventListener('click', async () => {
            await navigator.clipboard.writeText(text)
        })
    })
});
