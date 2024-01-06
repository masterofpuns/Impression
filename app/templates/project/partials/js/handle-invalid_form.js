function scrollToInvalid()
{
    const invalidSubmit = document.querySelector('.is-invalid');

    if (invalidSubmit) {
        const rect = invalidSubmit.getBoundingClientRect();
        const offsetTop = rect.top + window.scrollY;

        const navbarHeight = document.querySelector('.navbar').offsetHeight;
        const additionalSpace = 40;

        const finalScrollPosition = offsetTop - navbarHeight - additionalSpace;

        window.scrollTo({top: finalScrollPosition, behavior: 'smooth'});
    }

}