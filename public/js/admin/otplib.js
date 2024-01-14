const otpAuth = 'otpauth://totp/Google%3Aaulasoftwarelibre%40gmail.com?secret=cv45fdatrghuvkfzokvegahkzly4zmng&issuer=Google';

function generateOTP(secret, elementId)
{
    const token = otplib.authenticator.generate(secret);
    console.log('Código OTP actual:', token);

    const [codeElement, buttonElement] = document.getElementById(elementId).children;
    codeElement.textContent = token;
    buttonElement.dataset.copy = token;

    const timeUsed = otplib.authenticator.timeUsed() / 30 * 100;
    const progressBar= document.getElementById(elementId).getElementsByClassName('progress-bar');
    progressBar[0].style.width = `${timeUsed}%`

    setTimeout(() => generateOTP(secret,elementId), 1000);
}
