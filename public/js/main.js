document.addEventListener('DOMContentLoaded', function() {
    const accordionBtns = document.querySelectorAll('.accordion-button');
    
    accordionBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const targetId = this.dataset.accordion;
        const targetAccordion = document.getElementById(targetId);
        const arrowIcon = this.querySelector('.arrow img');
  
        btn.classList.toggle('open');
        targetAccordion.classList.toggle('hide');

        if (targetAccordion.classList.contains('hide')) {
          targetAccordion.style.maxHeight = "0";
        } else {
          targetAccordion.style.maxHeight = targetAccordion.scrollHeight + "px";
        }

        // Toggle rotation of the arrow
        arrowIcon.classList.toggle('rotate-180');
      });
    });
  });

  document.addEventListener("DOMContentLoaded", function() {
    // Check if there are elements with the data-fancybox attribute
    const fancyboxElements = document.querySelectorAll("[data-fancybox]");
    
    // If there are elements with data-fancybox attribute, bind FancyBox
    if (fancyboxElements.length > 0) {
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {
  const mobileNav = document.querySelector('.mobile-nav');
  const openMobileNavButtons = document.querySelectorAll('.open-mobile-nav');

  openMobileNavButtons.forEach(button => {
      button.addEventListener('click', function() {
          mobileNav.classList.toggle('hidden');
      });
  });
});